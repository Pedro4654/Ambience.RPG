<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ambience RPG - Comunidade</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/moderation.js') }}"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 min-h-screen">
    
<!-- Navbar Dark/RPG -->
<nav class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 sticky top-0 z-50 shadow-2xl border-b border-gray-700">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="text-white text-xl font-bold">🎲</span>
                </div>
                <span class="text-xl font-bold text-white hidden sm:block">Ambience RPG</span>
            </a>

            <!-- Menu Central -->
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('comunidade.feed') }}" class="px-4 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-gray-700/50 transition-all font-medium">
                    Feed
                </a>
                <a href="{{ route('comunidade.buscar') }}" class="px-4 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-gray-700/50 transition-all font-medium">
                    Explorar
                </a>
            </div>

            <!-- Menu Usuário -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('comunidade.create') }}" class="hidden md:flex px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all font-medium items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Criar
                    </a>

                    <!-- Avatar -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 p-1 rounded-lg hover:bg-gray-700/50 transition-all">
                            <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown -->
                        <div class="absolute right-0 mt-2 w-56 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <div class="p-4 border-b border-gray-700">
                                <p class="font-bold text-white">{{ Auth::user()->username }}</p>
                                <p class="text-sm text-gray-400">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('perfil.meu') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                                    Meu Perfil
                                </a>
                                <a href="{{ route('comunidade.salvos') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                                    Salvos
                                </a>
                                <a href="{{ route('perfil.editar') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                                    Configurações
                                </a>
                                <div class="border-t border-gray-700 my-2"></div>
                                <form method="POST" action="{{ route('usuarios.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-900/20 hover:text-red-300 rounded-lg transition-colors">
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('usuarios.login') }}" class="px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-lg transition-all font-medium flex items-center gap-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd"/>
                        </svg>
                        Entrar
                    </a>
                    <a href="{{ route('usuarios.create') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all font-medium">
                        Começar Aventura
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
    <!-- Mensagens Flash -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md animate-pulse">
                <p class="font-medium">✅ {{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
                @foreach($errors->all() as $error)
                    <p class="font-medium">❌ {{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Conteúdo Principal -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 mt-20 py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">© 2024 Ambience RPG. Feito com 💜 para a comunidade RPG</p>
            <div class="flex justify-center gap-4 mt-4">
                <a href="#" class="text-gray-500 hover:text-purple-400 transition-colors">Sobre</a>
                <a href="#" class="text-gray-500 hover:text-purple-400 transition-colors">Termos</a>
                <a href="#" class="text-gray-500 hover:text-purple-400 transition-colors">Privacidade</a>
                <a href="#" class="text-gray-500 hover:text-purple-400 transition-colors">Suporte</a>
            </div>
        </div>
    </footer>

    <script>
        // Adicionar funcionalidade de menu mobile se necessário
    </script>
</body>

</html>
