        @extends('layout.app')

        @section('content')
        <!-- ESTILO INLINE COMPLETO -->
        <style>
        :root {
        --bg-dark: #0a0f14;
        --card: #1f2a33;
        --muted: #8b9ba8;
        --accent: #22c55e;
        --accent-light: #16a34a;
        --accent-dark: #15803d;
        --hero-green: #052e16;
        --text-on-primary: #e6eef6;
        --transition-speed: 600ms;
        }
        body {
        background: var(--bg-dark);
        color: var(--text-on-primary);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        /* NAVBAR ESTILO AMBIENCE */
        .header-ambience {
        position: sticky; top: 0; z-index: 100;
        background: rgba(10, 15, 20, 0.85);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(34,197,94,0.15);
        transition: all 600ms;
        }
        .container-nav { max-width: 1280px; margin: 0 auto; padding: 0 32px; }
        .nav-ambience { display: flex; align-items: center; justify-content: space-between; padding: 18px 0; height: 70px; }
        .logo-ambience { display: flex; align-items: center; gap: 12px; font-weight: 800; font-size: 18px; color: #fff; text-decoration: none; }
        .logo-img { height: 48px; width: auto; }
        .nav-links { display: flex; gap: 32px; align-items: center; }
        .nav-links a { color: rgba(255,255,255,0.85); text-decoration: none; font-weight: 500; font-size: 15px; transition: color .2s; }
        .nav-links a:hover { color: var(--accent); }
        .cta-buttons { display: flex; gap: 14px; align-items: center; }
        .btn-ambience {
        padding: 11px 22px; border-radius: 10px; font-weight: 700; font-size: 15px; border: none; cursor: pointer;
        transition: all .25s; display: inline-flex; align-items: center; gap: 10px;
        }
        .btn-ambience.login { background: transparent; border: 1px solid rgba(34,197,94,0.4); color: var(--accent); }
        .btn-ambience.login:hover { background: rgba(34,197,94,0.1); border-color: var(--accent); }
        .btn-ambience.primary {
        background: linear-gradient(to right, var(--accent), var(--accent-light)); color: var(--hero-green); font-weight: 800;
        box-shadow: 0 4px 14px rgba(34,197,94,0.3);
        }
        .btn-ambience.primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(34,197,94,0.4); }
        .user-menu { display: flex; align-items: center; gap: 16px; position: relative; }
        .notification-btn {
        position: relative; width: 42px; height: 42px; border-radius: 10px;
        background: rgba(34,197,94,0.08); border:1px solid rgba(34,197,94,0.15);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .25s;
        }
        .notification-btn:hover { background: rgba(34,197,94,0.15); border-color: var(--accent); transform: translateY(-2px); }
        .notification-btn svg { width: 20px; height: 20px; stroke: var(--accent); fill: none; stroke-width: 2; }
        .notification-badge {
        position:absolute; top:-4px; right:-4px; width: 18px; height: 18px; background: #ef4444;
        border-radius: 50%; border:2px solid var(--bg-dark); font-size:10px; font-weight:700; color:#fff;
        display:flex; align-items:center; justify-content:center;
        }
        .user-avatar-wrapper { position: relative; cursor: pointer; }
        .user-avatar {
        width: 42px; height: 42px; border-radius: 10px; object-fit: cover;
        border:2px solid rgba(34,197,94,0.2);
        transition: all .25s; background: linear-gradient(135deg, #064e3b, #052e16);
        }
        .user-avatar:hover { border-color: var(--accent); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(34,197,94,0.3); }
        .user-avatar-default {
        width: 42px; height: 42px; border-radius: 10px; background: linear-gradient(135deg, #064e3b, #052e16);
        border:2px solid rgba(34,197,94,0.2); display: flex; align-items: center; justify-content: center;
        font-size: 18px; font-weight: 700; color: var(--accent); transition: all .25s;
        }
        .user-avatar-default:hover { border-color: var(--accent); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(34,197,94,0.3);}
        .user-dropdown {
        display: none; position: absolute; right: 0; top: calc(100% + 12px); min-width: 260px;
        background: var(--card); border: 1px solid rgba(34,197,94,0.2); border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3); overflow: hidden;
        }
        .user-dropdown.show { display: block; }
        .user-dropdown-header {
        padding: 20px; border-bottom: 1px solid rgba(34,197,94,0.15);
        background: linear-gradient(135deg, rgba(34,197,94,0.08), transparent);
        }
        .user-dropdown-info { display: flex; align-items: center; gap: 12px; }
        .user-dropdown-avatar {
        width: 48px; height: 48px; border-radius: 12px; object-fit: cover;
        border: 2px solid rgba(34,197,94,0.3);
        }
        .user-dropdown-avatar-default {
        width: 48px; height: 48px; border-radius: 12px;
        background: linear-gradient(135deg, #064e3b, #052e16);
        border: 2px solid rgba(34,197,94,0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 700; color: var(--accent);
        }
        .user-dropdown-details { flex:1; min-width:0; }
        .user-dropdown-details h4 { font-size:16px; font-weight:700; color:#fff; margin:0; }
        .user-dropdown-details p { font-size:13px; color:var(--muted); margin:0; }
        .user-dropdown-menu { padding: 8px 0; }
        .user-dropdown-item {
        display: flex; align-items: center; gap: 12px; padding: 12px 20px;
        color: #d1d5db; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none;
        transition: all .2s; background: transparent; border: none; width: 100%; text-align: left;
        }
        .user-dropdown-item:hover { background: rgba(34,197,94,0.08); color: var(--accent); }
        .user-dropdown-item.logout { color: #ef4444; }
        .user-dropdown-item.logout:hover { background: rgba(239,68,68,0.1); }
        .user-dropdown-item svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }
        .divider-dropdown { height: 1px; background: rgba(34,197,94,0.15); margin: 8px 0; }

        /* PERFIL */
        .profile-card {
        background: var(--card);
        border-radius: 2rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        padding: 2.5rem 2rem;
        border: 1px solid rgba(34,197,94,0.1);
        }
        .profile-card .avatar {
        border-radius: 1.25rem;
        border: 4px solid var(--card);
        box-shadow: 0 0 0 4px rgba(34,197,94,0.2), 0 8px 24px rgba(0,0,0,0.3);
        }
        .social-icons a {
        display: inline-flex;
        width: 44px; height: 44px;
        align-items: center; justify-content: center;
        border-radius: 50%;
        font-size: 1.4rem;
        margin-right: 0.5rem;
        margin-left: 0.5rem;
        color: #fff;
        transition: box-shadow .2s, transform .14s;
        }
        .social-icons .discord { background: #5865F2; }
        .social-icons .youtube { background: #FF0000; }
        .social-icons .twitch { background: #9147ff; }
        .social-icons a:hover { box-shadow: 0 0 0 4px rgba(255,255,255,0.2); transform: scale(1.10); }
        </style>
        <!-- FONT AWESOME -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

        <!-- NAVBAR AMBIENCE - VERSÃO CORRETA -->
        <header class="header-ambience">
        <div class="container-nav">
            <nav class="nav-ambience">
            {{-- Logo só com ícone --}}
            <a href="{{ route('home') }}" class="logo-ambience">
                <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
            </a>
            
            <div class="nav-links">
                <a href="{{ route('salas.index') }}">Salas</a>
                <a href="{{ route('comunidade.feed') }}">Comunidade</a>
                <a href="{{ route('suporte.index') }}">Suporte</a>
            </div>
            
            @guest
                <div class="cta-buttons">
                <button class="btn-ambience login" onclick="window.location.href='{{ route('usuarios.login') }}'">Entrar</button>
                <button class="btn-ambience primary" onclick="window.location.href='{{ route('usuarios.create') }}'">Começar Agora</button>
                </div>
            @else
                <div class="user-menu">
                {{-- Botão de Notificações --}}
                <button class="notification-btn" id="notificationBtn" aria-label="Notificações">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    @php
                    $unreadCount = 0;
                    if(method_exists(auth()->user(), 'unreadNotifications') && auth()->user()->unreadNotifications) {
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    }
                    @endphp
                    @if($unreadCount > 0)
                    <span class="notification-badge">{{ $unreadCount }}</span>
                    @endif
                </button>
                
                {{-- Avatar do Usuário --}}
                <div class="user-avatar-wrapper" id="userAvatarBtn">
                    @if(auth()->user()->avatar_url)
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->username }}" class="user-avatar">
                    @else
                    <div class="user-avatar-default">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </div>
                    @endif
                </div>
                
                {{-- Dropdown do Usuário --}}
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-dropdown-header">
                    <div class="user-dropdown-info">
                        @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->username }}" class="user-dropdown-avatar">
                        @else
                        <div class="user-dropdown-avatar-default">
                            {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                        </div>
                        @endif
                        <div class="user-dropdown-details">
                        <h4>{{ auth()->user()->username }}</h4>
                        <p>{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    </div>
                    <div class="user-dropdown-menu">
                    <a href="{{ route('perfil.show', auth()->user()->username) }}" class="user-dropdown-item">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Meu Perfil
                    </a>
                    <div class="divider-dropdown"></div>
                    <form method="POST" action="{{ route('usuarios.logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="user-dropdown-item logout">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Sair
                        </button>
                    </form>
                    </div>
                </div>
                </div>
            @endguest
            </nav>
        </div>
        </header>



        <!-- CONTEÚDO DO PERFIL -->
        <div class="min-h-screen py-12" style="background: var(--bg-dark);">
            <div class="container mx-auto px-4 max-w-5xl">

                {{-- BANNER --}}
                <div class="relative h-56 md:h-64 rounded-3xl shadow-2xl overflow-hidden mb-10" style="background: linear-gradient(135deg, var(--hero-green), var(--accent-dark), var(--accent));">
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full blur-3xl"></div>
                        <div class="absolute bottom-10 right-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                    </div>
                    <button onclick="history.back()" class="absolute top-6 left-6 px-4 py-2 bg-white/10 backdrop-blur-md text-white rounded-lg hover:bg-white/20 transition-all font-medium">
                        ← Voltar
                    </button>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                            Perfil de {{ $usuario->username }}
                        </h1>
                        <p class="text-sm md:text-base opacity-90 mt-1">
                            Visualize informações, postagens e redes sociais
                        </p>
                    </div>
                </div>

                {{-- GRID: PERFIL + EDIÇÃO --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
                    <div class="lg:col-span-2">
                        {{-- CARD PERFIL --}}
                        <div class="profile-card mb-6">
                            <div class="flex flex-col md:flex-row gap-8 @auth @if(Auth::id() !== $usuario->id) items-center justify-center text-center @endif @else items-center justify-center text-center @endauth">
                                <div class="flex flex-col items-center md:items-start">
                                    <div class="relative group">
                                        <img 
                                            src="{{ $usuario->avatar_url ?? asset('images/default-avatar.png') }}" 
                                            alt="{{ $usuario->username }}"
                                            class="w-32 h-32 rounded-2xl object-cover ring-4 ring-gray-800 shadow-2xl mb-4 group-hover:scale-105 transition-transform avatar"
                                        >
                                        @if(Auth::check() && Auth::id() === $usuario->id)
                                            <button type="button" class="absolute bottom-3 right-0 w-9 h-9 bg-gradient-to-r from-green-600 to-green-500 text-white rounded-full shadow-lg hover:scale-110 transition-transform text-lg flex items-center justify-center">
                                                📷
                                            </button>
                                        @endif
                                    </div>
                                    <h2 class="text-2xl font-bold mb-1" style="color: var(--text-on-primary);">
                                        {{ $usuario->username }}
                                    </h2>
                                </div>

                                <div class="flex-1 flex flex-col gap-4 @auth @if(Auth::id() !== $usuario->id) items-center text-center @endif @endauth">
                                    @if($usuario->bio)
                                        <p style="color: var(--muted);">{{ $usuario->bio }}</p>
                                    @else
                                        <p style="color: rgba(139,155,168,0.5);" class="italic">Sem bio ainda...</p>
                                    @endif

                                    {{-- REDES SOCIAIS --}}
                                    @php
                                        $hasAnySocial = $usuario->discord_url || $usuario->youtube_url || $usuario->twitch_url;
                                    @endphp
                                    @if($hasAnySocial)
                                        <div class="social-icons flex gap-4 mt-2 @auth @if(Auth::id() !== $usuario->id) justify-center @else justify-start @endif @else justify-center @endauth">
                                            @if($usuario->discord_url)
                                                <a href="{{ $usuario->discord_url }}" target="_blank" class="discord" title="Discord">
                                                    <i class="fa-brands fa-discord"></i>
                                                </a>
                                            @endif
                                            @if($usuario->youtube_url)
                                                <a href="{{ $usuario->youtube_url }}" target="_blank" class="youtube" title="YouTube">
                                                    <i class="fa-brands fa-youtube"></i>
                                                </a>
                                            @endif
                                            @if($usuario->twitch_url)
                                                <a href="{{ $usuario->twitch_url }}" target="_blank" class="twitch" title="Twitch">
                                                    <i class="fa-brands fa-twitch"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- ESTATÍSTICAS --}}
                                    <div class="grid grid-cols-3 gap-6 p-5 rounded-2xl" style="background: rgba(34,197,94,0.08);">
                                        <div class="text-center">
                                            <div class="text-2xl font-extrabold mb-1" style="color: var(--accent);">
                                                {{ $posts->total() }}
                                            </div>
                                            <p class="text-xs font-semibold" style="color: var(--muted);">📝 Posts</p>
                                        </div>
                                        <a href="{{ route('perfil.seguidores', $usuario->id) }}" class="text-center hover:scale-105 transition-transform">
                                            <div class="text-2xl font-extrabold mb-1" style="color: var(--accent-light);">
                                                {{ $usuario->seguidores()->count() }}
                                            </div>
                                            <p class="text-xs font-semibold" style="color: var(--muted);">👥 Seguidores</p>
                                        </a>
                                        <a href="{{ route('perfil.seguindo', $usuario->id) }}" class="text-center hover:scale-105 transition-transform">
                                            <div class="text-2xl font-extrabold mb-1" style="color: var(--accent);">
                                                {{ $usuario->seguindo()->count() }}
                                            </div>
                                            <p class="text-xs font-semibold" style="color: var(--muted);">💫 Seguindo</p>
                                        </a>
                                    </div>

                                    {{-- BOTÕES --}}
                                    <div class="flex flex-wrap gap-3 mt-3 @auth @if(Auth::id() !== $usuario->id) justify-center @endif @else justify-center @endauth">
                                        @auth
                                            @if(Auth::id() === $usuario->id)
                                                <a href="{{ route('comunidade.feed') }}" class="flex-1 min-w-[180px] py-3 rounded-xl font-bold text-center transition-all" style="background: rgba(34,197,94,0.1); color: var(--accent); border: 1px solid rgba(34,197,94,0.3);">
                                                    📰 Ver Feed
                                                </a>
                                            @else
                                                @if($esta_seguindo)
                                                    <form action="{{ route('perfil.deixar_de_seguir', $usuario->id) }}" method="POST" class="flex-1 min-w-[180px]">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="w-full py-3 rounded-xl font-bold transition-all" style="background: rgba(139,155,168,0.2); color: var(--muted);">✓ Seguindo</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('perfil.seguir', $usuario->id) }}" method="POST" class="flex-1 min-w-[180px]">
                                                        @csrf
                                                        <button type="submit" class="w-full py-3 rounded-xl font-bold transition-all" style="background: linear-gradient(to right, var(--accent), var(--accent-light)); color: var(--hero-green); box-shadow: 0 4px 14px rgba(34,197,94,0.3);">+ Seguir</button>
                                                    </form>
                                                @endif
                                                <button class="px-6 py-3 rounded-xl font-bold transition-all" style="background: rgba(34,197,94,0.1); color: var(--accent); border: 1px solid rgba(34,197,94,0.3);">💬 Mensagem</button>
                                            @endif
                                        @else
                                            <a href="{{ route('usuarios.login') }}" class="flex-1 py-3 rounded-xl font-bold text-center transition-all" style="background: linear-gradient(to right, var(--accent), var(--accent-light)); color: var(--hero-green); box-shadow: 0 4px 14px rgba(34,197,94,0.3);">
                                                🔑 Fazer Login para Seguir
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TABS --}}
                        <div class="profile-card mb-8">
                            <div class="flex border-b" style="border-color: rgba(34,197,94,0.15);">
                                <button onclick="mostrarAba('posts')" id="tab-posts" class="flex-1 py-3 md:py-4 px-4 md:px-6 font-bold transition-all border-b-2" style="color: var(--accent); border-color: var(--accent);">
                                    📝 Postagens
                                </button>
                                @auth
                                    @if(Auth::id() === $usuario->id)
                                        <button onclick="mostrarAba('salvos')" id="tab-salvos" class="flex-1 py-3 md:py-4 px-4 md:px-6 font-bold transition-all" style="color: var(--muted);">
                                            💾 Salvos
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        {{-- CONTEÚDO POSTS --}}
                        <div id="aba-posts">
                            @if($posts->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                                    @foreach($posts as $post)
                                        <a href="{{ route('comunidade.post.show', $post->slug) }}" class="group profile-card hover:shadow-2xl transition-all transform hover:-translate-y-2">
                                            <div class="relative h-44 md:h-48 overflow-hidden rounded-2xl mb-4" style="background: linear-gradient(135deg, var(--hero-green), var(--accent-dark));">
                                                @if($post->arquivos->first() && $post->arquivos->first()->tipo === 'imagem')
                                                    <img src="{{ $post->arquivos->first()->url }}" alt="{{ $post->titulo }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-white text-6xl">📝</div>
                                                @endif
                                                <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(34,197,94,0.9); color: var(--hero-green);">
                                                    {{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}
                                                </span>
                                            </div>
                                            <h3 class="font-bold text-base md:text-lg mb-2 line-clamp-2 group-hover:text-green-500 transition-colors" style="color: var(--text-on-primary);">
                                                {{ $post->titulo }}
                                            </h3>
                                            <p class="text-sm line-clamp-2 mb-4" style="color: var(--muted);">{{ $post->conteudo }}</p>
                                            <div class="flex gap-4 text-xs md:text-sm" style="color: var(--muted);">
                                                <span>❤️ {{ $post->curtidas()->count() }}</span>
                                                <span>💬 {{ $post->comentarios()->count() }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mt-6">{{ $posts->links() }}</div>
                            @else
                                <div class="profile-card p-14 text-center">
                                    <div class="text-7xl mb-4">📝</div>
                                    <h3 class="text-2xl font-bold mb-2" style="color: var(--text-on-primary);">Nenhuma postagem ainda</h3>
                                    <p class="mb-6" style="color: var(--muted);">Compartilhe conteúdo com a comunidade</p>
                                    @auth
                                        @if(Auth::id() === $usuario->id)
                                            <a href="{{ route('comunidade.create') }}" class="inline-block px-8 py-4 rounded-xl font-bold transition-all" style="background: linear-gradient(to right, var(--accent), var(--accent-light)); color: var(--hero-green); box-shadow: 0 4px 14px rgba(34,197,94,0.3);">
                                                ✨ Criar Primeira Postagem
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </div>

                        {{-- SALVOS --}}
                        @auth
                            @if(Auth::id() === $usuario->id)
                                <div id="aba-salvos" class="hidden">
                                    @if($usuario->saved_posts()->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                                            @foreach($usuario->saved_posts as $saved)
                                                @php $post = $saved->post; @endphp
                                                <div class="group profile-card hover:shadow-2xl transition-all transform hover:-translate-y-2">
                                                    <div class="relative h-44 md:h-48 overflow-hidden rounded-2xl mb-4" style="background: linear-gradient(135deg, var(--hero-green), var(--accent-dark));">
                                                        @if($post->arquivos->first() && $post->arquivos->first()->tipo === 'imagem')
                                                            <img src="{{ $post->arquivos->first()->url }}" alt="{{ $post->titulo }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-white text-6xl">📝</div>
                                                        @endif
                                                        <span class="absolute top-3 right-3 px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-bold">💾 Salvo</span>
                                                    </div>
                                                    <h3 class="font-bold text-base md:text-lg mb-2 line-clamp-2" style="color: var(--text-on-primary);">{{ $post->titulo }}</h3>
                                                    <p class="text-sm line-clamp-2 mb-4" style="color: var(--muted);">{{ $post->conteudo }}</p>
                                                    <div class="flex gap-2">
                                                        <a href="{{ route('comunidade.post.show', $post->slug) }}" class="flex-1 py-2 rounded-lg text-xs md:text-sm font-bold text-center transition-all" style="background: linear-gradient(to right, var(--accent), var(--accent-light)); color: var(--hero-green);">Ver Post</a>
                                                        <form action="{{ route('comunidade.desalvar', $post->id) }}" method="POST" class="flex-1">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="w-full py-2 bg-red-100 text-red-600 text-xs md:text-sm font-bold rounded-lg hover:bg-red-200 transition-all" onclick="return confirm('Remover dos salvos?')">🗑️</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="profile-card p-14 text-center">
                                            <div class="text-7xl mb-4">💾</div>
                                            <h3 class="text-2xl font-bold mb-2" style="color: var(--text-on-primary);">Nenhuma postagem salva</h3>
                                            <p class="mb-6" style="color: var(--muted);">Salve suas postagens favoritas para acessá-las rapidamente</p>
                                            <a href="{{ route('comunidade.feed') }}" class="inline-block px-8 py-4 rounded-xl font-bold transition-all" style="background: linear-gradient(to right, var(--accent), var(--accent-light)); color: var(--hero-green); box-shadow: 0 4px 14px rgba(34,197,94,0.3);">
                                                Explorar Feed →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endauth
                    </div>

                    {{-- FORMULÁRIO EDIÇÃO --}}
                    @auth
                        @if(Auth::id() === $usuario->id)
                            <div>
                                <div class="profile-card">
                                    <div class="px-6 py-5 rounded-t-3xl mb-5" style="background: linear-gradient(to right, var(--accent), var(--accent-light)); margin: -2.5rem -2rem 1.5rem -2rem; padding: 2rem;">
                                        <h2 class="text-xl font-bold" style="color: var(--hero-green);">✏️ Editar Perfil</h2>
                                        <p class="text-sm mt-1" style="color: rgba(5,46,22,0.8);">Atualize sua bio e redes sociais</p>
                                    </div>
                                    <form action="{{ route('perfil.update') }}" method="POST" class="space-y-5">
                                        @csrf @method('PUT')
                                        <div>
                                            <label class="block text-xs font-bold mb-2" style="color: var(--text-on-primary);">📝 Bio (até 500 caracteres)</label>
                                            <textarea name="bio" maxlength="500" rows="4" placeholder="Conte um pouco sobre você..." class="w-full px-3 py-2 rounded-xl resize-none text-sm" style="background: rgba(34,197,94,0.05); border: 2px solid rgba(34,197,94,0.2); color: var(--text-on-primary);">{{ old('bio', Auth::user()->bio) }}</textarea>
                                            @error('bio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold mb-2" style="color: var(--text-on-primary);">
                                                <span class="inline-flex items-center gap-2"><i class="fa-brands fa-discord" style="color:#5865F2"></i> Discord</span>
                                            </label>
                                            <input type="url" name="discord_url" placeholder="https://discord.gg/seu-link" value="{{ old('discord_url', Auth::user()->discord_url) }}" class="w-full px-3 py-2 rounded-xl text-sm" style="background: rgba(34,197,94,0.05); border: 2px solid rgba(34,197,94,0.2); color: var(--text-on-primary);">
                                            @error('discord_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold mb-2" style="color: var(--text-on-primary);">
                                                <span class="inline-flex items-center gap-2"><i class="fa-brands fa-youtube" style="color:#FF0000"></i> YouTube</span>
                                            </label>
                                            <input type="url" name="youtube_url" placeholder="https://youtube.com/@seu-canal" value="{{ old('youtube_url', Auth::user()->youtube_url) }}" class="w-full px-3 py-2 rounded-xl text-sm" style="background: rgba(34,197,94,0.05); border: 2px solid rgba(34,197,94,0.2); color: var(--text-on-primary);">
                                            @error('youtube_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold mb-2" style="color: var(--text-on-primary);">
                                                <span class="inline-flex items-center gap-2"><i class="fa-brands fa-twitch" style="color:#9147ff"></i> Twitch</span>
                                            </label>
                                            <input type="url" name="twitch_url" placeholder="https://twitch.tv/seu-user" value="{{ old('twitch_url', Auth::user()->twitch_url) }}" class="w-full px-3 py-2 rounded-xl text-sm" style="background: rgba(34,197,94,0.05); border: 2px solid rgba(34,197,94,0.2); color: var(--text-on-primary);">
                                            @error('twitch_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <div class="flex flex-col gap-2 pt-3" style="border-top: 1px solid rgba(34,197,94,0.15);">
                                            <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-sm transition-all" style="background: linear-gradient(to right, var(--accent), var(--accent-light)); color: var(--hero-green); box-shadow: 0 4px 14px rgba(34,197,94,0.3);">
                                                ✅ Salvar Alterações
                                            </button>
                                            <a href="{{ route('perfil.show', Auth::user()->username) }}" class="w-full py-2.5 rounded-xl font-bold text-sm text-center transition-all" style="background: rgba(139,155,168,0.2); color: var(--muted);">
                                                ❌ Cancelar
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <script>
        // Dropdown usuário
        document.addEventListener('DOMContentLoaded', function() {
        const avatarBtn = document.getElementById('userAvatarBtn');
        const dropdown = document.getElementById('userDropdown');
        if(avatarBtn && dropdown) {
            avatarBtn.addEventListener('click', e => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
            });
            document.addEventListener('click', function(e) {
            if(!dropdown.contains(e.target) && e.target !== avatarBtn && !avatarBtn.contains(e.target)) {
                dropdown.classList.remove('show');
            }
            });
        }
        });
        // Tabs
        function mostrarAba(aba) {
            document.getElementById('aba-posts').classList.add('hidden');
            const abaSalvos = document.getElementById('aba-salvos');
            if (abaSalvos) abaSalvos.classList.add('hidden');
            document.getElementById('tab-posts').style.borderColor = 'transparent';
            document.getElementById('tab-posts').style.color = 'var(--muted)';
            const tabSalvos = document.getElementById('tab-salvos');
            if (tabSalvos) {
            tabSalvos.style.borderColor = 'transparent';
            tabSalvos.style.color = 'var(--muted)';
            }
            if (aba === 'posts') {
                document.getElementById('aba-posts').classList.remove('hidden');
                document.getElementById('tab-posts').style.borderColor = 'var(--accent)';
                document.getElementById('tab-posts').style.color = 'var(--accent)';
            } else if (aba === 'salvos' && abaSalvos) {
                abaSalvos.classList.remove('hidden');
                tabSalvos.style.borderColor = 'var(--accent)';
                tabSalvos.style.color = 'var(--accent)';
            }
        }
        </script>
        @endsection
