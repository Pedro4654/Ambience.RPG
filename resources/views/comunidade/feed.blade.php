<!-- VIEW 1: feed.blade.php -->
<!-- resources/views/comunidade/feed.blade.php -->

@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <!-- Sidebar Esquerdo -->
        <aside class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h3 class="text-xl font-bold mb-4 text-gray-800">🌟 Comunidade</h3>
                <nav class="space-y-2">
                    <a href="{{ route('comunidade.feed') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-100 text-gray-700 font-medium">
                        📰 Feed
                    </a>
                    @auth
                        <a href="{{ route('comunidade.create') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-100 text-gray-700 font-medium">
                            ➕ Criar Postagem
                        </a>
                        <a href="{{ route('perfil.meu') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-100 text-gray-700 font-medium">
                            👤 Meu Perfil
                        </a>
                    @endauth
                </nav>
            </div>
        </aside>

        <!-- Feed Central -->
        <main class="md:col-span-3">
            <!-- Barra de Busca -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <form action="{{ route('comunidade.buscar') }}" method="GET" class="flex gap-2">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="🔍 Buscar postagens, fichas, modelos..."
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Buscar
                    </button>
                </form>
            </div>

            <!-- Feed de Postagens -->
            @if($posts->count() > 0)
                <div class="space-y-6">
                    @foreach($posts as $post)
                        @include('comunidade.components.post-card', ['post' => $post])
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <p class="text-gray-500 text-lg">📭 Nenhuma postagem ainda. Seja o primeiro!</p>
                    @auth
                        <a href="{{ route('comunidade.create') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Criar Postagem
                        </a>
                    @else
                        <a href="{{ route('usuarios.login') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Fazer Login
                        </a>
                    @endauth
                </div>
            @endif
        </main>
    </div>
</div>
@endsection