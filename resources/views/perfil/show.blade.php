@extends('layout.app')

@section('content')
<div class="min-h-screen">
    
    <!-- Banner do Perfil -->
    <div class="relative h-64 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 overflow-hidden">
        <!-- Padrão decorativo -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
        </div>
        
        <!-- Botão Voltar -->
        <button onclick="history.back()" class="absolute top-6 left-6 px-4 py-2 bg-white/20 backdrop-blur-md text-white rounded-lg hover:bg-white/30 transition-all font-medium">
            ← Voltar
        </button>
    </div>

    <div class="container mx-auto px-4 max-w-5xl -mt-32 relative z-10">
        
        <!-- Card do Perfil -->
        <div class="glassmorphism rounded-3xl shadow-2xl overflow-hidden mb-8">
            <div class="p-8">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    
                    <!-- Avatar e Info Básica -->
                    <div class="flex flex-col items-center md:items-start">
                        <div class="relative group">
                            <img 
                                src="{{ $usuario->avatar_url ?? asset('images/default-avatar.png') }}" 
                                alt="{{ $usuario->username }}"
                                class="w-32 h-32 rounded-full object-cover ring-4 ring-white shadow-2xl mb-4 group-hover:scale-105 transition-transform"
                            >
                            @if(Auth::check() && Auth::id() === $usuario->id)
                                <button class="absolute bottom-4 right-0 w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full shadow-lg hover:scale-110 transition-transform">
                                    📷
                                </button>
                            @endif
                        </div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $usuario->username }}</h1>
                        
                        <!-- Badge de Privacidade -->
                        @if($usuario->privacidade_perfil === 'privado')
                            <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-bold">
                                🔒 Perfil Privado
                            </span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                🌍 Perfil Público
                            </span>
                        @endif
                    </div>

                    <!-- Bio e Informações -->
                    <div class="flex-1">
                        @if($usuario->bio)
                            <p class="text-gray-700 text-lg leading-relaxed mb-4">{{ $usuario->bio }}</p>
                        @else
                            <p class="text-gray-400 italic mb-4">Sem bio ainda...</p>
                        @endif
                        
                        @if($usuario->website)
                            <a href="{{ $usuario->website }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium mb-6">
                                🔗 {{ $usuario->website }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        @endif

                        <!-- Estatísticas -->
                        <div class="grid grid-cols-3 gap-6 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl mb-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600 mb-1">{{ $posts->total() }}</div>
                                <p class="text-sm text-gray-600 font-medium">📝 Posts</p>
                            </div>
                            <a href="{{ route('perfil.seguidores', $usuario->id) }}" class="text-center hover:scale-105 transition-transform">
                                <div class="text-3xl font-bold text-purple-600 mb-1">{{ $usuario->seguidores()->count() }}</div>
                                <p class="text-sm text-gray-600 font-medium">👥 Seguidores</p>
                            </a>
                            <a href="{{ route('perfil.seguindo', $usuario->id) }}" class="text-center hover:scale-105 transition-transform">
                                <div class="text-3xl font-bold text-pink-600 mb-1">{{ $usuario->seguindo()->count() }}</div>
                                <p class="text-sm text-gray-600 font-medium">💫 Seguindo</p>
                            </a>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex flex-wrap gap-3">
                            @auth
                                @if(Auth::id() === $usuario->id)
                                    <!-- Botões para o Próprio Perfil -->
                                    <a href="{{ route('perfil.editar') }}" class="flex-1 min-w-[200px] py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-2xl font-bold text-center transition-all">
                                        ✏️ Editar Perfil
                                    </a>
                                    <a href="{{ route('comunidade.feed') }}" class="flex-1 min-w-[200px] py-3 bg-white text-gray-700 rounded-xl hover:shadow-lg font-bold text-center transition-all border-2 border-gray-200">
                                        📰 Ver Feed
                                    </a>
                                @else
                                    <!-- Botões para Outro Perfil -->
                                    @if($esta_seguindo)
                                        <form action="{{ route('perfil.deixar_de_seguir', $usuario->id) }}" method="POST" class="flex-1 min-w-[200px]">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-bold transition-all">
                                                ✓ Seguindo
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('perfil.seguir', $usuario->id) }}" method="POST" class="flex-1 min-w-[200px]">
                                            @csrf
                                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-2xl font-bold transition-all">
                                                + Seguir
                                            </button>
                                        </form>
                                    @endif
                                    <button class="px-6 py-3 bg-white text-gray-700 rounded-xl hover:shadow-lg font-bold transition-all border-2 border-gray-200">
                                        💬 Mensagem
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('usuarios.login') }}" class="flex-1 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-2xl font-bold text-center transition-all">
                                    🔑 Fazer Login para Seguir
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs de Conteúdo -->
        <div class="glassmorphism rounded-2xl shadow-lg mb-8 overflow-hidden">
            <div class="flex border-b border-gray-200">
                <button onclick="mostrarAba('posts')" id="tab-posts" class="flex-1 py-4 px-6 font-bold text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all border-b-2 border-blue-600">
                    📝 Postagens
                </button>
                @auth
                    @if(Auth::id() === $usuario->id)
                        <button onclick="mostrarAba('salvos')" id="tab-salvos" class="flex-1 py-4 px-6 font-bold text-gray-600 hover:text-purple-600 hover:bg-purple-50 transition-all">
                            💾 Salvos
                        </button>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Conteúdo da Aba Posts -->
        <div id="aba-posts">
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($posts as $post)
                        <a href="{{ route('comunidade.post.show', $post->slug) }}" class="group glassmorphism rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2">
                            
                            <!-- Preview -->
                            <div class="relative h-48 overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600">
                                @if($post->arquivos->first())
                                    @if($post->arquivos->first()->tipo === 'imagem')
                                        <img 
                                            src="{{ $post->arquivos->first()->url }}" 
                                            alt="{{ $post->titulo }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                        >
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-white text-6xl">
                                            📦
                                        </div>
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white text-6xl">
                                        📝
                                    </div>
                                @endif
                                
                                <!-- Badge do Tipo -->
                                <span class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-800 rounded-full text-xs font-bold">
                                    {{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}
                                </span>
                            </div>

                            <!-- Info -->
                            <div class="p-5">
                                <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    {{ $post->titulo }}
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $post->conteudo }}</p>
                                
                                <!-- Stats -->
                                <div class="flex gap-4 text-sm text-gray-500">
                                    <span>❤️ {{ $post->curtidas()->count() }}</span>
                                    <span>💬 {{ $post->comentarios()->count() }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="glassmorphism rounded-2xl p-16 text-center shadow-lg">
                    <div class="text-8xl mb-4">📝</div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Nenhuma postagem ainda</h3>
                    <p class="text-gray-600 mb-6">Compartilhe conteúdo com a comunidade</p>
                    @auth
                        @if(Auth::id() === $usuario->id)
                            <a href="{{ route('comunidade.create') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-2xl font-bold transition-all">
                                ✨ Criar Primeira Postagem
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <!-- Conteúdo da Aba Salvos -->
        @auth
            @if(Auth::id() === $usuario->id)
                <div id="aba-salvos" class="hidden">
                    @if($usuario->saved_posts()->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                            @foreach($usuario->saved_posts as $saved)
                                @php $post = $saved->post; @endphp
                                <div class="group glassmorphism rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2">
                                    
                                    <!-- Preview com Badge Salvo -->
                                    <div class="relative h-48 overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600">
                                        @if($post->arquivos->first() && $post->arquivos->first()->tipo === 'imagem')
                                            <img 
                                                src="{{ $post->arquivos->first()->url }}" 
                                                alt="{{ $post->titulo }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-white text-6xl">
                                                📝
                                            </div>
                                        @endif
                                        
                                        <span class="absolute top-3 right-3 px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-bold shadow-lg">
                                            💾 Salvo
                                        </span>
                                    </div>

                                    <!-- Info -->
                                    <div class="p-5">
                                        <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2">
                                            {{ $post->titulo }}
                                        </h3>
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $post->conteudo }}</p>
                                        
                                        <!-- Botões -->
                                        <div class="flex gap-2">
                                            <a href="{{ route('comunidade.post.show', $post->slug) }}" class="flex-1 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-bold rounded-lg hover:shadow-lg transition-all text-center">
                                                Ver Post
                                            </a>
                                            <form action="{{ route('comunidade.desalvar', $post->id) }}" method="POST" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full py-2 bg-red-100 text-red-600 text-sm font-bold rounded-lg hover:bg-red-200 transition-all" onclick="return confirm('Remover dos salvos?')">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="glassmorphism rounded-2xl p-16 text-center shadow-lg">
                            <div class="text-8xl mb-4">💾</div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Nenhuma postagem salva</h3>
                            <p class="text-gray-600 mb-6">Salve suas postagens favoritas para acessá-las rapidamente</p>
                            <a href="{{ route('comunidade.feed') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-2xl font-bold transition-all">
                                Explorar Feed →
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        @endauth
    </div>
</div>

<script>
function mostrarAba(aba) {
    // Esconde todas as abas
    document.getElementById('aba-posts').classList.add('hidden');
    const abaSalvos = document.getElementById('aba-salvos');
    if (abaSalvos) abaSalvos.classList.add('hidden');
    
    // Remove destaque de todos os tabs
    document.getElementById('tab-posts').classList.remove('border-b-2', 'border-blue-600', 'text-blue-600');
    const tabSalvos = document.getElementById('tab-salvos');
    if (tabSalvos) tabSalvos.classList.remove('border-b-2', 'border-purple-600', 'text-purple-600');
    
    // Mostra aba selecionada
    if (aba === 'posts') {
        document.getElementById('aba-posts').classList.remove('hidden');
        document.getElementById('tab-posts').classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
    } else if (aba === 'salvos' && abaSalvos) {
        abaSalvos.classList.remove('hidden');
        tabSalvos.classList.add('border-b-2', 'border-purple-600', 'text-purple-600');
    }
}

</script>
@endsection