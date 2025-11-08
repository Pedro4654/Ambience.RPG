@extends('layout.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50">
    
    <!-- Header com Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-48 relative">
        <button class="absolute top-4 left-4 text-white hover:text-gray-200" onclick="history.back()">
            ← Voltar
        </button>
    </div>

    <div class="container mx-auto px-4 max-w-5xl -mt-24 relative z-10">
        
        <!-- Card de Perfil -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 mb-8">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                
                <!-- Avatar e Info -->
                <div class="flex flex-col items-center md:items-start">
                    <img 
                        src="{{ $usuario->avatar_url ?? asset('images/default-avatar.png') }}" 
                        alt="{{ $usuario->username }}"
                        class="w-32 h-32 rounded-full object-cover ring-4 ring-blue-200 mb-4"
                    >
                    <h1 class="text-3xl font-bold text-gray-800">{{ $usuario->username }}</h1>
                </div>

                <!-- Bio e Stats -->
                <div class="flex-1">
                    @if($usuario->bio)
                        <p class="text-gray-700 mb-4">{{ $usuario->bio }}</p>
                    @endif
                    
                    @if($usuario->website)
                        <a href="{{ $usuario->website }}" target="_blank" class="text-blue-600 hover:underline mb-4 block">
                            🔗 {{ $usuario->website }}
                        </a>
                    @endif

                    <!-- Estatísticas -->
                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <div class="text-center">
                         
                            <p class="text-sm text-gray-500">Postagens: </p>
                               <p class="text-2xl font-bold text-blue-600">{{ $posts->total() }}</p>
                        </div>
                        <div class="text-center">
                            
                            <p class="text-sm text-gray-500">Seguidores: </p>
                            <p class="text-2xl font-bold text-blue-600">{{ $usuario->seguidores()->count() }}</p>
                        </div>
                        <div class="text-center">
                           
                            <p class="text-sm text-gray-500">Seguindo: </p>
                             <p class="text-2xl font-bold text-blue-600">{{ $usuario->seguindo()->count() }}</p>
                        </div>
                    </div>


                    <!-- Botões de Ação -->
                    <div class="flex gap-3 mt-6">
                        @auth
                            @if(Auth::id() === $usuario->id)
                                <!-- Botões para Meu Perfil -->
                                <a 
                                    href="{{ route('perfil.editar') }}" 
                                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg font-bold transition-all"
                                >
                                    ✏️ Editar Perfil
                                </a>
                                <a 
                                    href="{{ route('comunidade.feed') }}" 
                                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-bold transition-all"
                                >
                                    📰 Ver Feed
                                </a>
                            @else
                                <!-- Botões para Outro Usuário -->
                                @if($esta_seguindo)
                                    <form action="{{ route('perfil.deixar_de_seguir', $usuario->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-6 py-2 bg-red-500 text-white rounded-lg hover:shadow-lg font-bold transition-all">
                                            ✓ Deixar de Seguir
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('perfil.seguir', $usuario->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg font-bold transition-all">
                                            + Seguir
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('usuarios.login') }}" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg font-bold transition-all">
                                🔐 Fazer Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Abas: Postagens e Salvos -->
        @auth
            @if(Auth::id() === $usuario->id)
              
                <!-- Links -->
                <div class="mt-6 pt-6 border-t space-y-2">
                    <a href="{{ route('perfil.seguidores', $usuario->id) }}" class="block text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver Seguidores ({{ $num_seguidores }})
                    </a>
                    <a href="{{ route('perfil.seguindo', $usuario->id) }}" class="block text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver Seguindo ({{ $num_seguindo }})
                    </a>
                </div>
            </div>
            @endif
        @endauth

        <!-- Aba Postagens -->
        <div id="aba-posts" class="space-y-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">📰 Postagens</h2>
            
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                            
                            <!-- Preview -->
                            @if($post->arquivos->first())
                                @if($post->arquivos->first()->tipo === 'imagem')
                                    <div class="relative h-48 overflow-hidden bg-gray-900">
                                        <img 
                                            src="{{ $post->arquivos->first()->url }}" 
                                            alt="{{ $post->titulo }}"
                                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                        >
                                    </div>
                                @else
                                    <div class="h-48 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">
                                        <span class="text-white text-6xl">📦</span>
                                    </div>
                                @endif
                            @else
                                <div class="h-48 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">
                                    <span class="text-white text-6xl">📝</span>
                                </div>
                            @endif

                            <!-- Conteúdo -->
                            <div class="p-5">
                                <span class="inline-block px-3 py-1 text-xs font-bold bg-blue-100 text-blue-700 rounded-full mb-3">
                                    {{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}
                                </span>
                                
                                <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2">
                                    {{ $post->titulo }}
                                </h3>
                                
                                <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $post->conteudo }}</p>
                                
                                <!-- Footer -->
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                    <div class="flex gap-3 text-xs text-gray-500">
                                        <span>❤️ {{ $post->curtidas()->count() }}</span>
                                        <span>💬 {{ $post->comentarios()->count() }}</span>
                                    </div>
                                    <a 
                                        href="{{ route('comunidade.post.show', $post->slug) }}" 
                                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-xs font-bold rounded-lg hover:shadow-lg transition-all"
                                    >
                                        Ver →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-md p-12 text-center">
                    <p class="text-6xl mb-4">📝</p>
                    <p class="text-gray-500 text-xl font-bold mb-2">Nenhuma postagem ainda</p>
                    <p class="text-gray-400 mb-6">Comece a compartilhar conteúdo com a comunidade</p>
                    @auth
                        @if(Auth::id() === $usuario->id)
                            <a href="{{ route('comunidade.create') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg font-bold transition-all">
                                ✨ Criar Primeira Postagem
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <!-- Aba Salvos (Apenas para próprio perfil) -->
        @auth
            @if(Auth::id() === $usuario->id)
                <div id="aba-salvos" class="hidden space-y-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">💾 Posts Salvos</h2>
                    
                    @if($usuario->saved_posts()->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($usuario->saved_posts as $saved)
                                @php $post = $saved->post; @endphp
                                <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                                    
                                    <!-- Preview -->
                                    @if($post->arquivos->first())
                                        @if($post->arquivos->first()->tipo === 'imagem')
                                            <div class="relative h-48 overflow-hidden bg-gray-900">
                                                <img 
                                                    src="{{ $post->arquivos->first()->url }}" 
                                                    alt="{{ $post->titulo }}"
                                                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                                >
                                                <div class="absolute top-3 right-3 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                                    💾 Salvo
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-48 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 relative">
                                                <span class="text-white text-6xl">📦</span>
                                                <div class="absolute top-3 right-3 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                                    💾 Salvo
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="h-48 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 relative">
                                            <span class="text-white text-6xl">📝</span>
                                            <div class="absolute top-3 right-3 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                                💾 Salvo
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Conteúdo -->
                                    <div class="p-5">
                                        <span class="inline-block px-3 py-1 text-xs font-bold bg-purple-100 text-purple-700 rounded-full mb-3">
                                            {{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}
                                        </span>
                                        
                                        <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2">
                                            {{ $post->titulo }}
                                        </h3>
                                        
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $post->conteudo }}</p>
                                        
                                        <!-- Footer -->
                                        <div class="flex gap-2 pt-3 border-t border-gray-200">
                                            <a 
                                                href="{{ route('comunidade.post.show', $post->slug) }}" 
                                                class="flex-1 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-bold rounded-lg hover:shadow-lg transition-all text-center"
                                            >
                                                Ver Post
                                            </a>
                                            <form action="{{ route('comunidade.desalvar', $post->id) }}" method="POST" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit" 
                                                    class="w-full py-2 bg-red-100 text-red-600 text-sm font-bold rounded-lg hover:bg-red-200 transition-all"
                                                    onclick="return confirm('Remover dos salvos?')"
                                                >
                                                    🗑️ Remover
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-md p-12 text-center">
                            <p class="text-6xl mb-4">💾</p>
                            <p class="text-gray-500 text-xl font-bold mb-2">Nenhuma postagem salva</p>
                            <p class="text-gray-400 mb-6">Salve suas postagens favoritas para acessá-las rapidamente</p>
                            <a href="{{ route('comunidade.feed') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg font-bold transition-all">
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
function mostrarAbaPosts() {
    document.getElementById('aba-posts').classList.remove('hidden');
    document.getElementById('aba-salvos').classList.add('hidden');
}

function mostrarAbaSalvos() {
    document.getElementById('aba-posts').classList.add('hidden');
    document.getElementById('aba-salvos').classList.remove('hidden');
}
</script>
@endsection