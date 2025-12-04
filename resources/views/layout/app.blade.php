
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ambience RPG - Comunidade</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/moderation.js') }}"></script>
    <style>
        :root{
          --bg-dark:#0a0f14;
          --card:#1f2a33;
          --muted:#8b9ba8;
          --accent:#22c55e;
          --accent-light:#16a34a;
          --accent-dark:#15803d;
          --hero-green:#052e16;
          --text-on-primary: #e6eef6;
          --transition-speed: 600ms;
          
          --header-bg: rgba(10, 15, 20, 0.75);
          --gradient-start: #052e16;
          --gradient-mid: #064e3b;
          --gradient-end: #065f46;
          --btn-gradient-start: #22c55e;
          --btn-gradient-end: #16a34a;
          --accent-border: rgba(34, 197, 94, 0.4);
        }

        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Inter,system-ui,-apple-system,sans-serif;background:#0a0f14;color:var(--text-on-primary);-webkit-font-smoothing:antialiased;line-height:1.5}

        /* Dropdown do Menu de Usuário */
        .user-dropdown{
          position:absolute;
          top:calc(100% + 12px);
          right:0;
          width:280px;
          background:linear-gradient(145deg,rgba(31,42,51,0.98),rgba(20,28,35,0.98));
          border:1px solid rgba(34,197,94,0.2);
          border-radius:16px;
          padding:0;
          box-shadow:0 20px 60px rgba(0,0,0,0.6);
          backdrop-filter:blur(12px);
          z-index:200;
          display:none;
          animation:slideDown .25s ease;
          overflow:hidden;
        }

        .user-dropdown.active{
          display:block;
        }

        .user-dropdown-header{
          padding:20px;
          border-bottom:1px solid rgba(34,197,94,0.15);
          background:linear-gradient(135deg,rgba(34,197,94,0.08),transparent);
        }

        .user-dropdown-info{
          display:flex;
          align-items:center;
          gap:12px;
          margin-bottom:12px;
        }

        .user-dropdown-avatar{
          width:48px;
          height:48px;
          border-radius:12px;
          object-fit:cover;
          border:2px solid rgba(34,197,94,0.3);
          background:linear-gradient(135deg,#064e3b,#052e16);
        }

        .user-dropdown-avatar-default{
          width:48px;
          height:48px;
          border-radius:12px;
          background:linear-gradient(135deg,#064e3b,#052e16);
          border:2px solid rgba(34,197,94,0.3);
          display:flex;
          align-items:center;
          justify-content:center;
          font-size:20px;
          font-weight:700;
          color:var(--accent);
        }

        .user-dropdown-details{
          flex:1;
          min-width:0;
          overflow:hidden;
        }

        .user-dropdown-details h4{
          font-size:16px;
          font-weight:700;
          color:#fff;
          margin-bottom:4px;
          overflow:hidden;
          text-overflow:ellipsis;
          white-space:nowrap;
        }

        .user-dropdown-details p{
          font-size:13px;
          color:var(--muted);
          overflow:hidden;
          text-overflow:ellipsis;
          white-space:nowrap;
          max-width:100%;
        }

        .user-dropdown-menu{
          padding:8px 0;
        }

        .user-dropdown-item{
          display:flex;
          align-items:center;
          gap:12px;
          padding:12px 20px;
          color:#d1d5db;
          font-size:14px;
          font-weight:500;
          cursor:pointer;
          transition:all .2s;
          text-decoration:none;
          border:none;
          background:transparent;
          width:100%;
          text-align:left;
        }

        .user-dropdown-item:hover{
          background:rgba(34,197,94,0.1);
          color:var(--accent);
        }

        .user-dropdown-item svg{
          width:18px;
          height:18px;
          stroke:currentColor;
          fill:none;
          stroke-width:2;
          stroke-linecap:round;
          stroke-linejoin:round;
        }

        .user-dropdown-divider{
          height:1px;
          background:rgba(34,197,94,0.15);
          margin:8px 0;
        }

        .user-dropdown-item.logout{
          color:#ef4444;
        }

        .user-dropdown-item.logout:hover{
          background:rgba(239,68,68,0.1);
          color:#ef4444;
        }

        /* Header */
        .header{
          position:sticky;
          top:0;
          z-index:100;
          background:var(--header-bg);
          backdrop-filter:blur(12px);
          -webkit-backdrop-filter:blur(12px);
          border-bottom:1px solid rgba(34,197,94,0.12);
          transition:background-color var(--transition-speed) ease, border-color var(--transition-speed) ease;
        }
        .container{max-width:1280px;margin:0 auto;padding:0 32px}
        .nav{display:flex;align-items:center;justify-content:space-between;padding:18px 0;height:70px}
        .logo{display:flex;align-items:center;gap:12px;font-weight:800;font-size:19px;color:#fff;text-decoration:none}
        .logo-img{height:50px;width:auto;display:block}
        .nav-links{display:flex;gap:32px;align-items:center}
        .nav-links a{
          color:rgba(255,255,255,0.9);
          text-decoration:none;
          font-weight:500;
          font-size:15px;
          transition:color .2s;
        }
        .nav-links a:hover{color:var(--accent)}
        .cta-buttons{display:flex;gap:14px;align-items:center}
        .btn{
          padding:11px 22px;
          border-radius:10px;
          font-weight:700;
          font-size:15px;
          border:none;
          cursor:pointer;
          transition:all .25s;
          display:inline-flex;
          align-items:center;
          gap:10px;
          font-family:Inter,sans-serif;
        }
        .btn.login{
          background:transparent;
          border:1px solid var(--accent-border);
          color:var(--accent);
          transition:all .3s ease;
        }
        .btn.login:hover{
          background:rgba(34,197,94,0.1);
          border-color:var(--accent);
        }
        .btn.primary{
          background:linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
          color:#052e16;
          font-weight:700;
          box-shadow:0 4px 14px rgba(34,197,94,0.3);
          transition:all .3s ease;
        }
        .btn.primary:hover{
          transform:translateY(-2px);
          box-shadow:0 6px 20px rgba(34,197,94,0.4);
        }

        /* User Menu (quando logado) */
        .user-menu{
          display:flex;
          align-items:center;
          gap:16px;
        }

        .notification-btn{
          position:relative;
          width:42px;
          height:42px;
          border-radius:10px;
          background:rgba(34,197,94,0.08);
          border:1px solid rgba(34,197,94,0.15);
          display:flex;
          align-items:center;
          justify-content:center;
          cursor:pointer;
          transition:all .25s;
        }

        .notification-btn:hover{
          background:rgba(34,197,94,0.15);
          border-color:rgba(34,197,94,0.3);
          transform:translateY(-2px);
        }

        .notification-btn svg{
          width:20px;
          height:20px;
          stroke:var(--accent);
          fill:none;
          stroke-width:2;
          stroke-linecap:round;
          stroke-linejoin:round;
        }

        .notification-badge{
          position:absolute;
          top:-4px;
          right:-4px;
          width:18px;
          height:18px;
          background:#ef4444;
          border-radius:50%;
          border:2px solid var(--bg-dark);
          font-size:10px;
          font-weight:700;
          color:#fff;
          display:flex;
          align-items:center;
          justify-content:center;
        }

        .user-avatar-wrapper{
          position:relative;
          cursor:pointer;
        }

        .user-avatar{
          width:42px;
          height:42px;
          border-radius:10px;
          object-fit:cover;
          border:2px solid rgba(34,197,94,0.2);
          transition:all .25s;
          background:linear-gradient(135deg,#064e3b,#052e16);
        }

        .user-avatar:hover{
          border-color:var(--accent);
          transform:translateY(-2px);
          box-shadow:0 4px 12px rgba(34,197,94,0.3);
        }

        .user-avatar-default{
          width:42px;
          height:42px;
          border-radius:10px;
          background:linear-gradient(135deg,#064e3b,#052e16);
          border:2px solid rgba(34,197,94,0.2);
          display:flex;
          align-items:center;
          justify-content:center;
          font-size:18px;
          font-weight:700;
          color:var(--accent);
          transition:all .25s;
        }

        .user-avatar-default:hover{
          border-color:var(--accent);
          transform:translateY(-2px);
          box-shadow:0 4px 12px rgba(34,197,94,0.3);
        }

        /* Modal de Notificações */
        .notification-modal{
          position:fixed;
          top:80px;
          right:32px;
          width:360px;
          background:linear-gradient(145deg,rgba(31,42,51,0.95),rgba(20,28,35,0.95));
          border:1px solid rgba(34,197,94,0.2);
          border-radius:16px;
          padding:24px;
          box-shadow:0 20px 60px rgba(0,0,0,0.6);
          backdrop-filter:blur(12px);
          z-index:200;
          display:none;
          animation:slideDown .25s ease;
        }

        @keyframes slideDown{
          from{
            opacity:0;
            transform:translateY(-10px);
          }
          to{
            opacity:1;
            transform:translateY(0);
          }
        }

        .notification-modal.active{
          display:block;
        }

        .notification-header{
          display:flex;
          justify-content:space-between;
          align-items:center;
          margin-bottom:16px;
          padding-bottom:12px;
          border-bottom:1px solid rgba(34,197,94,0.15);
        }

        .notification-header h3{
          font-size:16px;
          font-weight:700;
          color:#fff;
        }

        .notification-close{
          width:28px;
          height:28px;
          border-radius:6px;
          background:transparent;
          border:none;
          cursor:pointer;
          display:flex;
          align-items:center;
          justify-content:center;
          transition:all .2s;
        }

        .notification-close:hover{
          background:rgba(255,255,255,0.05);
        }

        .notification-close svg{
          width:16px;
          height:16px;
          stroke:#8b9ba8;
          stroke-width:2;
        }

        .notification-empty{
          text-align:center;
          padding:32px 16px;
          color:var(--muted);
        }

        .notification-empty svg{
          width:48px;
          height:48px;
          margin:0 auto 12px;
          stroke:var(--muted);
          opacity:0.4;
        }

        .notification-empty p{
          font-size:14px;
          line-height:1.6;
        }

        /* Footer */
        .footer{border-top:1px solid rgba(34,197,94,0.1);padding:38px 0 24px;background:#0d1419}
        .footer-columns{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;max-width:1200px;margin:0 auto 32px;padding:0 32px}
        .footer-brand{display:flex;align-items:center;gap:12px;margin-bottom:14px;font-weight:800;font-size:18px}
        .footer-brand .logo-img{height:110px;width:auto}
        .footer h4{font-size:15px;font-weight:700;margin-bottom:14px;color:var(--accent);transition:color var(--transition-speed) ease}
        .footer ul{list-style:none}
        .footer ul li{margin:8px 0;color:var(--muted);font-size:14px;cursor:pointer;transition:color .2s}
        .footer ul li:hover{color:var(--accent)}
        .footer-text{color:var(--muted);font-size:14px;line-height:1.6;max-width:320px}
        .social-links{display:flex;gap:16px;margin-top:12px}
        .social-links svg{width:20px;height:20px;fill:var(--muted);cursor:pointer;transition:fill .2s}
        .social-links svg:hover{fill:var(--accent)}
        .footer-bottom{text-align:center;color:var(--muted);font-size:13px;padding-top:24px;border-top:1px solid rgba(34,197,94,0.1);max-width:1200px;margin:0 auto;padding:24px 32px 0}

        /* Responsive */
        @media(max-width:768px){
          .nav-links{display:none}
          .footer-columns{grid-template-columns:1fr}
          .notification-modal{
            right:16px;
            left:16px;
            width:auto;
          }
        }

        /* Estilos específicos para conteúdo do app */
        .app-content {
          min-height: calc(100vh - 140px);
          padding: 32px 0;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="bg-[#0a0f14] text-[#e6eef6]">

<header class="header">
  <div class="container">
    <nav class="nav">
      <a href="{{ route('home') }}" class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
      </a>
      <div class="nav-links">
        <a href="{{ route('salas.index') }}">Salas</a>
        <a href="{{ route('comunidade.feed') }}">Comunidade</a>
        <a href="{{ route('suporte.index') }}">Suporte</a>
      </div>
      
      @guest
        <div class="cta-buttons">
          <button class="btn login" onclick="window.location.href='{{ route('usuarios.login') }}'">Entrar</button>
          <button class="btn primary" onclick="window.location.href='{{ route('usuarios.create') }}'">Começar Agora</button>
        </div>
      @else
        <div class="user-menu">
          <button class="notification-btn" id="notificationBtn" aria-label="Notificações">
            <svg viewBox="0 0 24 24">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
          </button>
          
          <div class="user-avatar-wrapper" id="userAvatarBtn">
            @if(auth()->user()->avatar_url)
              <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->username }}" class="user-avatar">
            @else
              <div class="user-avatar-default">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</div>
            @endif
            
            <!-- Dropdown Menu -->
            <div class="user-dropdown" id="userDropdown">
              <div class="user-dropdown-header">
                <div class="user-dropdown-info">
                  @if(auth()->user()->avatar_url)
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->username }}" class="user-dropdown-avatar">
                  @else
                    <div class="user-dropdown-avatar-default">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</div>
                  @endif
                  <div class="user-dropdown-details">
                    <h4>{{ auth()->user()->username }}</h4>
                    <p>{{ auth()->user()->email }}</p>
                  </div>
                </div>
              </div>
              
              <div class="user-dropdown-menu">
                <a href="{{ route('perfil.show', auth()->user()->username) }}" class="user-dropdown-item">
                  <svg viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                  </svg>
                  Meu Perfil
                </a>
                
                <div class="user-dropdown-divider"></div>
                
                <form method="POST" action="{{ route('usuarios.logout') }}" style="margin:0;">
                  @csrf
                  <button type="submit" class="user-dropdown-item logout">
                    <svg viewBox="0 0 24 24">
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
        </div>
      @endguest
    </nav>
  </div>
</header>

<!-- Modal de Notificações -->
<div class="notification-modal" id="notificationModal">
  <div class="notification-header">
    <h3>Notificações</h3>
    <button class="notification-close" id="closeNotificationModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
  </div>
  <div class="notification-empty">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
      <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
    </svg>
    <p>Você não tem notificações no momento.<br>Quando algo acontecer, você verá aqui!</p>
  </div>
</div>

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
    <main class="app-content">
        @yield('content')
    </main>

   <footer class="footer">
  <div class="footer-columns">
    <div>
      <div class="footer-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
      </div>
      <p class="footer-text"></p>
    </div>
    <div>
      <h4>Recursos</h4>
      <ul>
        <li onclick="window.location.href='{{ route('salas.index') }}'">Salas</li>
        <li onclick="window.location.href='{{ route('comunidade.feed') }}'">Comunidade</li>
        <li onclick="window.location.href='{{ route('suporte.index') }}'">Suporte</li>
      </ul>
    </div>
    <div>
      <h4>Ajuda</h4>
      <ul>
        <li onclick="window.location.href='{{ route('suporte.create') }}'">Criar Ticket</li>
        <li onclick="window.location.href='{{ route('suporte.index') }}'">Meus Tickets</li>
        <li onclick="window.location.href='{{ route('suporte.faq') }}'">FAQ</li>
      </ul>
    </div>
    <div>
      <h4>Conecte-se</h4>
      <div class="social-links" aria-hidden="true">
        <a href="https://www.youtube.com/@AmbienceRPG" target="_blank" aria-label="Youtube">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M10 15.5v-7l6 3.5-6 3.5ZM21.8 7.2a3 3 0 0 0-2.1-2.1C17.5 4.5 12 4.5 12 4.5s-5.5 0-7.7.6a3 3 0 0 0-2.1 2.1A31 31 0 0 0 1.5 12a31 31 0 0 0 .7 4.8 3 3 0 0 0 2.1 2.1c2.2.6 7.7.6 7.7.6s5.5 0 7.7-.6a3 3 0 0 0 2.1-2.1c.5-1.5.7-3.2.7-4.8s-.2-3.3-.7-4.8Z"/>
          </svg>
        </a>
        <a href="https://instagram.com/ambience.rpg" target="_blank" aria-label="Instagram">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm-5 3a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm4.75-3.25a1 1 0 1 1 0 2 1 1 0 0 1 0-2Z"/>
          </svg>
        </a>
      </div>
    </div>
  </div>
  <div class="footer-bottom">© 2025 Ambience RPG. Todos os direitos reservados.</div>
</footer>

    <script>
        // ========== NOTIFICAÇÕES ==========
        (function(){
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationModal = document.getElementById('notificationModal');
            const closeNotificationModal = document.getElementById('closeNotificationModal');
            const notificationBadge = document.querySelector('.notification-badge');

            if(!notificationBtn || !notificationModal) return;

            let notificacoes = [];
            let offset = 0;
            const limit = 10;

            // Carregar notificações
            async function carregarNotificacoes(append = false) {
                try {
                    const response = await fetch(`/api/notificacoes?limit=${limit}&offset=${offset}`);
                    const data = await response.json();

                    if (data.success) {
                        if (append) {
                            notificacoes = [...notificacoes, ...data.notificacoes];
                        } else {
                            notificacoes = data.notificacoes;
                        }

                        renderizarNotificacoes();
                        atualizarBadge(data.total_nao_lidas);
                    }
                } catch (error) {
                    console.error('Erro ao carregar notificações:', error);
                }
            }

            // Renderizar notificações no DOM
            function renderizarNotificacoes() {
                const container = document.querySelector('.notification-list');
                if (!container) {
                    // Criar container se não existir
                    const emptyDiv = notificationModal.querySelector('.notification-empty');
                    if (emptyDiv) {
                        emptyDiv.remove();
                    }

                    const list = document.createElement('div');
                    list.className = 'notification-list';
                    notificationModal.appendChild(list);
                    renderizarNotificacoes();
                    return;
                }

                if (notificacoes.length === 0) {
                    container.innerHTML = `
                        <div class="notification-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            <p>Você não tem notificações no momento.<br>Quando algo acontecer, você verá aqui!</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = notificacoes.map(notif => `
                    <div class="notification-item ${notif.lida ? 'lida' : 'nao-lida'}" data-id="${notif.id}">
                        <div class="notification-icon ${notif.cor}">
                            ${getIconSvg(notif.icone)}
                        </div>
                        <div class="notification-content">
                            <p class="notification-message">${notif.mensagem}</p>
                            <span class="notification-time">${notif.tempo}</span>
                        </div>
                        <div class="notification-actions">
                            ${!notif.lida ? `
                                <button class="notification-action-btn marcar-lida" data-id="${notif.id}" title="Marcar como lida">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </button>
                            ` : ''}
                            <button class="notification-action-btn remover" data-id="${notif.id}" title="Remover">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `).join('');

                // Adicionar event listeners
                adicionarEventListeners();
            }

            // Atualizar badge de contador
            function atualizarBadge(count) {
                if (count > 0) {
                    if (!notificationBadge) {
                        const badge = document.createElement('span');
                        badge.className = 'notification-badge';
                        badge.textContent = count > 99 ? '99+' : count;
                        notificationBtn.appendChild(badge);
                    } else {
                        notificationBadge.textContent = count > 99 ? '99+' : count;
                    }
                } else {
                    if (notificationBadge) {
                        notificationBadge.remove();
                    }
                }
            }

            // Marcar notificação como lida
            async function marcarComoLida(id) {
                try {
                    const response = await fetch(`/api/notificacoes/${id}/marcar-lida`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Atualizar notificação localmente
                        const notif = notificacoes.find(n => n.id === id);
                        if (notif) {
                            notif.lida = true;
                        }
                        renderizarNotificacoes();
                        atualizarBadge(data.total_nao_lidas);
                    }
                } catch (error) {
                    console.error('Erro ao marcar como lida:', error);
                }
            }

            // Remover notificação
            async function removerNotificacao(id) {
                try {
                    const response = await fetch(`/api/notificacoes/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Remover notificação localmente
                        notificacoes = notificacoes.filter(n => n.id !== id);
                        renderizarNotificacoes();
                        atualizarBadge(data.total_nao_lidas);
                    }
                } catch (error) {
                    console.error('Erro ao remover notificação:', error);
                }
            }

            // Adicionar event listeners
            function adicionarEventListeners() {
                // Marcar como lida
                document.querySelectorAll('.marcar-lida').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const id = parseInt(btn.dataset.id);
                        marcarComoLida(id);
                    });
                });

                // Remover
                document.querySelectorAll('.remover').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const id = parseInt(btn.dataset.id);
                        removerNotificacao(id);
                    });
                });

                // Clicar na notificação (ir para o link)
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const notif = notificacoes.find(n => n.id === parseInt(item.dataset.id));
                        if (notif && notif.link) {
                            if (!notif.lida) {
                                marcarComoLida(notif.id);
                            }
                            window.location.href = notif.link;
                        }
                    });
                });
            }

            // Obter ícone SVG
            function getIconSvg(icone) {
                const icones = {
                    'UserPlus': '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>',
                    'Heart': '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
                    'MessageCircle': '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>',
                    'AtSign': '<circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/>',
                    'Mail': '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
                    'Bell': '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'
                };

                return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${icones[icone] || icones['Bell']}</svg>`;
            }

            // Abrir/fechar modal
            notificationBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationModal.classList.toggle('active');
                const userDropdown = document.getElementById('userDropdown');
                if(userDropdown) userDropdown.classList.remove('active');

                if (notificationModal.classList.contains('active')) {
                    carregarNotificacoes();
                }
            });

            if(closeNotificationModal) {
                closeNotificationModal.addEventListener('click', () => {
                    notificationModal.classList.remove('active');
                });
            }

            document.addEventListener('click', (e) => {
                if(!notificationModal.contains(e.target) && e.target !== notificationBtn) {
                    notificationModal.classList.remove('active');
                }
            });

            document.addEventListener('keydown', (e) => {
                if(e.key === 'Escape' && notificationModal.classList.contains('active')) {
                    notificationModal.classList.remove('active');
                }
            });

            // Polling para verificar novas notificações a cada 30 segundos
            setInterval(async () => {
                try {
                    const response = await fetch('/api/notificacoes/count');
                    const data = await response.json();
                    
                    if (data.success) {
                        atualizarBadge(data.count);
                    }
                } catch (error) {
                    console.error('Erro ao verificar notificações:', error);
                }
            }, 30000);

            // Carregar contador inicial
            (async () => {
                try {
                    const response = await fetch('/api/notificacoes/count');
                    const data = await response.json();
                    
                    if (data.success) {
                        atualizarBadge(data.count);
                    }
                } catch (error) {
                    console.error('Erro ao carregar contador inicial:', error);
                }
            })();
        })();

        // ========== DROPDOWN DE USUÁRIO ==========
        (function(){
            const userAvatarBtn = document.getElementById('userAvatarBtn');
            const userDropdown = document.getElementById('userDropdown');

            if(!userAvatarBtn || !userDropdown) return;

            userAvatarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('active');
                const notificationModal = document.getElementById('notificationModal');
                if(notificationModal) notificationModal.classList.remove('active');
            });

            document.addEventListener('click', (e) => {
                if(!userDropdown.contains(e.target) && !userAvatarBtn.contains(e.target)) {
                    userDropdown.classList.remove('active');
                }
            });

            document.addEventListener('keydown', (e) => {
                if(e.key === 'Escape' && userDropdown.classList.contains('active')) {
                    userDropdown.classList.remove('active');
                }
            });
        })();
    </script>
</body>

</html>
