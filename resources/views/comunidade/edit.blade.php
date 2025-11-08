<!-- VIEW 4: edit.blade.php -->
<!-- resources/views/comunidade/edit.blade.php -->

@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">✏️ Editar Postagem</h1>

        <form action="{{ route('comunidade.post.update', $post->id) }}" method="PUT" enctype="multipart/form-data">
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
@endsection
