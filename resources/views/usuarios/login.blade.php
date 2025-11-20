<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Entrar - Ambience RPG</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --bg-dark:#0a0f14;
  --card:#1f2937;
  --muted:#8b9ba8;
  --accent:#22c55e;
  --accent-light:#16a34a;
  --accent-dark:#15803d;
  --text-primary:#e6eef6;
  --transition-speed:600ms;
}

*{box-sizing:border-box;margin:0;padding:0}

body{
  font-family:Inter,system-ui,-apple-system,sans-serif;
  background:linear-gradient(to bottom,rgba(5,46,22,0.90),rgba(6,78,59,0.85)),url('{{ asset("images/1.jpg") }}');
  background-size:cover;
  background-position:center;
  background-attachment:fixed;
  color:var(--text-primary);
  -webkit-font-smoothing:antialiased;
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:2rem 1rem;
  overflow-x:hidden;
}

/* ========== ANIMAÇÃO DE PORTAL ========== */
@keyframes portalOpen{
  0%{
    opacity:0;
    transform:scale(0.8) rotateY(90deg);
    filter:blur(20px) hue-rotate(180deg);
  }
  50%{
    filter:blur(10px) hue-rotate(90deg);
  }
  100%{
    opacity:1;
    transform:scale(1) rotateY(0deg);
    filter:blur(0) hue-rotate(0deg);
  }
}

@keyframes portalClose{
  0%{
    opacity:1;
    transform:scale(1) rotateY(0deg);
    filter:blur(0);
  }
  50%{
    filter:blur(15px) hue-rotate(-90deg);
  }
  100%{
    opacity:0;
    transform:scale(0.8) rotateY(-90deg);
    filter:blur(25px) hue-rotate(-180deg);
  }
}

@keyframes floatIn{
  from{
    opacity:0;
    transform:translateY(30px);
  }
  to{
    opacity:1;
    transform:translateY(0);
  }
}

@keyframes glowPulse{
  0%,100%{
    box-shadow:0 0 20px rgba(34,197,94,0.3);
  }
  50%{
    box-shadow:0 0 40px rgba(34,197,94,0.6),0 0 60px rgba(34,197,94,0.4);
  }
}

/* ========== CONTAINER PRINCIPAL ========== */
.portal-container{
  animation:portalOpen 0.8s cubic-bezier(0.34,1.56,0.64,1);
  width:100%;
  max-width:480px;
}

.portal-container.closing{
  animation:portalClose 0.6s cubic-bezier(0.6,-0.28,0.735,0.045) forwards;
}

.login-card{
  background:rgba(17,24,39,0.95);
  backdrop-filter:blur(20px);
  border-radius:24px;
  padding:3rem 2.5rem;
  box-shadow:0 20px 60px rgba(0,0,0,0.5),0 0 1px rgba(34,197,94,0.2);
  border:1px solid rgba(34,197,94,0.1);
  position:relative;
  overflow:hidden;
}

.login-card::before{
  content:'';
  position:absolute;
  top:-50%;
  left:-50%;
  width:200%;
  height:200%;
  background:radial-gradient(circle,rgba(34,197,94,0.1) 0%,transparent 70%);
  animation:glowPulse 4s ease-in-out infinite;
  pointer-events:none;
}

/* ========== LOGO E TÍTULO ========== */
.login-header{
  text-align:center;
  margin-bottom:2.5rem;
  position:relative;
  z-index:2;
}

.logo-container{
  display:flex;
  justify-content:center;
  margin-bottom:1.5rem;
  animation:floatIn 0.6s ease 0.2s backwards;
}

.logo-img{
  height:70px;
  width:auto;
  filter:drop-shadow(0 4px 8px rgba(34,197,94,0.4));
}

.login-title{
  font-family:Montserrat,sans-serif;
  font-size:2rem;
  font-weight:900;
  color:#fff;
  text-transform:uppercase;
  letter-spacing:2px;
  margin-bottom:0.5rem;
  animation:floatIn 0.6s ease 0.3s backwards;
}

.login-subtitle{
  color:var(--muted);
  font-size:0.95rem;
  animation:floatIn 0.6s ease 0.4s backwards;
}

/* ========== FORMULÁRIO ========== */
.login-form{
  position:relative;
  z-index:2;
}

.form-group{
  position:relative;
  margin-bottom:1.5rem;
  animation:floatIn 0.6s ease backwards;
}

.form-group:nth-child(1){animation-delay:0.5s}
.form-group:nth-child(2){animation-delay:0.6s}

.form-label{
  display:block;
  font-size:0.875rem;
  font-weight:600;
  color:#d1d5db;
  margin-bottom:0.5rem;
  transition:color 0.3s;
}

.input-wrapper{
  position:relative;
}

.input-icon{
  position:absolute;
  left:1rem;
  top:50%;
  transform:translateY(-50%);
  width:20px;
  height:20px;
  color:var(--muted);
  pointer-events:none;
  transition:color 0.3s,transform 0.3s;
}

.form-input{
  width:100%;
  padding:1rem 1rem 1rem 3rem;
  background:#1f2937;
  border:2px solid #374151;
  border-radius:12px;
  font-size:1rem;
  color:#f9fafb;
  transition:all 0.3s cubic-bezier(0.4,0,0.2,1);
  font-family:Inter,sans-serif;
}

.form-input::placeholder{
  color:#6b7280;
}

.form-input:focus{
  outline:none;
  border-color:var(--accent);
  background:#111827;
  box-shadow:0 0 0 4px rgba(34,197,94,0.1),0 8px 16px rgba(0,0,0,0.2);
  transform:translateY(-2px);
}

.form-input:focus + .input-icon{
  color:var(--accent);
  transform:translateY(-50%) scale(1.1);
}

/* ========== TOGGLE DE SENHA ========== */
.password-toggle{
  position:absolute;
  right:1rem;
  top:50%;
  transform:translateY(-50%);
  background:transparent;
  border:none;
  cursor:pointer;
  padding:0.5rem;
  border-radius:8px;
  transition:all 0.2s;
  z-index:10;
}

.password-toggle:hover{
  background:rgba(34,197,94,0.1);
}

.eye-icon{
  width:20px;
  height:20px;
  color:var(--muted);
  transition:color 0.2s;
}

.password-toggle:hover .eye-icon{
  color:var(--accent);
}

/* ========== LINK ESQUECI SENHA ========== */
.forgot-link{
  display:block;
  text-align:right;
  font-size:0.875rem;
  color:var(--accent);
  text-decoration:none;
  margin-top:0.75rem;
  transition:all 0.2s;
  font-weight:500;
}

.forgot-link:hover{
  color:var(--accent-light);
  transform:translateX(4px);
}

/* ========== BOTÃO DE LOGIN ========== */
.login-button{
  width:100%;
  padding:1.125rem 1.5rem;
  background:linear-gradient(135deg,#22c55e,#16a34a);
  color:#052e16;
  font-weight:700;
  font-size:1.125rem;
  border:none;
  border-radius:12px;
  cursor:pointer;
  box-shadow:0 4px 14px rgba(34,197,94,0.4);
  transition:all 0.3s cubic-bezier(0.4,0,0.2,1);
  text-transform:uppercase;
  letter-spacing:1px;
  position:relative;
  overflow:hidden;
  margin-top:2rem;
  animation:floatIn 0.6s ease 0.7s backwards;
}

.login-button::before{
  content:'';
  position:absolute;
  top:50%;
  left:50%;
  width:0;
  height:0;
  border-radius:50%;
  background:rgba(255,255,255,0.3);
  transform:translate(-50%,-50%);
  transition:width 0.6s,height 0.6s;
}

.login-button:hover::before{
  width:300px;
  height:300px;
}

.login-button:hover{
  transform:translateY(-4px);
  box-shadow:0 8px 20px rgba(34,197,94,0.5);
}

.login-button:active{
  transform:translateY(-2px);
}

/* ========== DIVIDER ========== */
.divider{
  display:flex;
  align-items:center;
  margin:2rem 0;
  animation:floatIn 0.6s ease 0.8s backwards;
}

.divider::before,
.divider::after{
  content:'';
  flex:1;
  height:1px;
  background:linear-gradient(to right,transparent,#374151,transparent);
}

.divider span{
  padding:0 1rem;
  color:var(--muted);
  font-size:0.875rem;
  font-weight:500;
}

/* ========== LINK PARA CADASTRO ========== */
.signup-link{
  text-align:center;
  animation:floatIn 0.6s ease 0.9s backwards;
}

.signup-link p{
  color:#9ca3af;
  font-size:0.95rem;
  margin-bottom:1rem;
}

.portal-button{
  display:inline-flex;
  align-items:center;
  gap:0.75rem;
  padding:0.875rem 2rem;
  background:transparent;
  border:2px solid var(--accent);
  color:var(--accent);
  font-weight:600;
  font-size:1rem;
  border-radius:12px;
  cursor:pointer;
  transition:all 0.3s;
  text-decoration:none;
  position:relative;
  overflow:hidden;
}

.portal-button::before{
  content:'';
  position:absolute;
  top:50%;
  left:50%;
  width:0;
  height:0;
  background:var(--accent);
  border-radius:50%;
  transform:translate(-50%,-50%);
  transition:width 0.4s,height 0.4s;
}

.portal-button:hover::before{
  width:400px;
  height:400px;
}

.portal-button:hover{
  color:#052e16;
  border-color:var(--accent-light);
  transform:scale(1.05);
}

.portal-button span{
  position:relative;
  z-index:1;
}

.portal-icon{
  position:relative;
  z-index:1;
  width:20px;
  height:20px;
  transition:transform 0.3s;
}

.portal-button:hover .portal-icon{
  transform:rotate(180deg);
}

/* ========== ALERTAS ========== */
.alert{
  padding:1rem 1.25rem;
  border-radius:12px;
  margin-bottom:1.5rem;
  font-size:0.875rem;
  font-weight:500;
  display:flex;
  align-items:center;
  gap:0.75rem;
  animation:floatIn 0.4s ease;
}

.alert-error{
  background:rgba(239,68,68,0.1);
  border:1px solid #ef4444;
  color:#ef4444;
}

.alert-success{
  background:rgba(34,197,94,0.1);
  border:1px solid var(--accent);
  color:var(--accent);
}

/* ========== RESPONSIVO ========== */
@media(max-width:640px){
  .login-card{
    padding:2rem 1.5rem;
  }
  
  .login-title{
    font-size:1.5rem;
  }
  
  .logo-img{
    height:50px;
  }
}

/* ========== ANIMAÇÃO DE LOADING ========== */
@keyframes spin{
  to{transform:rotate(360deg)}
}

.loading{
  pointer-events:none;
  opacity:0.7;
}

.loading::after{
  content:'';
  position:absolute;
  top:50%;
  left:50%;
  width:24px;
  height:24px;
  margin:-12px 0 0 -12px;
  border:3px solid rgba(5,46,22,0.3);
  border-top-color:#052e16;
  border-radius:50%;
  animation:spin 0.8s linear infinite;
}
</style>
</head>
<body>

<div class="portal-container" id="portalContainer">
  <div class="login-card">
    <div class="login-header">
      <div class="logo-container">
        <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
      </div>
      <h1 class="login-title">Bem-vindo de Volta</h1>
      <p class="login-subtitle">Continue sua jornada épica</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px">
        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px">
        <circle cx="12" cy="12" r="10"/>
        <line x1="15" y1="9" x2="9" y2="15"/>
        <line x1="9" y1="9" x2="15" y2="15"/>
      </svg>
      <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('usuarios.login.post') }}" class="login-form" id="loginForm">
      @csrf
      
      <div class="form-group">
        <label class="form-label">Email</label>
        <div class="input-wrapper">
          <input 
            type="email" 
            name="email" 
            id="email"
            required
            class="form-input"
            placeholder="seu@email.com"
            value="{{ old('email') }}"
          >
          <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
          </svg>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Senha</label>
        <div class="input-wrapper">
          <input 
            type="password" 
            name="password" 
            id="password"
            required
            class="form-input"
            placeholder="••••••••"
            style="padding-right:3rem"
          >
          <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
          <button type="button" class="password-toggle" id="togglePassword">
            <svg class="eye-icon" id="eyeIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg class="eye-icon" id="eyeOffIcon" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            </svg>
          </button>
        </div>
        <a href="{{ route('usuarios.forgot.form') }}" class="forgot-link">Esqueci minha senha</a>
      </div>

      <button type="submit" class="login-button" id="loginButton">
        Entrar na Aventura
      </button>
    </form>

    <div class="divider">
      <span>ou</span>
    </div>

    <div class="signup-link">
      <p>Novo aventureiro?</p>
      <a href="#" class="portal-button" id="portalToSignup">
        <svg class="portal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
        </svg>
        <span>Criar Conta</span>
      </a>
    </div>
  </div>
</div>

<script>
// Toggle de senha
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeIcon = document.getElementById('eyeIcon');
const eyeOffIcon = document.getElementById('eyeOffIcon');

togglePassword.addEventListener('click', () => {
  const type = passwordInput.type === 'password' ? 'text' : 'password';
  passwordInput.type = type;
  
  if(type === 'text'){
    eyeIcon.style.display = 'none';
    eyeOffIcon.style.display = 'block';
  } else {
    eyeIcon.style.display = 'block';
    eyeOffIcon.style.display = 'none';
  }
});

// Animação de portal para cadastro
const portalButton = document.getElementById('portalToSignup');
const portalContainer = document.getElementById('portalContainer');

portalButton.addEventListener('click', (e) => {
  e.preventDefault();
  portalContainer.classList.add('closing');
  
  setTimeout(() => {
    window.location.href = '{{ route("usuarios.create") }}';
  }, 600);
});

// Loading no submit
const loginForm = document.getElementById('loginForm');
const loginButton = document.getElementById('loginButton');

loginForm.addEventListener('submit', () => {
  loginButton.classList.add('loading');
  loginButton.textContent = 'Entrando...';
});

// Animação de foco nos inputs
document.querySelectorAll('.form-input').forEach(input => {
  input.addEventListener('focus', () => {
    input.parentElement.parentElement.querySelector('.form-label').style.color = 'var(--accent)';
  });
  
  input.addEventListener('blur', () => {
    input.parentElement.parentElement.querySelector('.form-label').style.color = '#d1d5db';
  });
});
</script>
</body>
</html>