<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Criar Conta - Ambience RPG</title>
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
  padding:2rem 1rem;
  overflow-x:hidden;
}

/* ========== ANIMAÇÕES ========== */
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

@keyframes cardPulse{
  0%,100%{
    transform:scale(1);
  }
  50%{
    transform:scale(1.02);
  }
}

@keyframes flipAvatar{
  0%{
    transform:rotateY(0deg) scale(1);
  }
  50%{
    transform:rotateY(90deg) scale(0.95);
    opacity:0.5;
  }
  100%{
    transform:rotateY(0deg) scale(1);
    opacity:1;
  }
}

@keyframes glowPulse{
  0%,100%{
    box-shadow:0 0 20px rgba(34,197,94,0.3);
  }
  50%{
    box-shadow:0 0 40px rgba(34,197,94,0.6);
  }
}

@keyframes progressFill{
  from{
    width:0%;
  }
}

@keyframes badgePop{
  0%{
    transform:scale(0) rotate(-180deg);
    opacity:0;
  }
  60%{
    transform:scale(1.2) rotate(10deg);
  }
  100%{
    transform:scale(1) rotate(0deg);
    opacity:1;
  }
}

@keyframes shine{
  0%{transform:translateX(-100%)}
  100%{transform:translateX(100%)}
}

/* ========== CONTAINER PRINCIPAL ========== */
.portal-container{
  animation:portalOpen 0.8s cubic-bezier(0.34,1.56,0.64,1);
  max-width:1400px;
  margin:0 auto;
}

.portal-container.closing{
  animation:portalClose 0.6s cubic-bezier(0.6,-0.28,0.735,0.045) forwards;
}

.register-layout{
  display:grid;
  grid-template-columns:1fr 400px;
  gap:2rem;
  align-items:start;
}

/* ========== CARD DO FORMULÁRIO ========== */
.register-card{
  background:rgba(17,24,39,0.95);
  backdrop-filter:blur(20px);
  border-radius:24px;
  padding:2.5rem;
  box-shadow:0 20px 60px rgba(0,0,0,0.5),0 0 1px rgba(34,197,94,0.2);
  border:1px solid rgba(34,197,94,0.1);
  position:relative;
  overflow:hidden;
}

.register-card::before{
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

/* ========== HEADER ========== */
.register-header{
  text-align:center;
  margin-bottom:2rem;
  position:relative;
  z-index:2;
}

.logo-container{
  display:flex;
  justify-content:center;
  margin-bottom:1rem;
  animation:floatIn 0.6s ease 0.2s backwards;
}

.logo-img{
  height:60px;
  width:auto;
  filter:drop-shadow(0 4px 8px rgba(34,197,94,0.4));
}

.register-title{
  font-family:Montserrat,sans-serif;
  font-size:1.75rem;
  font-weight:900;
  color:#fff;
  text-transform:uppercase;
  letter-spacing:2px;
  margin-bottom:0.5rem;
  animation:floatIn 0.6s ease 0.3s backwards;
}

.register-subtitle{
  color:var(--muted);
  font-size:0.9rem;
  animation:floatIn 0.6s ease 0.4s backwards;
}

/* ========== BARRA DE AFINIDADE ========== */
.affinity-bar{
  background:#111827;
  border-radius:12px;
  padding:1rem 1.25rem;
  margin-bottom:2rem;
  border:1px solid #374151;
  position:relative;
  z-index:2;
  animation:floatIn 0.6s ease 0.5s backwards;
}

.affinity-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:0.75rem;
}

.affinity-title{
  font-size:0.875rem;
  font-weight:700;
  color:#d1d5db;
  display:flex;
  align-items:center;
  gap:0.5rem;
}

.affinity-icon{
  width:20px;
  height:20px;
  color:var(--accent);
}

.affinity-percentage{
  font-size:1rem;
  font-weight:800;
  color:var(--accent);
}

.progress-track{
  height:8px;
  background:#1f2937;
  border-radius:4px;
  overflow:hidden;
  position:relative;
}

.progress-fill{
  height:100%;
  background:linear-gradient(90deg,var(--accent-dark),var(--accent),var(--accent-light));
  border-radius:4px;
  transition:width 0.6s cubic-bezier(0.34,1.56,0.64,1);
  position:relative;
  box-shadow:0 0 10px rgba(34,197,94,0.6);
}

.progress-fill::after{
  content:'';
  position:absolute;
  top:0;
  left:0;
  right:0;
  bottom:0;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,0.3),transparent);
  animation:shine 2s infinite;
}

.affinity-badge{
  display:none;
  align-items:center;
  gap:0.5rem;
  margin-top:0.75rem;
  padding:0.75rem 1rem;
  background:linear-gradient(135deg,rgba(34,197,94,0.2),rgba(22,163,74,0.1));
  border:1px solid var(--accent);
  border-radius:8px;
  font-size:0.875rem;
  font-weight:600;
  color:var(--accent);
}

.affinity-badge.show{
  display:flex;
  animation:badgePop 0.6s cubic-bezier(0.34,1.56,0.64,1);
}

/* ========== GRID DE CAMPOS ========== */
.form-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:1.25rem;
  position:relative;
  z-index:2;
}

.form-grid-full{
  grid-column:1/-1;
}

.form-group{
  animation:floatIn 0.6s ease backwards;
}

.form-label{
  display:block;
  font-size:0.875rem;
  font-weight:600;
  color:#d1d5db;
  margin-bottom:0.5rem;
  transition:color 0.3s;
}

.form-input,
.form-select{
  width:100%;
  padding:0.875rem 1rem;
  background:#1f2937;
  border:2px solid #374151;
  border-radius:10px;
  font-size:0.95rem;
  color:#f9fafb;
  transition:all 0.3s cubic-bezier(0.4,0,0.2,1);
  font-family:Inter,sans-serif;
}

.form-input::placeholder{
  color:#6b7280;
}

.form-input:focus,
.form-select:focus{
  outline:none;
  border-color:var(--accent);
  background:#111827;
  box-shadow:0 0 0 4px rgba(34,197,94,0.1);
  transform:translateY(-2px);
}

.form-input.input-warn{
  border-color:#ef4444 !important;
  background:#1f2937;
}

.form-select{
  cursor:pointer;
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
  background-repeat:no-repeat;
  background-position:right 1rem center;
  background-size:20px;
  padding-right:3rem;
}

.form-select:invalid{
  color:#6b7280;
}

.form-select option{
  background:#1f2937;
  color:#f9fafb;
}

textarea.form-input{
  min-height:100px;
  resize:vertical;
}

.error-message{
  display:block;
  margin-top:0.375rem;
  font-size:0.875rem;
  color:#ef4444;
}

.field-info{
  display:block;
  margin-top:0.375rem;
  font-size:0.75rem;
  color:#9ca3af;
}

/* ========== DATA DE NASCIMENTO ========== */
.date-grid{
  display:grid;
  grid-template-columns:1fr 2fr 1.5fr;
  gap:0.75rem;
}

/* ========== UPLOAD DE ARQUIVO ========== */
.file-upload-wrapper{
  position:relative;
  width:100%;
}

.file-input-hidden{
  position:absolute;
  opacity:0;
  width:0;
  height:0;
  pointer-events:none;
}

.file-upload-button{
  display:flex !important;
  align-items:center !important;
  justify-content:flex-start !important;
  gap:0.75rem !important;
  padding:0.875rem 1rem !important;
  background:#1f2937 !important;
  border:2px solid #374151 !important;
  border-radius:10px !important;
  cursor:pointer !important;
  transition:all 0.3s !important;
  font-size:0.95rem !important;
  color:#6b7280 !important;
  font-weight:500 !important;
  width:100% !important;
}

.file-upload-button:hover{
  background:#111827 !important;
  border-color:var(--accent) !important;
  transform:translateY(-2px);
}

.upload-icon{
  width:20px !important;
  height:20px !important;
  color:var(--accent) !important;
  flex-shrink:0 !important;
}

#file-name{
  overflow:hidden;
  text-overflow:ellipsis;
  white-space:nowrap;
  flex:1;
}

/* ALERTA NSFW */
#avatar-nsfw-alert{
  margin-top:0.75rem;
  display:none;
}

/* ========== CHECKBOX DE TERMOS ========== */
.terms-box{
  background:#111827;
  border:2px solid #374151;
  border-radius:12px;
  padding:1.25rem;
  margin:1.5rem 0;
  transition:all 0.3s;
}

.terms-box.active{
  border-color:var(--accent);
  background:rgba(34,197,94,0.05);
}

.terms-label{
  display:flex;
  align-items:flex-start;
  gap:0.75rem;
  cursor:pointer;
  color:#d1d5db;
  font-size:0.875rem;
  line-height:1.6;
}

.terms-checkbox{
  margin-top:0.25rem;
  width:20px;
  height:20px;
  cursor:pointer;
  accent-color:var(--accent);
}

.terms-link{
  color:var(--accent);
  text-decoration:none;
  font-weight:600;
  transition:color 0.2s;
}

.terms-link:hover{
  color:var(--accent-light);
  text-decoration:underline;
}

.terms-status{
  display:block;
  margin-top:0.75rem;
  font-size:0.75rem;
  color:#9ca3af;
  padding-left:calc(20px + 0.75rem);
}

/* ========== BOTÃO DE SUBMIT ========== */
.submit-button{
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
  margin-top:1rem;
}

.submit-button:disabled{
  opacity:0.5;
  cursor:not-allowed;
  transform:none!important;
}

.submit-button:not(:disabled):hover{
  transform:translateY(-4px);
  box-shadow:0 8px 20px rgba(34,197,94,0.5);
}

/* ========== LINK PARA LOGIN ========== */
.login-link{
  text-align:center;
  margin-top:1.5rem;
}

.login-link p{
  color:#9ca3af;
  font-size:0.95rem;
  margin-bottom:1rem;
}

.portal-button{
  display:inline-flex;
  align-items:center;
  gap:0.75rem;
  padding:0.75rem 1.5rem;
  background:transparent;
  border:2px solid var(--accent);
  color:var(--accent);
  font-weight:600;
  font-size:0.95rem;
  border-radius:10px;
  cursor:pointer;
  transition:all 0.3s;
  text-decoration:none;
}

.portal-button:hover{
  background:var(--accent);
  color:#052e16;
  transform:scale(1.05);
}

/* ========== PREVIEW DO PERSONAGEM ========== */
.character-preview{
  position:sticky;
  top:2rem;
  background:rgba(17,24,39,0.95);
  backdrop-filter:blur(20px);
  border-radius:24px;
  padding:2rem;
  box-shadow:0 20px 60px rgba(0,0,0,0.5),0 0 1px rgba(34,197,94,0.2);
  border:1px solid rgba(34,197,94,0.1);
  animation:floatIn 0.6s ease 0.6s backwards;
}

.character-preview.pulse{
  animation:cardPulse 0.4s ease;
}

.preview-header{
  text-align:center;
  margin-bottom:1.5rem;
}

.preview-title{
  font-size:1.25rem;
  font-weight:700;
  color:#fff;
  margin-bottom:0.5rem;
}

.preview-subtitle{
  font-size:0.875rem;
  color:var(--muted);
}

.character-card{
  background:#111827;
  border-radius:16px;
  padding:1.5rem;
  text-align:center;
  border:2px solid #374151;
  transition:all 0.3s;
}

.character-card.active{
  border-color:var(--accent);
  box-shadow:0 0 20px rgba(34,197,94,0.3);
}

.character-avatar{
  width:200px;
  height:200px;
  margin:0 auto 1.5rem;
  border-radius:16px;
  overflow:hidden;
  background:linear-gradient(135deg,#064e3b,#052e16);
  border:3px solid #374151;
  transition:border-color 0.3s;
  position:relative;
}

.character-avatar.active{
  border-color:var(--accent);
  box-shadow:0 8px 24px rgba(34,197,94,0.4);
}

.character-avatar.flipping{
  animation:flipAvatar 0.6s ease;
}

.character-avatar img{
  width:100%;
  height:100%;
  object-fit:cover;
}

.character-placeholder{
  width:100%;
  height:100%;
  display:flex;
  align-items:center;
  justify-content:center;
  color:var(--muted);
  font-size:4rem;
}

.character-info{
  display:flex;
  flex-direction:column;
  gap:0.75rem;
}

.info-item{
  background:#1f2937;
  padding:0.75rem 1rem;
  border-radius:10px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  font-size:0.875rem;
}

.info-label{
  color:var(--muted);
  font-weight:500;
}

.info-value{
  color:#fff;
  font-weight:700;
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

/* ========== MODAIS ========== */
.terms-modal{
  display:none;
  position:fixed;
  z-index:9999;
  left:0;
  top:0;
  width:100%;
  height:100%;
  background-color:rgba(0,0,0,0.8);
}

.terms-modal-content{
  background-color:#1f2937;
  margin:3% auto;
  width:90%;
  max-width:800px;
  border-radius:12px;
  box-shadow:0 10px 40px rgba(0,0,0,0.5);
  display:flex;
  flex-direction:column;
  max-height:85vh;
}

.terms-modal-header{
  padding:20px 30px;
  background:linear-gradient(135deg,#10b981 0%,#059669 100%);
  color:white;
  border-radius:12px 12px 0 0;
  display:flex;
  justify-content:space-between;
  align-items:center;
}

.terms-modal-header h2{
  margin:0;
  font-size:24px;
  font-weight:600;
}

.terms-close{
  background:rgba(255,255,255,0.2);
  border:none;
  color:white;
  font-size:32px;
  font-weight:bold;
  cursor:pointer;
  width:40px;
  height:40px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  transition:background 0.3s;
}

.terms-close:hover{
  background:rgba(255,255,255,0.3);
}

.progress-container{
  background:#374151;
  height:6px;
  margin:0;
}

.progress-bar{
  height:100%;
  background:linear-gradient(90deg,#10b981 0%,#059669 100%);
  width:0%;
  transition:width 0.1s ease;
}

.progress-text{
  display:block;
  padding:8px 30px;
  color:#9ca3af;
  font-size:13px;
  background:#111827;
  border-bottom:1px solid #374151;
}

.terms-modal-body{
  padding:30px;
  overflow-y:auto;
  flex:1;
  line-height:1.8;
  color:#d1d5db;
  background:#1f2937;
}

.terms-modal-body h3{
  color:#10b981;
  margin-top:25px;
  margin-bottom:15px;
  font-size:20px;
  font-weight:600;
}

.terms-modal-body p{
  margin-bottom:15px;
  color:#d1d5db;
}

.terms-modal-body ul,.terms-modal-body ol{
  margin-left:20px;
  margin-bottom:15px;
}

.terms-modal-body strong{
  color:#f9fafb;
}

.terms-modal-footer{
  padding:20px 30px;
  background:#111827;
  border-radius:0 0 12px 12px;
  display:flex;
  justify-content:flex-end;
  border-top:1px solid #374151;
}

.terms-btn-confirm{
  background:linear-gradient(135deg,#10b981 0%,#059669 100%);
  color:white;
  border:none;
  padding:12px 30px;
  border-radius:6px;
  font-size:16px;
  font-weight:600;
  cursor:pointer;
  transition:transform 0.2s,box-shadow 0.2s;
}

.terms-btn-confirm:hover:not(:disabled){
  transform:translateY(-2px);
  box-shadow:0 5px 15px rgba(16,185,129,0.4);
}

.terms-btn-confirm:disabled{
  background:#4b5563;
  cursor:not-allowed;
  opacity:0.6;
}

/* ========== RESPONSIVO ========== */
@media(max-width:1024px){
  .register-layout{
    grid-template-columns:1fr;
  }
  
  .character-preview{
    order:-1;
    position:relative;
    top:0;
  }
}

@media(max-width:640px){
  .form-grid{
    grid-template-columns:1fr;
  }
  
  .date-grid{
    grid-template-columns:1fr;
  }
  
  .register-card{
    padding:1.5rem;
  }
  
  .character-avatar{
    width:150px;
    height:150px;
  }
}
</style>
</head>
<body>

<div class="portal-container" id="portalContainer">
  <div class="register-layout">
    <!-- FORMULÁRIO -->
    <div class="register-card">
      <div class="register-header">
        <div class="logo-container">
          <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
        </div>
        <h1 class="register-title">Criar Sua Lenda</h1>
        <p class="register-subtitle">Sua jornada épica começa aqui</p>
      </div>

      <!-- BARRA DE AFINIDADE -->
      <div class="affinity-bar">
        <div class="affinity-header">
          <div class="affinity-title">
            <svg class="affinity-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span>Afinidade com RPG</span>
          </div>
          <span class="affinity-percentage" id="affinityPercent">0%</span>
        </div>
        <div class="progress-track">
          <div class="progress-fill" id="progressFill" style="width:0%"></div>
        </div>
        <div class="affinity-badge" id="affinityBadge">
          <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span>🎉 Pronto pra ser derrotado pelo primeiro goblin!</span>
        </div>
      </div>

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

      <form method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data" id="registerForm">
        @csrf

        <div class="form-grid">
          <!-- Username -->
          <div class="form-group">
            <label class="form-label">Nome de Usuário *</label>
            <input type="text" name="username" id="username" required class="form-input" placeholder="ConanBarbaro" value="{{ old('username') }}">
            <small id="username-warning" style="display:none;color:#ef4444;font-size:0.875rem;">Conteúdo inapropriado detectado</small>
            @error('username')
            <small class="error-message">{{ $message }}</small>
            @enderror
          </div>

          <!-- Email -->
          <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="email" id="email" required class="form-input" placeholder="seu@email.com" value="{{ old('email') }}">
            @error('email')
            <small class="error-message">{{ $message }}</small>
            @enderror
          </div>

          <!-- Senha -->
          <div class="form-group">
            <label class="form-label">Senha *</label>
            <input type="text" name="password" id="password" required class="form-input" placeholder="Mínimo 8 caracteres" autocomplete="new-password">
            <small id="password-strength" class="field-info">Mínimo 8 caracteres com pelo menos 1 letra</small>
            @error('password')
            <small class="error-message">{{ $message }}</small>
            @enderror
          </div>

          <!-- Confirmar Senha -->
          <div class="form-group">
            <label class="form-label">Confirmar Senha *</label>
            <input type="text" name="password_confirmation" id="passwordConfirm" required class="form-input" placeholder="Digite novamente" autocomplete="new-password">
            <small id="password-match" style="display:none;margin-top:0.375rem;font-size:0.75rem;"></small>
            @error('password_confirmation')
            <small class="error-message">{{ $message }}</small>
            @enderror
          </div>

          <!-- Data de Nascimento -->
          <div class="form-group">
            <label class="form-label">Data de Nascimento *</label>
            <div class="date-grid">
              <input type="number" name="birth_day" id="birthDay" placeholder="Dia" min="1" max="31" required class="form-input">
              <select name="birth_month" id="birthMonth" required class="form-select">
                <option value="">Mês</option>
                <option value="1">Janeiro</option>
                <option value="2">Fevereiro</option>
                <option value="3">Março</option>
                <option value="4">Abril</option>
                <option value="5">Maio</option>
                <option value="6">Junho</option>
                <option value="7">Julho</option>
                <option value="8">Agosto</option>
                <option value="9">Setembro</option>
                <option value="10">Outubro</option>
                <option value="11">Novembro</option>
                <option value="12">Dezembro</option>
              </select>
              <input type="number" name="birth_year" id="birthYear" placeholder="Ano" min="1900" max="2025" required class="form-input">
            </div>
            <input type="hidden" name="data_de_nascimento" id="fullBirthDate">
            <small id="date-error" style="display:none;color:#ef4444;font-size:0.875rem;margin-top:0.5rem;"></small>
            @error('data_de_nascimento')
            <small class="error-message">{{ $message }}</small>
            @enderror
          </div>

          <!-- Gênero -->
          <div class="form-group">
            <label class="form-label">Gênero *</label>
            <select name="genero" id="genero" required class="form-select">
              <option value="">Selecione</option>
              <option value="masculino">Masculino</option>
              <option value="feminino">Feminino</option>
            </select>
            @error('genero')
            <small class="error-message">{{ $message }}</small>
            @enderror
          </div>

          <!-- Classe -->
          <div class="form-group form-grid-full">
            <label class="form-label">Se você fosse um personagem, qual seria? *</label>
            <select name="classe_personagem" id="classe" required class="form-select">
              <option value="">Escolha sua classe</option>
              <option value="ladino">🗡️ Ladino - Ágil e sorrateiro</option>
              <option value="barbaro">🪓 Bárbaro - Força bruta</option>
              <option value="paladino">🛡️ Paladino - Justiça e honra</option>
              <option value="arqueiro">🏹 Arqueiro - Precisão mortal</option>
              <option value="bardo">🎵 Bardo - Charme e música</option>
              <option value="mago">🔮 Mago - Magia arcana</option>
            </select>
            @error('classe_personagem')
            <small class="error-message">{{ $message }}</small>
            @enderror
          </div>

          <!-- Avatar -->
          <div class="form-group">
            <label class="form-label">Foto de Perfil (Opcional)</label>
            <div class="file-upload-wrapper">
              <input type="file" id="avatar" name="avatar" accept="image/*" class="file-input-hidden">
              <label for="avatar" class="file-upload-button">
                <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <span id="file-name">Escolher arquivo</span>
              </label>
            </div>
            <small class="field-info">JPG, PNG, GIF (máx. 2MB). Se não enviar, usaremos um avatar padrão.</small>
            <div id="avatar-nsfw-alert"></div>
            @error('avatar')
            <small class="error-message">{{ $message }}</small>
            @enderror
          </div>

          <!-- Bio -->
          <div class="form-group form-grid-full">
            <label class="form-label">Bio (opcional)</label>
            <textarea name="bio" id="bio" class="form-input" placeholder="Conte sua história...">{{ old('bio') }}</textarea>
            <small id="bio-warning" style="display:none;color:#ef4444;font-size:0.875rem;">Conteúdo inapropriado detectado</small>
            @error('bio')
            <small class="error-message">{{ $message }}</small>
            @enderror
          </div>
        </div>

        <!-- Termos -->
        <div class="terms-box" id="termsBox">
          <label class="terms-label">
            <input type="checkbox" name="accept_terms" id="acceptTerms" required disabled class="terms-checkbox">
            <span>
              Eu li e concordo com os 
              <a href="#" class="terms-link" id="openTerms">Termos de Serviço</a>
              e a 
              <a href="#" class="terms-link" id="openPrivacy">Política de Privacidade</a>
            </span>
          </label>
          <span class="terms-status" id="termsStatus">⚠️ Você precisa ler os documentos antes</span>
          @error('accept_terms')
          <small class="error-message" style="padding-left:calc(20px + 0.75rem);">{{ $message }}</small>
          @enderror
        </div>

        <button type="submit" class="submit-button" id="submitBtn" disabled>Criar Minha Conta</button>

        <div class="login-link">
          <p>Já tem uma conta?</p>
          <a href="#" class="portal-button" id="portalToLogin">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Fazer Login</span>
          </a>
        </div>
      </form>
    </div>

    <!-- PREVIEW DO PERSONAGEM -->
    <div class="character-preview" id="characterPreview">
      <div class="preview-header">
        <h2 class="preview-title">Seu Personagem</h2>
        <p class="preview-subtitle">Personalize suas escolhas</p>
      </div>

      <div class="character-card" id="characterCard">
        <div class="character-avatar" id="characterAvatar">
          <div class="character-placeholder">?</div>
        </div>

        <div class="character-info">
          <div class="info-item">
            <span class="info-label">Gênero</span>
            <span class="info-value" id="previewGenero">-</span>
          </div>
          <div class="info-item">
            <span class="info-label">Classe</span>
            <span class="info-value" id="previewClasse">-</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Termos de Serviço -->
<div id="termsModal" class="terms-modal">
  <div class="terms-modal-content">
    <div class="terms-modal-header">
      <h2>📜 Termos de Serviço</h2>
      <button class="terms-close" data-modal="termsModal">&times;</button>
    </div>
    <div class="progress-container">
      <div id="termsProgress" class="progress-bar"></div>
    </div>
    <span id="termsProgressText" class="progress-text">0% concluído</span>
    <div id="termsBody" class="terms-modal-body">
      <h3>1. Aceitação dos Termos</h3>
      <p>Ao acessar e usar o Ambience RPG, você concorda em cumprir estes Termos de Serviço. Se você não concordar com alguma parte destes termos, não deverá usar nosso serviço.</p>

      <h3>2. Descrição do Serviço</h3>
      <p>O Ambience RPG é uma plataforma online dedicada a jogos de RPG por texto, onde os usuários podem criar personagens, participar de campanhas e interagir com uma comunidade de jogadores.</p>

      <h3>3. Conta de Usuário</h3>
      <p><strong>Responsabilidade:</strong> Você é responsável por manter a confidencialidade de sua conta e senha.</p>
      <p><strong>Informações Precisas:</strong> Você concorda em fornecer informações precisas e atualizadas durante o registro.</p>

      <h3>4. Conduta do Usuário</h3>
      <p>Ao usar o Ambience RPG, você concorda em <strong>NÃO</strong>:</p>
      <ul>
        <li>Usar linguagem ofensiva, discriminatória ou assediadora</li>
        <li>Compartilhar conteúdo ilegal, explícito ou inadequado</li>
        <li>Fazer spam, trolling ou qualquer comportamento disruptivo</li>
        <li>Tentar hackear, explorar bugs ou comprometer a segurança da plataforma</li>
        <li>Falsificar identidade ou se passar por outros usuários</li>
        <li>Usar bots, scripts automatizados ou ferramentas de terceiros não autorizadas</li>
      </ul>

      <h3>5. Conteúdo do Usuário</h3>
      <p><strong>Propriedade:</strong> Você mantém os direitos sobre o conteúdo que cria (personagens, histórias, etc.).</p>
      <p><strong>Licença:</strong> Ao postar conteúdo, você nos concede uma licença não exclusiva para exibir, armazenar e distribuir esse conteúdo na plataforma.</p>
      <p><strong>Moderação:</strong> Reservamos o direito de remover conteúdo que viole estes termos ou nossas diretrizes da comunidade.</p>

      <h3>6. Suspensão e Banimento</h3>
      <p>Podemos suspender ou encerrar sua conta se você violar estes Termos de Serviço, sem aviso prévio.</p>

      <h3>7. Limitação de Responsabilidade</h3>
      <p>O Ambience RPG é fornecido "como está". Não garantimos que o serviço estará sempre disponível, livre de erros ou seguro contra ataques.</p>

      <h3>8. Modificações nos Termos</h3>
      <p>Podemos atualizar estes Termos de Serviço a qualquer momento. Você será notificado sobre mudanças significativas.</p>

      <h3>9. Lei Aplicável</h3>
      <p>Estes termos são regidos pelas leis do Brasil.</p>

      <h3>10. Contato</h3>
      <p>Se você tiver dúvidas sobre estes Termos de Serviço, entre em contato conosco através do email: <strong>suporte@ambiencerpg.com</strong></p>
    </div>
    <div class="terms-modal-footer">
      <button id="termsConfirmBtn" class="terms-btn-confirm" disabled>Li e Aceito</button>
    </div>
  </div>
</div>

<!-- Modal de Política de Privacidade -->
<div id="privacyModal" class="terms-modal">
  <div class="terms-modal-content">
    <div class="terms-modal-header">
      <h2>🔒 Política de Privacidade</h2>
      <button class="terms-close" data-modal="privacyModal">&times;</button>
    </div>
    <div class="progress-container">
      <div id="privacyProgress" class="progress-bar"></div>
    </div>
    <span id="privacyProgressText" class="progress-text">0% concluído</span>
    <div id="privacyBody" class="terms-modal-body">
      <h3>1. Informações que Coletamos</h3>
      <p><strong>Informações de Registro:</strong></p>
      <ul>
        <li>Nome de usuário</li>
        <li>Endereço de email</li>
        <li>Data de nascimento</li>
        <li>Avatar/foto de perfil (opcional)</li>
      </ul>
      <p><strong>Informações de Uso:</strong></p>
      <ul>
        <li>Endereço IP</li>
        <li>Tipo de navegador</li>
        <li>Páginas visitadas</li>
        <li>Tempo de uso da plataforma</li>
      </ul>

      <h3>2. Como Usamos Suas Informações</h3>
      <p>Usamos suas informações para:</p>
      <ul>
        <li>Fornecer e melhorar nossos serviços</li>
        <li>Personalizar sua experiência</li>
        <li>Comunicar atualizações e notificações importantes</li>
        <li>Garantir a segurança da plataforma</li>
        <li>Cumprir obrigações legais</li>
      </ul>

      <h3>3. Compartilhamento de Informações</h3>
      <p><strong>NÃO vendemos</strong> suas informações pessoais a terceiros.</p>
      <p>Podemos compartilhar informações com:</p>
      <ul>
        <li>Provedores de serviços (hospedagem, email)</li>
        <li>Autoridades legais, quando exigido por lei</li>
      </ul>

      <h3>4. Cookies</h3>
      <p>Usamos cookies para melhorar sua experiência, manter sua sessão ativa e analisar o uso da plataforma.</p>

      <h3>5. Segurança</h3>
      <p>Implementamos medidas de segurança para proteger suas informações, incluindo criptografia de senhas e proteção contra acesso não autorizado.</p>

      <h3>6. Seus Direitos</h3>
      <p>Você tem o direito de:</p>
      <ul>
        <li><strong>Acessar</strong> suas informações pessoais</li>
        <li><strong>Corrigir</strong> dados incorretos</li>
        <li><strong>Excluir</strong> sua conta e dados associados</li>
        <li><strong>Exportar</strong> seus dados</li>
      </ul>

      <h3>7. Alterações nesta Política</h3>
      <p>Podemos atualizar esta Política de Privacidade periodicamente. Notificaremos você sobre mudanças significativas.</p>

      <h3>8. Contato</h3>
      <p>Para questões sobre esta Política de Privacidade ou para exercer seus direitos, entre em contato: <strong>privacidade@ambiencerpg.com</strong></p>

      <p><strong>Última atualização:</strong> Novembro de 2025</p>
    </div>
    <div class="terms-modal-footer">
      <button id="privacyConfirmBtn" class="terms-btn-confirm" disabled>Li e Aceito</button>
    </div>
  </div>
</div>

<!-- Scripts de moderação (carregado do seu sistema) -->
<script src="{{ asset('js/moderation.js') }}"></script>
<script src="{{ asset('js/nsfw-detector.js') }}"></script>

<script>
// ========== SISTEMA DE AFINIDADE (100% = TODOS OS CAMPOS) ==========
const affinity = {
  steps: {
    username: false,
    email: false,
    password: false,
    passwordConfirm: false,
    birthDate: false,
    genero: false,
    classe: false
  },
  
  update() {
    const completed = Object.values(this.steps).filter(Boolean).length;
    const total = Object.keys(this.steps).length;
    const percent = (completed / total) * 100;
    
    const progressFill = document.getElementById('progressFill');
    const affinityPercent = document.getElementById('affinityPercent');
    const affinityBadge = document.getElementById('affinityBadge');
    
    progressFill.style.width = percent + '%';
    affinityPercent.textContent = Math.round(percent) + '%';
    
    if(percent === 100) {
      affinityBadge.classList.add('show');
    } else {
      affinityBadge.classList.remove('show');
    }
  }
};

// ========== GERENCIAMENTO DE ESTADO DO PREVIEW ==========
let previewState = {
  genero: null,
  classe: null,
  hasCustomAvatar: false,
  customAvatarUrl: null
};

// ========== PREVIEW DO PERSONAGEM (SEM ANIMAÇÕES SOBREPOSTAS) ==========
const generoSelect = document.getElementById('genero');
const classeSelect = document.getElementById('classe');
const characterPreview = document.getElementById('characterPreview');
const characterCard = document.getElementById('characterCard');
const characterAvatar = document.getElementById('characterAvatar');
const previewGenero = document.getElementById('previewGenero');
const previewClasse = document.getElementById('previewClasse');
const avatarInput = document.getElementById('avatar');

function updateCharacterInfo() {
  // Atualizar texto de gênero
  if (previewState.genero) {
    previewGenero.textContent = previewState.genero === 'masculino' ? 'Masculino' : 'Feminino';
  } else {
    previewGenero.textContent = '-';
  }
  
  // Atualizar texto de classe
  const classeNames = {
    ladino: 'Ladino',
    barbaro: 'Bárbaro',
    paladino: 'Paladino',
    arqueiro: 'Arqueiro',
    bardo: 'Bardo',
    mago: 'Mago'
  };
  
  if (previewState.classe) {
    previewClasse.textContent = classeNames[previewState.classe];
  } else {
    previewClasse.textContent = '-';
  }
  
  // Atualizar borda do card
  if (previewState.genero && previewState.classe) {
    characterCard.classList.add('active');
    characterAvatar.classList.add('active');
  } else {
    characterCard.classList.remove('active');
    characterAvatar.classList.remove('active');
  }
}

function updateCharacterAvatar() {
  // Se tem avatar customizado, mostrar ele
  if (previewState.hasCustomAvatar && previewState.customAvatarUrl) {
    characterAvatar.classList.add('flipping');
    
    setTimeout(() => {
      characterAvatar.innerHTML = `<img src="${previewState.customAvatarUrl}" alt="Seu avatar">`;
      characterAvatar.classList.remove('flipping');
    }, 300);
    return;
  }
  
  // Se tem gênero E classe, mostrar avatar padrão
  if (previewState.genero && previewState.classe) {
    const defaultAvatar = `/images/avatars/default/${previewState.genero}/${previewState.classe}.png`;
    
    characterAvatar.classList.add('flipping');
    
    setTimeout(() => {
      characterAvatar.innerHTML = `<img src="${defaultAvatar}" alt="${previewState.classe}">`;
      characterAvatar.classList.remove('flipping');
    }, 300);
    return;
  }
  
  // Caso contrário, mostrar placeholder
  if (characterAvatar.querySelector('img')) {
    characterAvatar.classList.add('flipping');
    setTimeout(() => {
      characterAvatar.innerHTML = '<div class="character-placeholder">?</div>';
      characterAvatar.classList.remove('flipping');
    }, 300);
  }
}

// Event listeners para gênero
generoSelect.addEventListener('change', () => {
  const oldGenero = previewState.genero;
  previewState.genero = generoSelect.value || null;
  
  affinity.steps.genero = !!previewState.genero;
  affinity.update();
  
  updateCharacterInfo();
  
  // Só atualiza avatar se mudou
  if (oldGenero !== previewState.genero) {
    updateCharacterAvatar();
  }
  
  // Pulse no card
  characterPreview.classList.add('pulse');
  setTimeout(() => characterPreview.classList.remove('pulse'), 400);
});

// Event listeners para classe
classeSelect.addEventListener('change', () => {
  const oldClasse = previewState.classe;
  previewState.classe = classeSelect.value || null;
  
  affinity.steps.classe = !!previewState.classe;
  affinity.update();
  
  updateCharacterInfo();
  
  // Só atualiza avatar se mudou
  if (oldClasse !== previewState.classe) {
    updateCharacterAvatar();
  }
  
  // Pulse no card
  characterPreview.classList.add('pulse');
  setTimeout(() => characterPreview.classList.remove('pulse'), 400);
});

// ========== UPLOAD DE AVATAR COM MODERAÇÃO NSFW ==========
let nsfwAnalysisResult = null;

avatarInput.addEventListener('change', async function(e) {
  const file = e.target.files[0];
  const fileNameSpan = document.getElementById('file-name');
  const uploadButton = document.querySelector('.file-upload-button');
  const nsfwAlert = document.getElementById('avatar-nsfw-alert');
  
  if (!file) {
    fileNameSpan.textContent = 'Escolher arquivo';
    uploadButton.style.borderColor = '#374151';
    uploadButton.style.background = '#1f2937';
    previewState.hasCustomAvatar = false;
    previewState.customAvatarUrl = null;
    nsfwAlert.style.display = 'none';
    updateCharacterAvatar();
    return;
  }
  
  // Validações básicas
  if (!/image\/(png|jpeg|jpg|gif)/.test(file.type)) {
    alert('❌ Formato inválido. Use PNG, JPG ou GIF.');
    this.value = '';
    return;
  }
  
  if (file.size > 2 * 1024 * 1024) {
    alert('❌ Arquivo muito grande. Máximo 2MB.');
    this.value = '';
    return;
  }
  
  fileNameSpan.textContent = file.name;
  uploadButton.style.borderColor = '#10b981';
  uploadButton.style.background = '#374151';
  
  // Criar URL temporária para preview
  const tempUrl = URL.createObjectURL(file);
  previewState.hasCustomAvatar = true;
  previewState.customAvatarUrl = tempUrl;
  
  // Mostrar loading no alerta
  NSFWAlert.showLoading('avatar-nsfw-alert', 'Analisando imagem...');
  
  try {
    // Analisar imagem com NSFW Detector
    const result = await NSFWDetector.analyze(file);
    nsfwAnalysisResult = result;
    
    // Mostrar resultado
    NSFWAlert.show('avatar-nsfw-alert', result, {
      showClose: false,
      showDetails: false
    });
    
    if (result.isBlocked) {
      // Imagem bloqueada
      this.value = '';
      fileNameSpan.textContent = 'Escolher arquivo';
      uploadButton.style.borderColor = '#ef4444';
      uploadButton.style.background = '#1f2937';
      previewState.hasCustomAvatar = false;
      previewState.customAvatarUrl = null;
      updateCharacterAvatar();
    } else {
      // Imagem aprovada - atualizar preview
      updateCharacterAvatar();
      
      // Pulse no card
      characterPreview.classList.add('pulse');
      setTimeout(() => characterPreview.classList.remove('pulse'), 400);
    }
  } catch (error) {
    console.error('Erro na análise NSFW:', error);
    NSFWAlert.showError('avatar-nsfw-alert', 'Erro ao analisar imagem. A imagem será permitida.');
    nsfwAnalysisResult = null;
    
    // Mesmo com erro, mostrar preview
    updateCharacterAvatar();
  }
});

// ========== MODERAÇÃO DE TEXTO ==========
window.addEventListener('DOMContentLoaded', async () => {
  const state = await window.Moderation.init({
    csrfToken: '{{ csrf_token() }}',
    endpoint: '/moderate',
    debounceMs: 120,
  });

  function applyWarning(elSelector, res) {
    const el = document.querySelector(elSelector);
    const warn = document.querySelector(elSelector + '-warning');
    if (!el) return;
    
    if (res && res.inappropriate) {
      el.classList.add('input-warn');
      if (warn) warn.style.display = 'block';
    } else {
      el.classList.remove('input-warn');
      if (warn) warn.style.display = 'none';
    }
  }

  // Moderar username
  window.Moderation.attachInput('#username', 'username', {
    onLocal: (res) => applyWarning('#username', res),
    onServer: (srv) => {
      if (srv && srv.data && srv.data.inappropriate) {
        applyWarning('#username', { inappropriate: true });
      }
    }
  });

  // Moderar bio
  window.Moderation.attachInput('#bio', 'bio', {
    onLocal: (res) => applyWarning('#bio', res),
    onServer: (srv) => {}
  });

  // Attach no formulário
  window.Moderation.attachFormSubmit('#registerForm', [
    { selector: '#username', fieldName: 'username' },
    { selector: '#bio', fieldName: 'bio' }
  ]);
});

// ========== VALIDAÇÃO DE DATA DE NASCIMENTO ==========
const birthDay = document.getElementById('birthDay');
const birthMonth = document.getElementById('birthMonth');
const birthYear = document.getElementById('birthYear');
const fullBirthDate = document.getElementById('fullBirthDate');
const dateError = document.getElementById('date-error');

function validateDate() {
  const day = parseInt(birthDay.value);
  const month = parseInt(birthMonth.value);
  const year = parseInt(birthYear.value);
  
  if (!day || !month || !year) {
    dateError.style.display = 'none';
    affinity.steps.birthDate = false;
    affinity.update();
    return { valid: false, message: 'Preencha a data completa' };
  }
  
  if (day < 1 || day > 31) {
    dateError.textContent = '✗ Dia inválido (deve ser entre 1 e 31)';
    dateError.style.display = 'block';
    dateError.style.color = '#ef4444';
    affinity.steps.birthDate = false;
    affinity.update();
    return { valid: false, message: 'Dia inválido' };
  }
  
  if (year < 1900 || year > 2025) {
    dateError.textContent = '✗ Ano inválido (deve ser entre 1900 e 2025)';
    dateError.style.display = 'block';
    dateError.style.color = '#ef4444';
    affinity.steps.birthDate = false;
    affinity.update();
    return { valid: false, message: 'Ano inválido' };
  }
  
  const daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
  const isLeapYear = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
  if (isLeapYear) daysInMonth[1] = 29;
  
  if (day > daysInMonth[month - 1]) {
    dateError.textContent = `✗ Dia inválido para ${getMonthName(month)} (máximo ${daysInMonth[month - 1]} dias)`;
    dateError.style.display = 'block';
    dateError.style.color = '#ef4444';
    affinity.steps.birthDate = false;
    affinity.update();
    return { valid: false, message: 'Dia inválido para o mês' };
  }
  
  const today = new Date();
  const birthDate = new Date(year, month - 1, day);
  
  if (birthDate > today) {
    dateError.textContent = '✗ Data de nascimento não pode ser no futuro';
    dateError.style.display = 'block';
    dateError.style.color = '#ef4444';
    affinity.steps.birthDate = false;
    affinity.update();
    return { valid: false, message: 'Data no futuro' };
  }
  
  // Data válida - atualizar campo hidden
  const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
  fullBirthDate.value = formattedDate;
  
  dateError.textContent = '✓ Data de nascimento válida';
  dateError.style.color = '#10b981';
  dateError.style.display = 'block';
  affinity.steps.birthDate = true;
  affinity.update();
  return { valid: true, message: 'Data válida' };
}

function getMonthName(month) {
  const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
                  'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
  return months[month - 1];
}

birthDay.addEventListener('input', validateDate);
birthMonth.addEventListener('change', validateDate);
birthYear.addEventListener('input', validateDate);

// ========== VALIDAÇÃO DE SENHAS ==========
const passwordInput = document.getElementById('password');
const passwordConfirm = document.getElementById('passwordConfirm');
const passwordMatch = document.getElementById('password-match');
const passwordStrength = document.getElementById('password-strength');

function checkPasswordStrength() {
  const password = passwordInput.value;
  
  if (password.length === 0) {
    passwordStrength.textContent = 'Mínimo 8 caracteres com pelo menos 1 letra';
    passwordStrength.style.color = '#9ca3af';
    affinity.steps.password = false;
    affinity.update();
    checkPasswordMatch();
    return false;
  }
  
  if (password.length < 8) {
    passwordStrength.textContent = '✗ Senha muito curta (mínimo 8 caracteres)';
    passwordStrength.style.color = '#ef4444';
    affinity.steps.password = false;
    affinity.update();
    checkPasswordMatch();
    return false;
  }
  
  const hasLetter = /[a-zA-Z]/.test(password);
  if (!hasLetter) {
    passwordStrength.textContent = '✗ A senha deve conter pelo menos 1 letra';
    passwordStrength.style.color = '#ef4444';
    affinity.steps.password = false;
    affinity.update();
    checkPasswordMatch();
    return false;
  }
  
  passwordStrength.textContent = '✓ Senha válida';
  passwordStrength.style.color = '#10b981';
  affinity.steps.password = true;
  affinity.update();
  checkPasswordMatch();
  return true;
}

function checkPasswordMatch() {
  const password = passwordInput.value;
  const confirmation = passwordConfirm.value;
  
  if (confirmation.length === 0) {
    passwordMatch.style.display = 'none';
    affinity.steps.passwordConfirm = false;
    affinity.update();
    return false;
  }
  
  passwordMatch.style.display = 'block';
  
  const passwordValid = password.length >= 8 && /[a-zA-Z]/.test(password);
  
  if (password === confirmation && passwordValid) {
    passwordMatch.textContent = '✓ As senhas coincidem';
    passwordMatch.style.color = '#10b981';
    passwordConfirm.style.borderColor = '#10b981';
    affinity.steps.passwordConfirm = true;
    affinity.update();
    return true;
  } else if (password !== confirmation) {
    passwordMatch.textContent = '✗ As senhas não coincidem';
    passwordMatch.style.color = '#ef4444';
    passwordConfirm.style.borderColor = '#ef4444';
    affinity.steps.passwordConfirm = false;
    affinity.update();
    return false;
  } else if (!passwordValid) {
    passwordMatch.textContent = '✗ A senha não atende aos requisitos';
    passwordMatch.style.color = '#ef4444';
    passwordConfirm.style.borderColor = '#ef4444';
    affinity.steps.passwordConfirm = false;
    affinity.update();
    return false;
  }
}

// Validação do username e email para afinidade
const usernameInput = document.getElementById('username');
const emailInput = document.getElementById('email');

usernameInput.addEventListener('input', () => {
  affinity.steps.username = usernameInput.value.length >= 3;
  affinity.update();
});

emailInput.addEventListener('input', () => {
  affinity.steps.email = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
  affinity.update();
});

passwordInput.addEventListener('input', checkPasswordStrength);
passwordConfirm.addEventListener('input', checkPasswordMatch);

// ========== GERENCIAMENTO DOS MODAIS ==========
const termsState = {
  termsRead: false,
  privacyRead: false
};

const termsModal = document.getElementById('termsModal');
const privacyModal = document.getElementById('privacyModal');
const openTermsBtn = document.getElementById('openTerms');
const openPrivacyBtn = document.getElementById('openPrivacy');
const acceptCheckbox = document.getElementById('acceptTerms');
const submitBtn = document.getElementById('submitBtn');
const termsStatus = document.getElementById('termsStatus');
const termsBox = document.getElementById('termsBox');

// Termos
const termsContent = document.getElementById('termsBody');
const termsProgress = document.getElementById('termsProgress');
const termsProgressText = document.getElementById('termsProgressText');
const termsConfirmBtn = document.getElementById('termsConfirmBtn');

termsContent.addEventListener('scroll', () => {
  const scrollTop = termsContent.scrollTop;
  const scrollHeight = termsContent.scrollHeight - termsContent.clientHeight;
  const scrollPercent = (scrollTop / scrollHeight) * 100;
  
  termsProgress.style.width = scrollPercent + '%';
  termsProgressText.textContent = `Role até o final para continuar (${Math.round(scrollPercent)}%)`;
  
  if (scrollPercent >= 95) {
    termsConfirmBtn.disabled = false;
    termsProgressText.textContent = '✓ Você chegou ao final! Clique em "Li e Aceito"';
    termsProgressText.style.color = '#10b981';
  }
});

// Privacidade
const privacyContent = document.getElementById('privacyBody');
const privacyProgress = document.getElementById('privacyProgress');
const privacyProgressText = document.getElementById('privacyProgressText');
const privacyConfirmBtn = document.getElementById('privacyConfirmBtn');

privacyContent.addEventListener('scroll', () => {
  const scrollTop = privacyContent.scrollTop;
  const scrollHeight = privacyContent.scrollHeight - privacyContent.clientHeight;
  const scrollPercent = (scrollTop / scrollHeight) * 100;
  
  privacyProgress.style.width = scrollPercent + '%';
  privacyProgressText.textContent = `Role até o final para continuar (${Math.round(scrollPercent)}%)`;
  
  if (scrollPercent >= 95) {
    privacyConfirmBtn.disabled = false;
    privacyProgressText.textContent = '✓ Você chegou ao final! Clique em "Li e Aceito"';
    privacyProgressText.style.color = '#10b981';
  }
});

openTermsBtn.addEventListener('click', (e) => {
  e.preventDefault();
  termsModal.style.display = 'block';
  document.body.style.overflow = 'hidden';
});

openPrivacyBtn.addEventListener('click', (e) => {
  e.preventDefault();
  privacyModal.style.display = 'block';
  document.body.style.overflow = 'hidden';
});

document.querySelectorAll('.terms-close').forEach(btn => {
  btn.addEventListener('click', () => {
    const modalId = btn.dataset.modal;
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
  });
});

window.addEventListener('click', (e) => {
  if (e.target === termsModal) {
    termsModal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }
  if (e.target === privacyModal) {
    privacyModal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }
});

termsConfirmBtn.addEventListener('click', () => {
  termsState.termsRead = true;
  termsModal.style.display = 'none';
  document.body.style.overflow = 'auto';
  updateTermsStatus();
});

privacyConfirmBtn.addEventListener('click', () => {
  termsState.privacyRead = true;
  privacyModal.style.display = 'none';
  document.body.style.overflow = 'auto';
  updateTermsStatus();
});

function updateTermsStatus() {
  if (termsState.termsRead && termsState.privacyRead) {
    acceptCheckbox.disabled = false;
    termsStatus.textContent = '✓ Você leu todos os documentos. Marque a caixa para continuar.';
    termsStatus.style.color = '#10b981';
    termsBox.classList.add('active');
  } else if (termsState.termsRead && !termsState.privacyRead) {
    termsStatus.textContent = '⚠️ Você ainda precisa ler a Política de Privacidade';
    termsStatus.style.color = '#f59e0b';
  } else if (!termsState.termsRead && termsState.privacyRead) {
    termsStatus.textContent = '⚠️ Você ainda precisa ler os Termos de Serviço';
    termsStatus.style.color = '#f59e0b';
  }
}

acceptCheckbox.addEventListener('change', () => {
  submitBtn.disabled = !acceptCheckbox.checked;
});

// ========== PORTAL DE VOLTA AO LOGIN ==========
const portalToLogin = document.getElementById('portalToLogin');
const portalContainer = document.getElementById('portalContainer');

portalToLogin.addEventListener('click', (e) => {
  e.preventDefault();
  portalContainer.classList.add('closing');
  
  setTimeout(() => {
    window.location.href = '{{ route("usuarios.login") }}';
  }, 600);
});

// ========== SUBMIT DO FORMULÁRIO ==========
document.getElementById('registerForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  // Validar data
  const dateValidation = validateDate();
  if (!dateValidation.valid) {
    alert('❌ ' + dateValidation.message);
    birthDay.focus();
    return;
  }
  
  // Validar senhas
  const password = passwordInput.value;
  const confirmation = passwordConfirm.value;
  
  if (password !== confirmation) {
    alert('❌ As senhas não coincidem.');
    passwordConfirm.focus();
    return;
  }
  
  if (password.length < 8 || !/[a-zA-Z]/.test(password)) {
    alert('❌ Senha inválida. Mínimo 8 caracteres com pelo menos 1 letra.');
    passwordInput.focus();
    return;
  }
  
  // Verificar termos
  if (!acceptCheckbox.checked) {
    alert('❌ Você precisa aceitar os Termos de Serviço e a Política de Privacidade.');
    return;
  }
  
  // Verificar moderação
  const hasInappropriate = document.querySelector('.input-warn');
  if (hasInappropriate) {
    alert('❌ Conteúdo inapropriado detectado. Corrija os campos marcados.');
    return;
  }
  
  // Verificar NSFW na imagem
  if (nsfwAnalysisResult && nsfwAnalysisResult.isBlocked) {
    alert('❌ A imagem foi bloqueada por conter conteúdo inapropriado. Escolha outra imagem.');
    return;
  }
  
  submitBtn.disabled = true;
  submitBtn.textContent = 'Criando sua lenda...';
  
  try {
    const form = e.target;
    const formData = new FormData(form);
    
    const response = await fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    });
    
    if (response.redirected) {
      window.location.href = response.url;
      return;
    }
    
    const contentType = response.headers.get('content-type');
    if (contentType && contentType.includes('application/json')) {
      const data = await response.json();
      
      if (response.ok && data.success) {
        window.location.href = data.redirect || '{{ route("home") }}';
      } else {
        alert(data.message || 'Erro ao criar conta.');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Criar Minha Conta';
      }
    } else {
      const text = await response.text();
      console.error('Resposta não-JSON:', text);
      alert('❌ Erro inesperado. Verifique o console.');
      submitBtn.disabled = false;
      submitBtn.textContent = 'Criar Minha Conta';
    }
  } catch (error) {
    console.error('Erro:', error);
    alert('❌ Erro de rede. Tente novamente.');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Criar Minha Conta';
  }
});

// ========== RESTAURAR VALORES OLD() ==========
const oldDate = '{{ old("data_de_nascimento") }}';
if (oldDate) {
  const [year, month, day] = oldDate.split('-');
  birthDay.value = parseInt(day);
  birthMonth.value = parseInt(month);
  birthYear.value = year;
  validateDate();
}

const oldGenero = '{{ old("genero") }}';
if (oldGenero) {
  generoSelect.value = oldGenero;
  previewState.genero = oldGenero;
  affinity.steps.genero = true;
  updateCharacterInfo();
  updateCharacterAvatar();
}

const oldClasse = '{{ old("classe_personagem") }}';
if (oldClasse) {
  classeSelect.value = oldClasse;
  previewState.classe = oldClasse;
  affinity.steps.classe = true;
  updateCharacterInfo();
  updateCharacterAvatar();
}

// Atualizar afinidade inicial
affinity.update();
</script>
</body>
</html>