<!-- VIEW 7: components/post-card.blade.php -->
<!-- resources/views/comunidade/components/post-card.blade.php -->

<article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
    <!-- Cabeçalho do Post -->
    <div class="p-4 border-b border-gray-200 flex items-start justify-between">
        <div class="flex gap-3 flex-1">
            <!-- Avatar do Autor -->
            <a href="{{ route('perfil.show', $post->autor->username) }}" class="flex-shrink-0">
                <img 
                    src="{{ $post->autor->avatar_url ?? asset('images/default-avatar.png') }}" 
                    alt="{{ $post->autor->username }}"
                    class="w-10 h-10 rounded-full object-cover"
                >
            </a>
            
            <!-- Informações do Autor -->
            <div class="flex-1 min-w-0">
                <a href="{{ route('perfil.show', $post->autor->username) }}" class="font-semibold text-gray-800 hover:text-blue-600">
                    {{ $post->autor->username }}
                </a>
                <p class="text-gray-500 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                <p class="text-gray-600 text-xs">{{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}</p>
            </div>
        </div>

        <!-- Menu (se for autor) -->
        @if(Auth::check() && Auth::id() === $post->usuario_id)
            <div class="relative group">
                <button class="text-gray-500 hover:text-gray-700 font-bold">⋮</button>
                <div class="absolute right-0 bg-white shadow-lg rounded-lg p-2 hidden group-hover:block z-10 min-w-max">
                    <a href="{{ route('comunidade.post.edit', $post->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                        ✏️ Editar
                    </a>
                    <form action="{{ route('comunidade.post.destroy', $post->id) }}" method="POST" class="inline w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 rounded" onclick="return confirm('Tem certeza?')">
                            🗑️ Deletar
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Conteúdo -->
    <div class="p-4">
        <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $post->titulo }}</h3>
        <p class="text-gray-700 line-clamp-3 mb-4">{{ $post->conteudo }}</p>

        <!-- Arquivos/Mídia -->
        @if($post->arquivos->count() > 0)
            <div class="mb-4 space-y-2">
                @foreach($post->arquivos as $arquivo)
                    @if($arquivo->tipo === 'imagem')
                        <img src="{{ $arquivo->url }}" alt="{{ $arquivo->nome_arquivo }}" class="w-full rounded-lg max-h-96 object-cover">
                    @elseif($arquivo->tipo === 'video')
                        <video controls class="w-full rounded-lg max-h-96">
                            <source src="{{ $arquivo->url }}" type="{{ $arquivo->tipo_mime }}">
                        </video>
                    @elseif($arquivo->tipo === 'modelo_3d')
                        <div class="bg-gray-200 rounded-lg p-4 text-center">
                            <p class="text-gray-600">📦 Modelo 3D: {{ $arquivo->nome_arquivo }}</p>
                            <a href="{{ $arquivo->url }}" download class="text-blue-600 hover:text-blue-800 text-sm">Baixar</a>
                        </div>
                    @else
                        <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-between">
                            <span class="text-gray-700">📄 {{ $arquivo->nome_arquivo }}</span>
                            <a href="{{ $arquivo->url }}" download class="text-blue-600 hover:text-blue-800">Baixar</a>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <!-- Link para visualizar completo -->
        <a href="{{ route('comunidade.post.show', $post->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Ver postagem completa →
        </a>
    </div>

    <!-- Rodapé - Estatísticas e Ações -->
    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
        <!-- Estatísticas -->
        <div class="flex gap-4 text-gray-600 text-sm mb-3">
            <span>❤️ {{ $post->curtidas()->count() }} curtidas</span>
            <span>💬 {{ $post->comentarios()->count() }} comentários</span>
        </div>

        <!-- Botões de Ação -->
        <div class="flex gap-2">
            @if(Auth::check())
                {{-- Curtir --}}
                @if($post->curtido_por_usuario(Auth::id()))
                    <form action="{{ route('comunidade.descurtir', $post->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium text-sm">
                            ❤️ Curtido
                        </button>
                    </form>
                @else
                    <form action="{{ route('comunidade.curtir') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <button type="submit" class="w-full py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium text-sm">
                            🤍 Curtir
                        </button>
                    </form>
                @endif

                {{-- Salvar --}}
                @if($post->salvo_por_usuario(Auth::id()))
                    <form action="{{ route('comunidade.desalvar', $post->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 text-blue-600 hover:bg-blue-50 rounded-lg font-medium text-sm">
                            💾 Salvo
                        </button>
                    </form>
                @else
                    <form action="{{ route('comunidade.salvar') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <button type="submit" class="w-full py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium text-sm">
                            ☐ Salvar
                        </button>
                    </form>
                @endif

                {{-- Comentar --}}
                <a href="{{ route('comunidade.post.show', $post->slug) }}" class="flex-1 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium text-center text-sm">
                    💬 Comentar
                </a>
            @else
                <a href="{{ route('usuarios.login') }}" class="w-full py-2 text-center text-gray-600 hover:bg-gray-100 rounded-lg font-medium text-sm">
                    🔐 Faça login
                </a>
            @endif
        </div>
    </div>
</article>