/**
 * NSFW Image Detector - Módulo Reutilizável
 * Arquivo: public/js/nsfw-detector.js
 * 
 * Como usar:
 * 1. Incluir o script na página: <script src="{{ asset('js/nsfw-detector.js') }}"></script>
 * 2. Chamar: NSFWDetector.analyze(file).then(result => { ... })
 */

const NSFWDetector = (function() {
    // === CONFIGURAÇÕES ===
    const CONFIG = {
        modelPath: '/models/nsfwjs-master/models/mobilenet_v2/',
        thresholds: {
            porn: 0.60,      // 60%
            hentai: 0.60,    // 60%
            sexy: 0.80       // 80%
        },
        maxFileSize: 10 * 1024 * 1024, // 10MB
        allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
    };

    // === ESTADO ===
    let nsfwModel = null;
    let isModelLoaded = false;
    let modelLoadPromise = null;

    /**
     * Carregar o modelo NSFW.js
     */
    async function loadModel() {
        if (isModelLoaded && nsfwModel) {
            return nsfwModel;
        }

        if (modelLoadPromise) {
            return modelLoadPromise;
        }

        modelLoadPromise = (async () => {
            try {
                console.log('🔦 Carregando modelo NSFW.js...');
                
                if (typeof nsfwjs === 'undefined') {
                    throw new Error('NSFW.js não encontrado. Verifique se o script foi carregado.');
                }

                nsfwModel = await nsfwjs.load(CONFIG.modelPath);
                isModelLoaded = true;
                
                console.log('✅ Modelo NSFW carregado com sucesso');
                return nsfwModel;
            } catch (error) {
                console.error('❌ Erro ao carregar modelo NSFW:', error);
                modelLoadPromise = null;
                throw error;
            }
        })();

        return modelLoadPromise;
    }

    /**
     * Validar arquivo
     */
    function validateFile(file) {
        if (!file) {
            return { valid: false, error: 'Nenhum arquivo fornecido' };
        }

        if (!CONFIG.allowedTypes.includes(file.type)) {
            return { 
                valid: false, 
                error: 'Tipo de arquivo inválido. Use: JPG, PNG, GIF, WEBP' 
            };
        }

        if (file.size > CONFIG.maxFileSize) {
            return { 
                valid: false, 
                error: `Arquivo muito grande. Máximo: ${(CONFIG.maxFileSize / 1024 / 1024)}MB` 
            };
        }

        return { valid: true };
    }

    /**
     * Criar elemento de imagem temporário
     */
    function createImageElement(file) {
        return new Promise((resolve, reject) => {
            const img = document.createElement('img');
            const url = URL.createObjectURL(file);
            
            img.onload = () => {
                URL.revokeObjectURL(url);
                resolve(img);
            };
            
            img.onerror = () => {
                URL.revokeObjectURL(url);
                reject(new Error('Erro ao carregar imagem'));
            };
            
            img.src = url;
        });
    }

    /**
     * Processar resultados da análise
     */
    function processResults(predictions) {
        const scores = {};
        predictions.forEach(pred => {
            scores[pred.className] = pred.probability;
        });

        const reasons = [];
        let isBlocked = false;
        let riskLevel = 'safe';

        // Verificar conteúdo pornográfico
        if (scores.Porn && scores.Porn >= CONFIG.thresholds.porn) {
            isBlocked = true;
            riskLevel = 'unsafe';
            reasons.push(`Conteúdo pornográfico detectado (${(scores.Porn * 100).toFixed(1)}%)`);
        }

        // Verificar conteúdo hentai
        if (scores.Hentai && scores.Hentai >= CONFIG.thresholds.hentai) {
            isBlocked = true;
            riskLevel = 'unsafe';
            reasons.push(`Conteúdo hentai detectado (${(scores.Hentai * 100).toFixed(1)}%)`);
        }

        // Verificar conteúdo sensual
        if (scores.Sexy && scores.Sexy >= CONFIG.thresholds.sexy) {
            if (!isBlocked) {
                riskLevel = 'warning';
            }
            reasons.push(`Conteúdo sensual detectado (${(scores.Sexy * 100).toFixed(1)}%)`);
        }

        return {
            isBlocked,
            riskLevel,
            reasons,
            scores,
            predictions,
            maxScore: Math.max(...predictions.map(p => p.probability))
        };
    }

    /**
     * Analisar imagem
     * @param {File} file - Arquivo de imagem
     * @returns {Promise<Object>} Resultado da análise
     */
    async function analyze(file) {
        // Validar arquivo
        const validation = validateFile(file);
        if (!validation.valid) {
            throw new Error(validation.error);
        }

        try {
            // Carregar modelo se necessário
            const model = await loadModel();

            // Criar elemento de imagem
            const img = await createImageElement(file);

            // Classificar imagem
            const startTime = Date.now();
            const predictions = await model.classify(img);
            const analysisTime = Date.now() - startTime;

            // Processar resultados
            const result = processResults(predictions);
            result.analysisTime = analysisTime;
            result.fileName = file.name;
            result.fileSize = file.size;

            console.log('📊 Análise NSFW concluída:', result);
            
            return result;
        } catch (error) {
            console.error('❌ Erro na análise NSFW:', error);
            throw error;
        }
    }

    /**
     * Verificar se modelo está carregado
     */
    function isLoaded() {
        return isModelLoaded;
    }

    /**
     * Obter configurações
     */
    function getConfig() {
        return { ...CONFIG };
    }

    // === API PÚBLICA ===
    return {
        analyze,
        isLoaded,
        getConfig,
        loadModel
    };
})();

// Expor globalmente
window.NSFWDetector = NSFWDetector;