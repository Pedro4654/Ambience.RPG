/**
 * GLB Viewer - Visualizador de Modelos 3D
 * Usa Three.js para renderizar arquivos .glb com análise de metadados
 */

(function() {
    'use strict';

    // Verificar se Three.js está disponível
    if (typeof THREE === 'undefined') {
        console.error('Three.js não está carregado. Certifique-se de incluir a biblioteca.');
        return;
    }

    // Namespace global
    window.GLBViewer = {
        // Cache de viewers criados
        viewers: {},
        
        /**
         * Criar viewer para um arquivo GLB
         */
        createViewer: function(containerId, glbFile, options = {}) {
            const container = document.getElementById(containerId);
            if (!container) {
                console.error('Container não encontrado:', containerId);
                return null;
            }

            // Configurações padrão
            const config = {
                width: options.width || container.clientWidth || 600,
                height: options.height || container.clientHeight || 400,
                backgroundColor: options.backgroundColor || 0x1a202c,
                autoRotate: options.autoRotate !== false,
                showGrid: options.showGrid !== false,
                showAxes: options.showAxes !== false,
                ...options
            };

            // Criar cena
            const scene = new THREE.Scene();
            scene.background = new THREE.Color(config.backgroundColor);

            // Adicionar luz ambiente
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
            scene.add(ambientLight);

            // Adicionar luz direcional
            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(5, 10, 7.5);
            scene.add(directionalLight);

            // Adicionar luz de preenchimento
            const fillLight = new THREE.DirectionalLight(0xffffff, 0.3);
            fillLight.position.set(-5, 0, -5);
            scene.add(fillLight);

            // Criar câmera
            const camera = new THREE.PerspectiveCamera(
                45,
                config.width / config.height,
                0.1,
                1000
            );
            camera.position.set(0, 2, 5);

            // Criar renderer
            const renderer = new THREE.WebGLRenderer({ 
                antialias: true,
                alpha: true 
            });
            renderer.setSize(config.width, config.height);
            renderer.setPixelRatio(window.devicePixelRatio);
            renderer.shadowMap.enabled = true;
            container.appendChild(renderer.domElement);

            // Adicionar grid (se habilitado)
            if (config.showGrid) {
                const gridHelper = new THREE.GridHelper(10, 10, 0x444444, 0x222222);
                scene.add(gridHelper);
            }

            // Adicionar axes helper (se habilitado)
            if (config.showAxes) {
                const axesHelper = new THREE.AxesHelper(2);
                scene.add(axesHelper);
            }

            // Criar controles de órbita
            const controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.dampingFactor = 0.05;
            controls.autoRotate = config.autoRotate;
            controls.autoRotateSpeed = 2.0;
            controls.enableZoom = true;
            controls.minDistance = 1;
            controls.maxDistance = 20;

            // Variáveis do viewer
            const viewer = {
                scene,
                camera,
                renderer,
                controls,
                model: null,
                modelScale: 1.0,
                metadata: {},
                isLoading: false,
                isLoaded: false,
                error: null,
                animations: [],
                mixer: null,
                clock: new THREE.Clock(),
                
                /**
                 * Carregar modelo GLB
                 */
                loadModel: async function(file) {
                    this.isLoading = true;
                    this.error = null;

                    try {
                        // Ler arquivo como ArrayBuffer
                        const arrayBuffer = await this.readFileAsArrayBuffer(file);
                        
                        // Analisar metadados
                        this.metadata = await this.analyzeGLB(arrayBuffer, file);
                        
                        // Verificar segurança
                        const securityCheck = this.checkSecurity(this.metadata);
                        if (!securityCheck.safe) {
                            throw new Error('Arquivo não passou na verificação de segurança: ' + securityCheck.reason);
                        }

                        // Carregar modelo com GLTFLoader
                        const loader = new THREE.GLTFLoader();
                        const gltf = await new Promise((resolve, reject) => {
                            loader.parse(arrayBuffer, '', resolve, reject);
                        });

                        // Remover modelo anterior se existir
                        if (this.model) {
                            this.scene.remove(this.model);
                        }

                        // Adicionar novo modelo
                        this.model = gltf.scene;
                        this.scene.add(this.model);

                        // Normalizar escala e centralizar modelo
                        this.normalizeAndCenterModel();

                        // Processar animações
                        if (gltf.animations && gltf.animations.length > 0) {
                            this.animations = gltf.animations;
                            this.mixer = new THREE.AnimationMixer(this.model);
                            
                            // Reproduzir primeira animação
                            const action = this.mixer.clipAction(gltf.animations[0]);
                            action.play();
                        }

                        this.isLoaded = true;
                        this.isLoading = false;

                        return {
                            success: true,
                            metadata: this.metadata
                        };

                    } catch (error) {
                        this.isLoading = false;
                        this.error = error.message;
                        console.error('Erro ao carregar modelo:', error);
                        
                        return {
                            success: false,
                            error: error.message
                        };
                    }
                },

                /**
                 * Ler arquivo como ArrayBuffer
                 */
                readFileAsArrayBuffer: function(file) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = (e) => resolve(e.target.result);
                        reader.onerror = reject;
                        reader.readAsArrayBuffer(file);
                    });
                },

                /**
                 * Analisar arquivo GLB e extrair metadados
                 */
                analyzeGLB: async function(arrayBuffer, file) {
                    const view = new DataView(arrayBuffer);
                    
                    // Verificar magic number (glTF)
                    const magic = view.getUint32(0, true);
                    if (magic !== 0x46546C67) { // 'glTF' em ASCII
                        throw new Error('Arquivo não é um GLB válido');
                    }

                    // Ler versão
                    const version = view.getUint32(4, true);
                    
                    // Ler tamanho total
                    const length = view.getUint32(8, true);

                    // Metadados básicos
                    const metadata = {
                        fileName: file.name,
                        fileSize: file.size,
                        fileSizeFormatted: this.formatBytes(file.size),
                        fileType: file.type,
                        glbVersion: version,
                        glbLength: length,
                        lastModified: new Date(file.lastModified).toLocaleString('pt-BR'),
                        
                        // Estatísticas (serão preenchidas após parse)
                        meshCount: 0,
                        vertexCount: 0,
                        faceCount: 0,
                        materialCount: 0,
                        textureCount: 0,
                        animationCount: 0,
                        nodeCount: 0,
                        
                        // Informações técnicas
                        hasAnimations: false,
                        hasSkinning: false,
                        hasMorphTargets: false,
                        
                        // Análise de segurança
                        securityChecks: {
                            fileSizeOk: file.size <= 50 * 1024 * 1024, // Max 50MB
                            versionOk: version === 2, // GLB 2.0
                            structureOk: true
                        }
                    };

                    // Parse do JSON chunk para extrair mais informações
                    try {
                        // Chunk 0 é JSON
                        const jsonChunkLength = view.getUint32(12, true);
                        const jsonChunkType = view.getUint32(16, true);
                        
                        if (jsonChunkType === 0x4E4F534A) { // 'JSON' em ASCII
                            const jsonBytes = new Uint8Array(arrayBuffer, 20, jsonChunkLength);
                            const jsonString = new TextDecoder().decode(jsonBytes);
                            const gltfJson = JSON.parse(jsonString);

                            // Extrair estatísticas do JSON
                            if (gltfJson.meshes) {
                                metadata.meshCount = gltfJson.meshes.length;
                                
                                // Contar vértices e faces
                                let totalVertices = 0;
                                let totalFaces = 0;
                                
                                gltfJson.meshes.forEach(mesh => {
                                    if (mesh.primitives) {
                                        mesh.primitives.forEach(prim => {
                                            if (prim.attributes && prim.attributes.POSITION !== undefined) {
                                                const accessor = gltfJson.accessors[prim.attributes.POSITION];
                                                if (accessor) {
                                                    totalVertices += accessor.count;
                                                }
                                            }
                                            
                                            if (prim.indices !== undefined) {
                                                const accessor = gltfJson.accessors[prim.indices];
                                                if (accessor) {
                                                    totalFaces += accessor.count / 3;
                                                }
                                            }
                                        });
                                    }
                                });
                                
                                metadata.vertexCount = totalVertices;
                                metadata.faceCount = Math.floor(totalFaces);
                            }

                            if (gltfJson.materials) {
                                metadata.materialCount = gltfJson.materials.length;
                            }

                            if (gltfJson.textures) {
                                metadata.textureCount = gltfJson.textures.length;
                            }

                            if (gltfJson.images) {
                                metadata.imageCount = gltfJson.images.length;
                            }

                            if (gltfJson.animations) {
                                metadata.animationCount = gltfJson.animations.length;
                                metadata.hasAnimations = true;
                            }

                            if (gltfJson.nodes) {
                                metadata.nodeCount = gltfJson.nodes.length;
                            }

                            if (gltfJson.skins) {
                                metadata.hasSkinning = true;
                            }

                            // Verificar limites de segurança
                            metadata.securityChecks.vertexCountOk = metadata.vertexCount <= 100000;
                            metadata.securityChecks.faceCountOk = metadata.faceCount <= 100000;
                            metadata.securityChecks.textureCountOk = metadata.textureCount <= 20;
                        }
                    } catch (e) {
                        console.warn('Erro ao analisar JSON chunk:', e);
                        metadata.securityChecks.structureOk = false;
                    }

                    return metadata;
                },

                /**
                 * Verificar segurança do arquivo
                 */
                checkSecurity: function(metadata) {
                    const checks = metadata.securityChecks;
                    
                    if (!checks.fileSizeOk) {
                        return { safe: false, reason: 'Arquivo muito grande (máximo 50MB)' };
                    }
                    
                    if (!checks.versionOk) {
                        return { safe: false, reason: 'Versão GLB não suportada (apenas 2.0)' };
                    }
                    
                    if (!checks.structureOk) {
                        return { safe: false, reason: 'Estrutura do arquivo inválida' };
                    }
                    
                    if (!checks.vertexCountOk) {
                        return { safe: false, reason: 'Número de vértices excessivo (máximo 100k)' };
                    }
                    
                    if (!checks.faceCountOk) {
                        return { safe: false, reason: 'Número de faces excessivo (máximo 100k)' };
                    }
                    
                    if (!checks.textureCountOk) {
                        return { safe: false, reason: 'Número de texturas excessivo (máximo 20)' };
                    }
                    
                    return { safe: true };
                },

                /**
                 * Normalizar escala e centralizar modelo (CORRIGIDO)
                 */
                normalizeAndCenterModel: function() {
                    if (!this.model) return;

                    // Calcular bounding box
                    const box = new THREE.Box3().setFromObject(this.model);
                    const center = box.getCenter(new THREE.Vector3());
                    const size = box.getSize(new THREE.Vector3());

                    // Obter maior dimensão
                    const maxDim = Math.max(size.x, size.y, size.z);

                    // Normalizar escala para caber em uma caixa de tamanho 2
                    const targetSize = 2.0;
                    const scale = targetSize / maxDim;
                    
                    this.model.scale.set(scale, scale, scale);
                    this.modelScale = scale;

                    // Recalcular bounding box após escala
                    box.setFromObject(this.model);
                    const newCenter = box.getCenter(new THREE.Vector3());

                    // Centralizar modelo na origem
                    this.model.position.x -= newCenter.x;
                    this.model.position.y -= newCenter.y;
                    this.model.position.z -= newCenter.z;

                    // Ajustar câmera para visualização ideal
                    const newSize = box.getSize(new THREE.Vector3());
                    const newMaxDim = Math.max(newSize.x, newSize.y, newSize.z);
                    const fov = this.camera.fov * (Math.PI / 180);
                    let cameraZ = Math.abs(newMaxDim / 2 / Math.tan(fov / 2));
                    cameraZ *= 1.8; // Zoom out para melhor visualização

                    this.camera.position.set(cameraZ, cameraZ * 0.5, cameraZ);
                    this.camera.lookAt(0, 0, 0);
                    this.camera.updateProjectionMatrix();

                    // Atualizar controles
                    this.controls.target.set(0, 0, 0);
                    this.controls.update();

                    console.log('✅ Modelo normalizado:', {
                        escalaOriginal: maxDim.toFixed(2),
                        escalaAplicada: scale.toFixed(3),
                        dimensoesFinais: `${newSize.x.toFixed(2)} x ${newSize.y.toFixed(2)} x ${newSize.z.toFixed(2)}`
                    });
                },

                /**
                 * Ajustar escala do modelo
                 */
                setModelScale: function(scaleMultiplier) {
                    if (!this.model) return;
                    
                    const newScale = this.modelScale * scaleMultiplier;
                    this.model.scale.set(newScale, newScale, newScale);
                    
                    // Recentralizar
                    const box = new THREE.Box3().setFromObject(this.model);
                    const center = box.getCenter(new THREE.Vector3());
                    
                    this.model.position.x -= center.x;
                    this.model.position.y -= center.y;
                    this.model.position.z -= center.z;
                    
                    console.log('🔧 Escala ajustada para:', scaleMultiplier.toFixed(2) + 'x');
                },

                /**
                 * Aumentar escala do modelo
                 */
                scaleUp: function() {
                    if (!this.model) return;
                    const currentScale = this.model.scale.x;
                    const newScale = currentScale * 1.2;
                    this.model.scale.set(newScale, newScale, newScale);
                },

                /**
                 * Diminuir escala do modelo
                 */
                scaleDown: function() {
                    if (!this.model) return;
                    const currentScale = this.model.scale.x;
                    const newScale = currentScale * 0.8;
                    this.model.scale.set(newScale, newScale, newScale);
                },

                /**
                 * Resetar escala original
                 */
                resetScale: function() {
                    if (!this.model) return;
                    this.normalizeAndCenterModel();
                },

                /**
                 * Formatar bytes
                 */
                formatBytes: function(bytes, decimals = 2) {
                    if (bytes === 0) return '0 Bytes';

                    const k = 1024;
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];

                    const i = Math.floor(Math.log(bytes) / Math.log(k));

                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                },

                /**
                 * Loop de animação
                 */
                animate: function() {
                    requestAnimationFrame(() => this.animate());

                    // Atualizar controles
                    this.controls.update();

                    // Atualizar animações
                    if (this.mixer) {
                        const delta = this.clock.getDelta();
                        this.mixer.update(delta);
                    }

                    // Renderizar
                    this.renderer.render(this.scene, this.camera);
                },

                /**
                 * Alternar auto-rotação
                 */
                toggleAutoRotate: function() {
                    this.controls.autoRotate = !this.controls.autoRotate;
                    return this.controls.autoRotate;
                },

                /**
                 * Resetar câmera
                 */
                resetCamera: function() {
                    this.normalizeAndCenterModel();
                },

                /**
                 * Destruir viewer
                 */
                destroy: function() {
                    // Limpar geometrias e materiais
                    this.scene.traverse((object) => {
                        if (object.geometry) {
                            object.geometry.dispose();
                        }
                        if (object.material) {
                            if (Array.isArray(object.material)) {
                                object.material.forEach(material => material.dispose());
                            } else {
                                object.material.dispose();
                            }
                        }
                    });

                    // Destruir renderer
                    this.renderer.dispose();
                    container.removeChild(this.renderer.domElement);

                    // Remover do cache
                    delete window.GLBViewer.viewers[containerId];
                }
            };

            // Iniciar loop de animação
            viewer.animate();

            // Redimensionamento responsivo
            window.addEventListener('resize', () => {
                const width = container.clientWidth;
                const height = container.clientHeight;
                
                viewer.camera.aspect = width / height;
                viewer.camera.updateProjectionMatrix();
                viewer.renderer.setSize(width, height);
            });

            // Armazenar no cache
            this.viewers[containerId] = viewer;

            return viewer;
        },

        /**
         * Obter viewer existente
         */
        getViewer: function(containerId) {
            return this.viewers[containerId] || null;
        }
    };

    console.log('✅ GLBViewer inicializado');
})();