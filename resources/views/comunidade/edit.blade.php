@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">✏️ Editar Postagem</h1>

        <form action="{{ route('comunidade.post.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="form-editar-post">
            @csrf
            @method('PUT')

            <!-- Título -->
            <div class="mb-6">
                <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-2">Título</label>
                <input 
                    type="text" 
                    id="titulo" 
                    name="titulo" 
                    required
                    maxlength="200"
                    value="{{ old('titulo', $post->titulo) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titulo') border-red-500 @enderror"
                >
                <small id="titulo-warning" style="display:none;color:#e0556b;font-size:0.9rem;margin-top:8px;">⚠️ Conteúdo inapropriado detectado</small>
                @error('titulo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Conteúdo -->
            <div class="mb-6">
                <label for="conteudo" class="block text-sm font-semibold text-gray-700 mb-2">Conteúdo</label>
                <textarea 
                    id="conteudo" 
                    name="conteudo" 
                    required
                    maxlength="5000"
                    rows="8"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('conteudo') border-red-500 @enderror"
                >{{ old('conteudo', $post->conteudo) }}</textarea>
                <p class="text-gray-500 text-sm mt-1">{{ strlen($post->conteudo) }}/5000 caracteres</p>
                <small id="conteudo-warning" style="display:none;color:#e0556b;font-size:0.9rem;margin-top:8px;">⚠️ Conteúdo inapropriado detectado</small>
                @error('conteudo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo de Conteúdo -->
            <div class="mb-6">
                <label for="tipo_conteudo" class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Conteúdo</label>
                <select 
                    id="tipo_conteudo" 
                    name="tipo_conteudo" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="texto" {{ $post->tipo_conteudo == 'texto' ? 'selected' : '' }}>📝 Texto</option>
                    <option value="imagem" {{ $post->tipo_conteudo == 'imagem' ? 'selected' : '' }}>🖼️ Imagem</option>
                    <option value="video" {{ $post->tipo_conteudo == 'video' ? 'selected' : '' }}>🎬 Vídeo</option>
                    <option value="modelo_3d" {{ $post->tipo_conteudo == 'modelo_3d' ? 'selected' : '' }}>🎮 Modelo 3D</option>
                    <option value="ficha" {{ $post->tipo_conteudo == 'ficha' ? 'selected' : '' }}>📋 Ficha</option>
                    <option value="outros" {{ $post->tipo_conteudo == 'outros' ? 'selected' : '' }}>📦 Outros</option>
                </select>
            </div>

            <!-- Botões -->
            <div class="flex gap-4">
                <button 
                    type="submit" 
                    id="submit-btn"
                    class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-colors"
                >
                    ✅ Salvar Alterações
                </button>
                <a 
                    href="{{ route('comunidade.post.show', $post->slug) }}" 
                    class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-semibold text-center transition-colors"
                >
                    ❌ Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPTS DE MODERAÇÃO -->
<script src="{{ asset('js/moderation.js') }}" defer></script>

<script>
window.addEventListener('DOMContentLoaded', async () => {
    // Inicializar moderação
    const state = await window.Moderation.init({
        csrfToken: '{{ csrf_token() }}',
        endpoint: '/moderate',
        debounceMs: 120,
    });

    console.log('[Edit Post Moderation] Sistema inicializado:', state);

    // Função para aplicar warnings
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
            console.log('[Título Edit] Moderação local:', res);
            applyWarning('#titulo', res);
        },
        onServer: (srv) => {
            console.log('[Título Edit] Moderação server:', srv);
            if (srv && srv.data && srv.data.inappropriate) {
                applyWarning('#titulo', { inappropriate: true });
            }
        }
    });

    // Monitorar conteúdo
    window.Moderation.attachInput('#conteudo', 'conteudo', {
        onLocal: (res) => {
            console.log('[Conteúdo Edit] Moderação local:', res);
            applyWarning('#conteudo', res);
        },
        onServer: (srv) => {
            console.log('[Conteúdo Edit] Moderação server:', srv);
            if (srv && srv.data && srv.data.inappropriate) {
                applyWarning('#conteudo', { inappropriate: true });
            }
        }
    });

    // Validação no submit
    const form = document.getElementById('form-editar-post');
    form.addEventListener('submit', function(e) {
        const tituloInapropriado = document.querySelector('#titulo.border-red-500');
        const conteudoInapropriado = document.querySelector('#conteudo.border-red-500');
        
        if (tituloInapropriado || conteudoInapropriado) {
            e.preventDefault();
            alert('⚠️ Conteúdo impróprio detectado. Corrija os campos marcados antes de continuar.');
            return false;
        }
    });
});
</script>
@endsection