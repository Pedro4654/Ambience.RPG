<!-- VIEW 3: show.blade.php -->
<!-- resources/views/comunidade/show.blade.php -->

@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <!-- Cabeçalho -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex gap-4">
                <a href="{{ route('perfil.show', $post->autor->username) }}">
                    <img 
                        src="{{ $post->autor->avatar_url ?? asset('images/default-avatar.png') }}" 
                        alt="{{ $post->autor->username }}"
                        class="w-12 h-12 rounded-full object-cover"
                    >
                </a>

                <div>
                    <a href="{{ route('perfil.show', $post->autor->username) }}" class="font-bold text-gray-800 hover:text-blue-600">
                        {{ $post->autor->username }}
                    </a>
                    <p class="text-gray-500 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                    <p class="text-gray-600 text-xs mt-1">📁 {{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}</p>
                </div>
            </div>

            @if(Auth::check() && Auth::id() === $post->usuario_id)
                <div class="flex gap-2">
                    <a href="{{ route('comunidade.post.edit', $post->id) }}" class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 text-sm font-medium">
                        ✏️ Editar
                    </a>
                    <form action="{{ route('comunidade.post.destroy', $post->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 text-sm font-medium" onclick="return confirm('Tem certeza?')">
                            🗑️ Deletar
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Título e Conteúdo -->
        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $post->titulo }}</h1>
        <p class="text-gray-700 text-lg leading-relaxed whitespace-pre-wrap">{{ $post->conteudo }}</p>
    </div>

    <!-- Mídia -->
    @if($post->arquivos->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">📎 Arquivos</h2>
            <div class="space-y-4">
                @foreach($post->arquivos as $arquivo)
                    @if($arquivo->tipo === 'imagem')
                        <img src="{{ $arquivo->url }}" alt="{{ $arquivo->nome_arquivo }}" class="w-full rounded-lg max-h-96 object-cover">
                    @elseif($arquivo->tipo === 'video')
                        <video controls class="w-full rounded-lg max-h-96">
                            <source src="{{ $arquivo->url }}" type="{{ $arquivo->tipo_mime }}">
                            Seu navegador não suporta vídeo.
                        </video>
                    @elseif($arquivo->tipo === 'modelo_3d')
                        <div class="bg-gray-200 rounded-lg p-8 text-center">
                            <p class="text-gray-700 font-medium mb-2">📦 Modelo 3D</p>
                            <p class="text-gray-600 mb-4">{{ $arquivo->nome_arquivo }}</p>
                            <a href="{{ $arquivo->url }}" download class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                ⬇️ Baixar Modelo
                            </a>
                        </div>
                    @else
                        <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-between">
                            <div>
                                <p class="text-gray-700 font-medium">📄 {{ $arquivo->nome_arquivo }}</p>
                                <p class="text-gray-500 text-sm">{{ $arquivo->tamanho_formatado }}</p>
                            </div>
                            <a href="{{ $arquivo->url }}" download class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                ⬇️ Baixar
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <!-- Estatísticas -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-4 gap-4 text-center">
            <div>
                <p class="text-2xl font-bold text-red-500">{{ $post->total_curtidas }}</p>
                <p class="text-gray-600 text-sm">Curtidas</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-blue-500">{{ $post->total_comentarios }}</p>
                <p class="text-gray-600 text-sm">Comentários</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-500">{{ $post->total_salvamentos }}</p>
                <p class="text-gray-600 text-sm">Salvos</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-purple-500">{{ $post->visualizacoes }}</p>
                <p class="text-gray-600 text-sm">Visualizações</p>
            </div>
        </div>
    </div>

    <!-- Ações -->
    @if(Auth::check())
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex gap-2">
                @if($curtido)
                    <form action="{{ route('comunidade.descurtir', $post->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold transition-colors">
                            ❤️ Curtido
                        </button>
                    </form>
                @else
                    <form action="{{ route('comunidade.curtir') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <button type="submit" class="w-full py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 font-bold transition-colors">
                            🤍 Curtir
                        </button>
                    </form>
                @endif

                @if($salvo)
                    <form action="{{ route('comunidade.desalvar', $post->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition-colors">
                            💾 Salvo
                        </button>
                    </form>
                @else
                    <form action="{{ route('comunidade.salvar') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <button type="submit" class="w-full py-3 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 font-bold transition-colors">
                            ☐ Salvar
                        </button>
                    </form>
                @endif

                <a href="#comentarios" class="flex-1 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-bold text-center transition-colors">
                    💬 Comentar
                </a>
            </div>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6 text-center">
            <p class="text-gray-700 mb-4">Para interagir com postagens, você precisa estar logado.</p>
            <a href="{{ route('usuarios.login') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                🔐 Fazer Login
            </a>
        </div>
    @endif

    <!-- Comentários -->
    <div class="bg-white rounded-lg shadow-md p-6" id="comentarios">
        <h2 class="text-xl font-bold text-gray-800 mb-6">💬 Comentários</h2>

        @if(Auth::check())
            <!-- Formulário de Comentário -->
            <form action="{{ route('comunidade.comentar') }}" method="POST" class="mb-6 pb-6 border-b">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">

                <div class="flex gap-4">
                    <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" alt="Seu avatar" class="w-10 h-10 rounded-full object-cover">
                    <div class="flex-1">
                        <textarea 
                            name="conteudo" 
                            maxlength="1000"
                            placeholder="Deixe um comentário..."
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        ></textarea>
                        <button type="submit" class="mt-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            Comentar
                        </button>
                    </div>
                </div>
            </form>
        @endif

        <!-- Lista de Comentários -->
        @if($post->comentarios->count() > 0)
            <div class="space-y-4">
                @foreach($post->comentarios as $comentario)
                    @include('comunidade.components.comment-item', ['comentario' => $comentario])
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">Nenhum comentário ainda. Seja o primeiro!</p>
        @endif
    </div>
</div>
@endsection
