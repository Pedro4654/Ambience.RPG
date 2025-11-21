@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold gradient-text mb-2">✨ Criar Nova Postagem</h1>
        <p class="text-gray-600">Compartilhe seu conteúdo com a comunidade RPG</p>
    </div>

    <!-- Formulário -->
    <form action="{{ route('comunidade.store') }}" method="POST" enctype="multipart/form-data" id="form-criar-post" class="glassmorphism rounded-3xl shadow-2xl overflow-hidden">
        @csrf

        <div class="p-8 space-y-6">
            
            <!-- Tipo de Conteúdo (Seleção Visual) -->
            <div>
                <label class="block text-lg font-bold text-gray-800 mb-4">🎯 Tipo de Conteúdo</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="tipo_conteudo" value="texto" class="peer hidden" required {{ old('tipo_conteudo') == 'texto' ? 'checked' : '' }}>
                        <div class="p-4 bg-white border-2 border-gray-200 rounded-xl text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg group-hover:border-blue-300">
                            <div class="text-3xl mb-2">📝</div>
                            <p class="font-bold text-gray-800 text-sm">Texto</p>
                        </div>
                    </label>

                    <label class="relative cursor-pointer group">
                        <input type="radio" name="tipo_conteudo" value="imagem" class="peer hidden" {{ old('tipo_conteudo') == 'imagem' ? 'checked' : '' }}>
                        <div class="p-4 bg-white border-2 border-gray-200 rounded-xl text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg group-hover:border-blue-300">
                            <div class="text-3xl mb-2">🖼️</div>
                            <p class="font-bold text-gray-800 text-sm">Imagem</p>
                        </div>
                    </label>

                    <label class="relative cursor-pointer group">
                        <input type="radio" name="tipo_conteudo" value="video" class="peer hidden" {{ old('tipo_conteudo') == 'video' ? 'checked' : '' }}>
                        <div class="p-4 bg-white border-2 border-gray-200 rounded-xl text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg group-hover:border-blue-300">
                            <div class="text-3xl mb-2">🎬</div>
                            <p class="font-bold text-gray-800 text-sm">Vídeo</p>
                        </div>
                    </label>

                    <label class="relative cursor-pointer group">
                        <input type="radio" name="tipo_conteudo" value="modelo_3d" class="peer hidden" {{ old('tipo_conteudo') == 'modelo_3d' ? 'checked' : '' }}>
                        <div class="p-4 bg-white border-2 border-gray-200 rounded-xl text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg group-hover:border-blue-300">
                            <div class="text-3xl mb-2">🎮</div>
                            <p class="font-bold text-gray-800 text-sm">Modelo 3D</p>
                        </div>
                    </label>

                    <label class="relative cursor-pointer group">
                        <input type="radio" name="tipo_conteudo" value="ficha" class="peer hidden" {{ old('tipo_conteudo') == 'ficha' ? 'checked' : '' }}>
                        <div class="p-4 bg-white border-2 border-gray-200 rounded-xl text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg group-hover:border-blue-300">
                            <div class="text-3xl mb-2">📋</div>
                            <p class="font-bold text-gray-800 text-sm">Ficha RPG</p>
                        </div>
                    </label>

                    <label class="relative cursor-pointer group">
                        <input type="radio" name="tipo_conteudo" value="outros" class="peer hidden" {{ old('tipo_conteudo') == 'outros' ? 'checked' : '' }}>
                        <div class="p-4 bg-white border-2 border-gray-200 rounded-xl text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg group-hover:border-blue-300">
                            <div class="text-3xl mb-2">📦</div>
                            <p class="font-bold text-gray-800 text-sm">Outros</p>
                        </div>
                    </label>
                </div>
                @error('tipo_conteudo')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Título -->
            <div>
                <label for="titulo" class="block text-lg font-bold text-gray-800 mb-3">
                    ✏️ Título
                </label>
                <input 
                    type="text" 
                    id="titulo" 
                    name="titulo" 
                    required
                    maxlength="200"
                    placeholder="Dê um título chamativo para sua postagem..."
                    value="{{ old('titulo') }}"
                    class="w-full px-6 py-4 bg-white border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all text-lg @error('titulo') border-red-500 @enderror"
                >
                <small id="titulo-warning" style="display:none;color:#e0556b;font-size:0.9rem;margin-top:8px;">⚠️ Conteúdo inapropriado detectado</small>
                @error('titulo')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Conteúdo -->
            <div>
                <label for="conteudo" class="block text-lg font-bold text-gray-800 mb-3">
                    📄 Descrição
                </label>
                <textarea 
                    id="conteudo" 
                    name="conteudo" 
                    required
                    maxlength="5000"
                    rows="8"
                    placeholder="Descreva sua postagem, compartilhe detalhes, instruções, história..."
                    class="w-full px-6 py-4 bg-white border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all resize-none @error('conteudo') border-red-500 @enderror"
                >{{ old('conteudo') }}</textarea>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-sm text-gray-500">
                        <span id="contador-chars" class="font-bold">0</span>/5000 caracteres
                    </p>
                    <div class="flex gap-2">
                        <button type="button" onclick="document.getElementById('conteudo').value += '**texto em negrito**'" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-xs font-medium">
                            <strong>B</strong>
                        </button>
                        <button type="button" onclick="document.getElementById('conteudo').value += '_texto em itálico_'" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-xs font-medium">
                            <em>I</em>
                        </button>
                    </div>
                </div>
                <small id="conteudo-warning" style="display:none;color:#e0556b;font-size:0.9rem;margin-top:8px;">⚠️ Conteúdo inapropriado detectado</small>
                @error('conteudo')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload de Arquivos -->
            <div>
                <label class="block text-lg font-bold text-gray-800 mb-3">
                    📎 Arquivos (Opcional)
                </label>
                <div 
                    class="border-2 border-dashed border-gray-300 rounded-xl p-12 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all relative overflow-hidden group" 
                    id="drop-zone"
                >
                    <input 
                        type="file" 
                        id="arquivos" 
                        name="arquivos[]" 
                        multiple
                        accept="image/*,video/*,.glb,.gltf"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                    >
                    
                    <div class="pointer-events-none">
                        <div class="text-6xl mb-4 group-hover:scale-110 transition-transform">📁</div>
                        <p class="text-gray-700 font-bold text-lg mb-2">Clique ou arraste arquivos aqui</p>
                        <p class="text-gray-500 text-sm mb-1">Suportados: Imagens (JPG, PNG, GIF)</p>
                        <p class="text-gray-500 text-sm mb-1">Vídeos (MP4, WebM)</p>
                        <p class="text-gray-500 text-sm">Modelos 3D (.GLB, .GLTF)</p>
                        <p class="text-gray-400 text-xs mt-3">Máximo: 50MB por arquivo</p>
                    </div>
                </div>
                
                <!-- Container de alerta NSFW -->
                <div id="nsfw-alert-container" style="display:none;" class="mt-4"></div>
                
                <!-- Lista de Arquivos Selecionados -->
                <ul id="lista-arquivos" class="mt-4 space-y-2"></ul>
            </div>
        </div>

        <!-- Footer com Botões -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-8 py-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row gap-3">
                <button 
                    type="submit" 
                    id="submit-btn"
                    class="flex-1 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-2xl font-bold text-lg transition-all transform hover:scale-105"
                >
                    ✅ Publicar Postagem
                </button>
                <a 
                    href="{{ route('comunidade.feed') }}" 
                    class="flex-1 py-4 bg-white text-gray-700 rounded-xl hover:shadow-lg font-bold text-lg text-center transition-all border-2 border-gray-200"
                >
                    ❌ Cancelar
                </a>
            </div>
            
            <p class="text-center text-gray-500 text-sm mt-4">
                💡 Dica: Seja claro e detalhado para engajar melhor a comunidade!
            </p>
        </div>
    </form>
</div>

<!-- SCRIPTS DE MODERAÇÃO -->
<script src="{{ asset('js/moderation.js') }}" defer></script>
<!-- SCRIPTS NSFW -->
<script src="https://unpkg.com/nsfwjs@2.4.2/dist/nsfwjs.min.js"></script>
<script src="{{ asset('js/nsfw-detector.js') }}" defer></script>
<script src="{{ asset('js/nsfw-alert.js') }}" defer></script>

<script>
    // ========== VARIÁVEL GLOBAL PARA CONTROLE DE NSFW ==========
    let imagensComProblema = [];

    // ========== MODERAÇÃO LOCAL ==========
    window.addEventListener('DOMContentLoaded', async () => {
        // Inicializar sistema de moderação
        const state = await window.Moderation.init({
            csrfToken: '{{ csrf_token() }}',
            endpoint: '/moderate',
            debounceMs: 120,
        });

        console.log('[Post Moderation] Sistema inicializado:', state);

        // Função para aplicar warnings visuais
        function applyWarning(elSelector, res) {
            const el = document.querySelector(elSelector);
            const warn = document.querySelector(elSelector + '-warning');
            if (!el) return;
            
            if (res && res.inappropriate) {
                el.classList.add('border-red-500', 'bg-red-50');
                if (warn) warn.style.display = 'block';
            } else {
                el.classList.remove('border-red-500', 'bg-red-50');
                if (warn) warn.style.display = 'none';
            }
        }

        // Monitorar título
        window.Moderation.attachInput('#titulo', 'titulo', {
            onLocal: (res) => {
                console.log('[Título] Moderação local:', res);
                applyWarning('#titulo', res);
            },
            onServer: (srv) => {
                console.log('[Título] Moderação server:', srv);
                if (srv && srv.data && srv.data.inappropriate) {
                    applyWarning('#titulo', { inappropriate: true });
                }
            }
        });

        // Monitorar conteúdo
        window.Moderation.attachInput('#conteudo', 'conteudo', {
            onLocal: (res) => {
                console.log('[Conteúdo] Moderação local:', res);
                applyWarning('#conteudo', res);
            },
            onServer: (srv) => {
                console.log('[Conteúdo] Moderação server:', srv);
                if (srv && srv.data && srv.data.inappropriate) {
                    applyWarning('#conteudo', { inappropriate: true });
                }
            }
        });

        // Validação no submit
        const form = document.getElementById('form-criar-post');
        form.addEventListener('submit', function(e) {
            const tituloInapropriado = document.querySelector('#titulo.border-red-500');
            const conteudoInapropriado = document.querySelector('#conteudo.border-red-500');
            
            if (tituloInapropriado || conteudoInapropriado) {
                e.preventDefault();
                alert('⚠️ Conteúdo impróprio detectado. Corrija os campos marcados antes de continuar.');
                return false;
            }

            // ========== VALIDAÇÃO NSFW ==========
            if (imagensComProblema.length > 0) {
                e.preventDefault();
                alert('🚫 Imagens inapropriadas detectadas. Remova as imagens bloqueadas antes de continuar.');
                return false;
            }
        });
    });

    // ========== CONTADOR DE CARACTERES ==========
    const conteudoTextarea = document.getElementById('conteudo');
    const contadorChars = document.getElementById('contador-chars');
    
    conteudoTextarea.addEventListener('input', function() {
        contadorChars.textContent = this.value.length;
        
        if (this.value.length > 4500) {
            contadorChars.classList.add('text-red-600', 'font-bold');
        } else {
            contadorChars.classList.remove('text-red-600', 'font-bold');
        }
    });

    const initialContent = '{{ old('conteudo') }}';
    if (initialContent) {
        contadorChars.textContent = initialContent.length;
    }

    // ========== DRAG AND DROP ==========
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('arquivos');
    const listaArquivos = document.getElementById('lista-arquivos');

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-100');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-500', 'bg-blue-100');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-100');
        fileInput.files = e.dataTransfer.files;
        exibirArquivos();
    });

    fileInput.addEventListener('change', exibirArquivos);

    // ========== ANÁLISE NSFW DE IMAGENS ==========
    async function analisarImagens(files) {
        imagensComProblema = [];
        NSFWAlert.clear('nsfw-alert-container');

        const imagens = Array.from(files).filter(file => file.type.startsWith('image/'));
        
        if (imagens.length === 0) return;

        NSFWAlert.showLoading('nsfw-alert-container', '🔍 Analisando imagens...');

        try {
            for (const imagem of imagens) {
                console.log(`[NSFW] Analisando: ${imagem.name}`);
                
                try {
                    const resultado = await NSFWDetector.analyze(imagem);
                    
                    console.log(`[NSFW] Resultado para ${imagem.name}:`, resultado);

                    if (resultado.isBlocked) {
                        imagensComProblema.push({
                            nome: imagem.name,
                            resultado: resultado
                        });
                        
                        NSFWAlert.show('nsfw-alert-container', resultado, {
                            showDetails: true,
                            showClose: false,
                            blockedTitle: `🚫 Imagem Bloqueada: ${imagem.name}`,
                            warningTitle: `⚠️ Conteúdo Sugestivo: ${imagem.name}`
                        });

                        // Desabilitar botão de submit
                        document.getElementById('submit-btn').disabled = true;
                        document.getElementById('submit-btn').classList.add('opacity-50', 'cursor-not-allowed');

                    } else if (resultado.riskLevel === 'warning') {
                        NSFWAlert.show('nsfw-alert-container', resultado, {
                            showDetails: false,
                            showClose: true,
                            warningTitle: `⚠️ Atenção: ${imagem.name}`
                        });
                    }

                } catch (error) {
                    console.error(`[NSFW] Erro ao analisar ${imagem.name}:`, error);
                }
            }

            if (imagensComProblema.length === 0) {
                NSFWAlert.show('nsfw-alert-container', {
                    isBlocked: false,
                    riskLevel: 'safe',
                    reasons: [],
                    scores: {},
                    predictions: []
                }, {
                    showClose: true,
                    safeTitle: '✅ Imagens Aprovadas',
                    safeSubtitle: 'Todas as imagens foram analisadas e aprovadas'
                });

                // Reabilitar botão de submit
                document.getElementById('submit-btn').disabled = false;
                document.getElementById('submit-btn').classList.remove('opacity-50', 'cursor-not-allowed');
            }

        } catch (error) {
            console.error('[NSFW] Erro na análise:', error);
            NSFWAlert.showError('nsfw-alert-container', 'Erro ao analisar imagens. Tente novamente.');
        }
    }

    async function exibirArquivos() {
        listaArquivos.innerHTML = '';
        
        if (fileInput.files.length === 0) {
            NSFWAlert.clear('nsfw-alert-container');
            imagensComProblema = [];
            document.getElementById('submit-btn').disabled = false;
            document.getElementById('submit-btn').classList.remove('opacity-50', 'cursor-not-allowed');
            return;
        }
        
        for (let file of fileInput.files) {
            const tamanhoMB = (file.size / 1024 / 1024).toFixed(2);
            const icone = getIconePorTipo(file.type);
            
            let item = document.createElement('li');
            item.className = 'flex items-center justify-between bg-white p-4 rounded-xl border-2 border-gray-200 hover:border-blue-400 transition-all';
            item.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="text-2xl">${icone}</span>
                    <div>
                        <p class="font-bold text-gray-800">${file.name}</p>
                        <p class="text-sm text-gray-500">${tamanhoMB}MB</p>
                    </div>
                </div>
                <span class="text-green-500 text-2xl">✓</span>
            `;
            listaArquivos.appendChild(item);
        }

        // Analisar imagens
        await analisarImagens(fileInput.files);
    }

    function getIconePorTipo(tipo) {
        if (tipo.startsWith('image/')) return '🖼️';
        if (tipo.startsWith('video/')) return '🎬';
        if (tipo.includes('model') || tipo.includes('gltf')) return '🎮';
        return '📄';
    }

    // Validação de tipo de conteúdo
    document.getElementById('form-criar-post').addEventListener('submit', function(e) {
        const tipoSelecionado = document.querySelector('input[name="tipo_conteudo"]:checked');
        
        if (!tipoSelecionado) {
            e.preventDefault();
            alert('Por favor, selecione um tipo de conteúdo!');
            return false;
        }
    });
</script>
@endsection