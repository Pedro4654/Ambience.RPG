<!-- ============================================================ -->
<!-- VIEW 2: create.blade.php -->
<!-- resources/views/comunidade/create.blade.php -->

@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">✨ Criar Nova Postagem</h1>

        <form action="{{ route('comunidade.store') }}" method="POST" enctype="multipart/form-data" id="form-criar-post">
            @csrf

            <!-- Título -->
            <div class="mb-6">
                <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-2">Título</label>
                <input 
                    type="text" 
                    id="titulo" 
                    name="titulo" 
                    required
                    maxlength="200"
                    placeholder="Título da sua postagem"
                    value="{{ old('titulo') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titulo') border-red-500 @enderror"
                >
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
                    placeholder="Descreva sua postagem aqui..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('conteudo') border-red-500 @enderror"
                >{{ old('conteudo') }}</textarea>
                <p class="text-gray-500 text-sm mt-1"><span id="contador-chars">0</span>/5000 caracteres</p>
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
                    <option value="">-- Selecione o tipo --</option>
                    <option value="texto" {{ old('tipo_conteudo') == 'texto' ? 'selected' : '' }}>📝 Texto</option>
                    <option value="imagem" {{ old('tipo_conteudo') == 'imagem' ? 'selected' : '' }}>🖼️ Imagem</option>
                    <option value="video" {{ old('tipo_conteudo') == 'video' ? 'selected' : '' }}>🎬 Vídeo</option>
                    <option value="modelo_3d" {{ old('tipo_conteudo') == 'modelo_3d' ? 'selected' : '' }}>🎮 Modelo 3D (.GLB)</option>
                    <option value="ficha" {{ old('tipo_conteudo') == 'ficha' ? 'selected' : '' }}>📋 Ficha de Personagem</option>
                    <option value="outros" {{ old('tipo_conteudo') == 'outros' ? 'selected' : '' }}>📦 Outros</option>
                </select>
                @error('tipo_conteudo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload de Arquivos -->
            <div class="mb-6">
                <label for="arquivos" class="block text-sm font-semibold text-gray-700 mb-2">📎 Arquivos (Opcional)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-colors" id="drop-zone">
                    <input 
                        type="file" 
                        id="arquivos" 
                        name="arquivos[]" 
                        multiple
                        accept="image/*,video/*,.glb,.gltf"
                        class="hidden"
                    >
                    <p class="text-gray-600 font-medium">Clique ou arraste arquivos aqui</p>
                    <p class="text-gray-400 text-sm">Suportados: Imagens, Vídeos, Modelos 3D (.GLB)</p>
                    <p class="text-gray-400 text-sm">Máximo: 50MB por arquivo</p>
                    <ul id="lista-arquivos" class="mt-4 space-y-2"></ul>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-colors"
                >
                    ✅ Publicar Postagem
                </button>
                <a 
                    href="{{ route('comunidade.feed') }}" 
                    class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-semibold text-center transition-colors"
                >
                    ❌ Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Contador de caracteres
    document.getElementById('conteudo').addEventListener('input', function() {
        document.getElementById('contador-chars').textContent = this.value.length;
        const initialContent = '{{ old('conteudo') }}';
        if (initialContent) document.getElementById('contador-chars').textContent = initialContent.length;
    });

    // Drag and drop de arquivos
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('arquivos');
    const listaArquivos = document.getElementById('lista-arquivos');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        fileInput.files = e.dataTransfer.files;
        exibirArquivos();
    });

    fileInput.addEventListener('change', exibirArquivos);

    function exibirArquivos() {
        listaArquivos.innerHTML = '';
        for (let file of fileInput.files) {
            let item = document.createElement('li');
            item.className = 'flex justify-between items-center bg-gray-100 p-3 rounded';
            item.innerHTML = `
                <span class="text-sm text-gray-700">${file.name} (${(file.size / 1024 / 1024).toFixed(2)}MB)</span>
                <span class="text-green-600 font-bold">✓</span>
            `;
            listaArquivos.appendChild(item);
        }
    }
</script>
@endsection
