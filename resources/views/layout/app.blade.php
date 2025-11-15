<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambience RPG - Comunidade</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/moderation.js') }}"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .glassmorphism {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
    
    <!-- Navbar Moderna -->
    <nav class="glassmorphism sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo -->
                <a href="{{ route('comunidade.feed') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="text-white text-xl font-bold">🎲</span>
                    </div>
                    <span class="text-xl font-bold gradient-text hidden sm:block">Ambience RPG</span>
                </a>

                <!-- Menu Central -->
                <div class="hidden md:flex items-center gap-2">
                    <a href="{{ route('comunidade.feed') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-white hover:shadow-md transition-all font-medium">
                        🏠 Feed
                    </a>
                    <a href="{{ route('comunidade.buscar') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-white hover:shadow-md transition-all font-medium">
                        🔍 Explorar
                    </a>
                    @auth
                        <a href="{{ route('comunidade.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            ✨ Criar Post
                        </a>
                    @endauth
                </div>

                <!-- Menu Usuário -->
                <div class="flex items-center gap-3">
                    @auth
                        <!-- Notificações -->
                        <button class="relative p-2 text-gray-600 hover:bg-white rounded-lg transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Avatar Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center gap-2 p-1 rounded-lg hover:bg-white transition-all">
                                <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" 
                                     alt="{{ Auth::user()->username }}" 
                                     class="w-8 h-8 rounded-full object-cover ring-2 ring-purple-400">
                                <span class="hidden sm:block text-sm font-medium text-gray-700">{{ Auth::user()->username }}</span>
                            </button>
                            
                            <!-- Dropdown -->
                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                                <div class="p-4 border-b">
                                    <p class="font-bold text-gray-800">{{ Auth::user()->username }}</p>
                                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="p-2">
                                    <a href="{{ route('perfil.meu') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                        👤 Meu Perfil
                                    </a>
                                    <a href="{{ route('comunidade.salvos') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                        💾 Salvos
                                    </a>
                                    <a href="{{ route('perfil.editar') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                        ⚙️ Configurações
                                    </a>
                                    <div class="border-t my-2"></div>
                                    <form method="POST" action="{{ route('usuarios.logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                                            🚪 Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('usuarios.login') }}" class="px-4 py-2 text-gray-700 hover:bg-white rounded-lg transition-all font-medium">
                            🔑 Entrar
                        </a>
                        <a href="{{ route('usuarios.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            ✨ Cadastrar
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
    <footer class="glassmorphism mt-20 py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-600">© 2024 Ambience RPG. Feito com 💜 para a comunidade RPG</p>
            <div class="flex justify-center gap-4 mt-4">
                <a href="#" class="text-gray-500 hover:text-blue-600 transition-colors">Sobre</a>
                <a href="#" class="text-gray-500 hover:text-blue-600 transition-colors">Termos</a>
                <a href="#" class="text-gray-500 hover:text-blue-600 transition-colors">Privacidade</a>
                <a href="#" class="text-gray-500 hover:text-blue-600 transition-colors">Suporte</a>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Toggle -->
    <script>
        // Adicionar funcionalidade de menu mobile se necessário
    </script>
</body>

</html>