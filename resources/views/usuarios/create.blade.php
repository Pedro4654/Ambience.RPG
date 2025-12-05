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
  --bg-dark: #0a0f14;
  --card: #1f2937;
  --muted: #8b9ba8;
  --accent: #22c55e;
  --accent-light: #16a34a;
  --accent-dark: #15803d;
  --text-primary: #e6eef6;
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
  background:rgba(17, 24, 39, 0);
  backdrop-filter:blur(20px);
  border-radius:24px;
  padding:2.5rem;
  box-shadow:0 20px 60px rgba(0,0,0,0.5),0 0 1px rgba(34,197,94,0.2);
  border:1px solid rgba(34,197,94,0.1);
  position:relative;
  overflow:hidden;
  z-index: 0;
}

.register-card::before{
  content:'';
  position:absolute;
  top:-50%;
  left:-50%;
  width:200%;
  height:200%;
  background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
  animation:glowPulse 4s ease-in-out infinite;
  pointer-events:none;
  z-index: -1;
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
  height:100px;
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
  background: #0e1422ff;
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
  background: #0e1422ff;
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
  background: #0e1422ff !important;
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
  background: #020617; 
  border: 1px solid #4b5563;
  border-radius:12px;
  padding:1.25rem;
  margin:1.5rem 0;
  transition:all 0.3s;
  box-shadow: 0 0 0 1px rgba(15,23,42,0.9);
}

.terms-box.active{
  background: rgba(34,197,94,0.06);
  border-color: var(--accent);
}

.terms-label{
  display:flex;
  align-items:flex-start;
  gap:0.75rem;
  cursor:pointer;
  color: #e5e7eb; 
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
  color: #fbbf24; 
  padding-left:calc(20px + 0.75rem);
  font-weight: 500;
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
  margin-top:2rem;
  animation:floatIn 0.6s ease 0.7s backwards;
}

/* mesmo efeito de glow do login */
.submit-button::before{
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

/* animação só quando não estiver desabilitado */
.submit-button:not(:disabled):hover::before{
  width:300px;
  height:300px;
}

.submit-button:disabled{
  opacity:0.5;
  cursor:not-allowed;
  transform:none!important;
}

/* hover e active iguais ao login */
.submit-button:not(:disabled):hover{
  transform:translateY(-4px);
  box-shadow:0 8px 20px rgba(34,197,94,0.5);
}

.submit-button:not(:disabled):active{
  transform:translateY(-2px);
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
  background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
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
  background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
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
  background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
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

/* Demo buttons */
        .demo-btn {
            padding: 16px 32px;
            background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
            color: #052e16;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
        }

        .demo-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        /* ========== MODAIS MODERNIZADOS ========== */
        .terms-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .terms-modal.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        .terms-modal-content {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            margin: 2% auto;
            width: 92%;
            max-width: 900px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            max-height: 90vh;
            animation: slideUp 0.4s ease;
            overflow: hidden;
        }

        .terms-modal-header {
            padding: 28px 36px;
            background: linear-gradient(135deg, var(--btn-gradient-start) 0%, var(--btn-gradient-end) 100%);
            color: var(--accent);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid rgba(34, 197, 94, 0.3);
        }

        .terms-modal-header h2 {
            margin: 0;
            font-family: Montserrat, sans-serif;
            font-size: 28px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .terms-close {
            background: var(--card);
            border: 2px solid rgba(5, 46, 22, 0.3);
            color: var(--accent);
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .terms-close:hover {
            background: var(--card);
            transform: scale(1.1);
            color: var(--accent);
        }

        .progress-container {
            background: rgba(10, 15, 20, 0.6);
            height: 8px;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--btn-gradient-start) 0%, var(--btn-gradient-end) 100%);
            width: 0%;
            transition: width 0.2s ease;
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
        }

        .progress-text {
            display: block;
            padding: 12px 36px;
            color: var(--accent);
            font-size: 13px;
            font-weight: 600;
            background: rgba(10, 15, 20, 0.4);
            border-bottom: 1px solid rgba(34, 197, 94, 0.1);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .terms-modal-body {
            padding: 36px;
            overflow-y: auto;
            flex: 1;
            line-height: 1.8;
            color: var(--text-on-primary);
            background: linear-gradient(145deg, rgba(10, 15, 20, 0.3), rgba(20, 28, 35, 0.3));
        }

        /* Scrollbar customizada */
        .terms-modal-body::-webkit-scrollbar {
            width: 10px;
        }

        .terms-modal-body::-webkit-scrollbar-track {
            background: rgba(10, 15, 20, 0.6);
            border-radius: 10px;
        }

        .terms-modal-body::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--btn-gradient-start), var(--btn-gradient-end));
            border-radius: 10px;
            border: 2px solid rgba(10, 15, 20, 0.6);
        }

        .terms-modal-body::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--btn-gradient-end), var(--btn-gradient-start));
        }

        .terms-modal-body h3 {
            color: var(--accent);
            margin-top: 32px;
            margin-bottom: 16px;
            font-family: Montserrat, sans-serif;
            font-size: 22px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(34, 197, 94, 0.2);
        }

        .terms-modal-body h3:first-child {
            margin-top: 0;
        }

        .terms-modal-body h3::before {
            content: "▸";
            color: var(--accent);
            font-size: 24px;
        }

        .terms-modal-body p {
            margin-bottom: 16px;
            color: var(--text-on-primary);
            font-size: 15px;
        }

        .terms-modal-body ul,
        .terms-modal-body ol {
            margin-left: 24px;
            margin-bottom: 16px;
            color: var(--text-on-primary);
        }

        .terms-modal-body li {
            margin-bottom: 10px;
            padding-left: 8px;
            position: relative;
        }

        .terms-modal-body ul li::marker {
            color: var(--accent);
        }

        .terms-modal-body strong {
            color: #fff;
            font-weight: 700;
        }

        .terms-modal-footer {
            padding: 24px 36px;
            background: rgba(10, 15, 20, 0.6);
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid var(--border-color);
            gap: 16px;
        }

        .terms-btn-confirm {
            background: var(--accent);
            color: var (--accent);
            border: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .terms-btn-confirm:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.5);
        }

        .terms-btn-confirm:disabled {
            background: linear-gradient(to right, #4b5563, #374151);
            color: #9ca3af;
            cursor: not-allowed;
            opacity: 0.5;
            box-shadow: none;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .terms-modal-content {
                width: 95%;
                max-height: 95vh;
                margin: 2.5% auto;
            }

            .terms-modal-header {
                padding: 20px 24px;
            }

            .terms-modal-header h2 {
                font-size: 22px;
            }

            .terms-modal-body {
                padding: 24px;
            }

            .terms-modal-body h3 {
                font-size: 18px;
            }

            .terms-modal-footer {
                padding: 20px 24px;
            }

            .progress-text,
            .terms-modal-body {
                padding-left: 24px;
                padding-right: 24px;
            }
        }

        @media (max-width: 480px) {
            .terms-modal-header h2 {
                font-size: 18px;
            }

            .terms-close {
                width: 36px;
                height: 36px;
                font-size: 24px;
            }

            .terms-btn-confirm {
                padding: 14px 28px;
                font-size: 14px;
            }
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
                <h2> Termos de Serviço</h2>
                <button class="terms-close" data-modal="termsModal" onclick="closeModal('termsModal')">&times;</button>
            </div>
            <div class="progress-container">
                <div id="termsProgress" class="progress-bar"></div>
            </div>
            <span id="termsProgressText" class="progress-text">0% concluído</span>
            <div id="termsBody" class="terms-modal-body">
            <h3>1. Aceitação dos Termos</h3>
<p>Ao acessar e usar a plataforma Ambience RPG ("Plataforma", "nós" ou "nosso"), você ("Usuário" ou "você") concorda em cumprir estes Termos de Serviço em sua totalidade. Se você não concordar com qualquer parte destes termos, não deverá usar nosso serviço. O uso continuado da Plataforma após alterações nestes Termos constitui aceitação das modificações.</p>

<h3>2. Elegibilidade e Idade Mínima</h3>
<p><strong>Maioridade:</strong> O acesso à Ambience RPG é permitido prioritariamente a usuários maiores de 18 anos. Se você tem entre 13 e 17 anos, pode utilizar a Plataforma apenas com autorização expressa de seus pais ou responsáveis legais, em conformidade com o Estatuto da Criança e do Adolescente (Lei nº 8.069/1990).</p>
<p><strong>Menores de 13 anos:</strong> Crianças menores de 13 anos têm acesso restrito à Plataforma. Elas podem utilizar funcionalidades limitadas apenas sob supervisão direta de pais ou responsáveis, com censura automática ativada no chat e conteúdo. O uso de contas por menores depende do consentimento dos responsáveis, observando-se sempre o melhor interesse da criança.</p>
<p><strong>Responsabilidade dos pais:</strong> Pais ou responsáveis que autorizarem o uso da Plataforma por menores são responsáveis por supervisionar as atividades e garantir o uso adequado dos serviços.</p>

<h3>3. Descrição do Serviço</h3>
<p>A Ambience RPG é uma plataforma online gratuita dedicada a jogos de RPG por texto, onde os usuários podem:</p>
<ul>
    <li>Criar e personalizar personagens com fichas detalhadas</li>
    <li>Participar de campanhas e aventuras colaborativas</li>
    <li>Interagir com uma comunidade de jogadores e mestres</li>
    <li>Criar e compartilhar conteúdo original (mapas, histórias, cenários)</li>
    <li>Utilizar ferramentas de rolagem de dados e gerenciamento de sessões</li>
    <li>Fazer upload de arquivos relevantes (PDFs, imagens, documentos)</li>
</ul>
<p>A Plataforma é oferecida gratuitamente, sem anúncios ou monetização de dados de usuários. Reservamo-nos o direito de modificar, suspender ou descontinuar qualquer funcionalidade a qualquer momento, com ou sem aviso prévio.</p>

<h3>4. Conta de Usuário e Cadastro</h3>
<p><strong>Criação de conta:</strong> Para utilizar a Plataforma, você deve criar uma conta fornecendo informações precisas, completas e atualizadas, incluindo nome de usuário, email válido, data de nascimento e senha segura.</p>
<p><strong>Responsabilidade pela conta:</strong> Você é o único responsável por manter a confidencialidade de suas credenciais de acesso (nome de usuário e senha). Qualquer atividade realizada através de sua conta será considerada de sua responsabilidade.</p>
<p><strong>Segurança:</strong> Você deve notificar imediatamente a Ambience RPG caso suspeite de uso não autorizado de sua conta ou qualquer violação de segurança.</p>
<p><strong>Unicidade:</strong> Cada usuário pode manter apenas uma conta ativa. Contas duplicadas ou falsas serão removidas.</p>
<p><strong>Veracidade:</strong> É proibido fornecer informações falsas, usar identidade de terceiros ou criar perfis que violem os direitos de outras pessoas.</p>

<h3>5. Uso Aceitável e Conduta do Usuário</h3>
<p>Ao usar a Ambience RPG, você concorda em utilizar a Plataforma de forma lícita, ética e em conformidade com a legislação brasileira. Você concorda expressamente em <strong>NÃO</strong>:</p>

<p><strong>Conteúdo Proibido:</strong></p>
<ul>
    <li>Publicar, transmitir ou compartilhar conteúdo ilegal, ofensivo, difamatório, obsceno, pornográfico, violento ou que viole direitos de terceiros</li>
    <li>Divulgar material que promova discriminação, racismo, xenofobia, homofobia, intolerância religiosa ou qualquer forma de preconceito</li>
    <li>Compartilhar conteúdo que incite violência, ódio, automutilação ou atividades ilegais</li>
    <li>Publicar informações privadas de terceiros sem autorização (doxxing)</li>
    <li>Distribuir spam, propaganda não solicitada ou correntes</li>
</ul>

<p><strong>Comportamento Proibido:</strong></p>
<ul>
    <li>Assediar, intimidar, ameaçar ou perseguir outros usuários</li>
    <li>Praticar trolling, provocações maliciosas ou comportamento disruptivo</li>
    <li>Falsificar identidade ou se passar por outros usuários, moderadores ou membros da equipe</li>
    <li>Manipular sistemas de votação, curtidas ou qualquer métrica da Plataforma</li>
    <li>Criar ou participar de esquemas para explorar vulnerabilidades do sistema</li>
</ul>

<p><strong>Segurança e Integridade:</strong></p>
<ul>
    <li>Tentar hackear, invadir ou comprometer a segurança da Plataforma</li>
    <li>Usar exploits, bugs ou falhas do sistema para obter vantagens indevidas</li>
    <li>Realizar engenharia reversa, descompilar ou desmontar o software da Plataforma</li>
    <li>Usar bots, scrapers, crawlers ou ferramentas automatizadas sem autorização prévia por escrito</li>
    <li>Sobrecarregar propositalmente os servidores ou infraestrutura da Plataforma (ataques DDoS)</li>
    <li>Coletar dados de outros usuários sem consentimento explícito</li>
</ul>

<p><strong>Atividades Comerciais:</strong></p>
<ul>
    <li>Usar a Plataforma para fins comerciais sem autorização expressa</li>
    <li>Vender, revender ou explorar comercialmente qualquer parte do serviço</li>
    <li>Solicitar ou coletar informações financeiras de outros usuários</li>
</ul>

<h3>6. Conteúdo do Usuário e Propriedade Intelectual</h3>
<p><strong>Seus direitos:</strong> Você mantém todos os direitos de propriedade intelectual sobre o conteúdo original que criar na Plataforma (personagens, histórias, aventuras, mapas, imagens, textos, etc.).</p>

<p><strong>Licença concedida:</strong> Ao publicar conteúdo na Ambience RPG, você nos concede uma licença mundial, não exclusiva, gratuita, sublicenciável e transferível para usar, reproduzir, distribuir, preparar obras derivadas, exibir e executar esse conteúdo em conexão com a operação da Plataforma e nossos negócios relacionados.</p>

<p><strong>Responsabilidade pelo conteúdo:</strong> Você é o único responsável pelo conteúdo que publica e garante que:</p>
<ul>
    <li>Possui todos os direitos necessários sobre o material compartilhado</li>
    <li>O conteúdo não viola direitos autorais, marcas registradas ou outros direitos de propriedade intelectual de terceiros</li>
    <li>O conteúdo não viola nenhuma lei ou regulamento aplicável</li>
    <li>O conteúdo não contém vírus, malware ou código malicioso</li>
</ul>

<p><strong>Uso de material protegido:</strong> O uso de material protegido por direitos autorais de terceiros sem a devida autorização é estritamente proibido pela Lei de Direitos Autorais (Lei nº 9.610/1998) e pode resultar em sanções civis e criminais.</p>

<p><strong>Moderação de conteúdo:</strong> A Ambience RPG reserva-se o direito de revisar, editar, recusar ou remover qualquer conteúdo que viole estes Termos, nossas diretrizes da comunidade ou a legislação aplicável, sem aviso prévio.</p>

<p><strong>Backup de conteúdo:</strong> Recomendamos fortemente que você mantenha cópias de backup de todo conteúdo importante criado na Plataforma. Não nos responsabilizamos por perda de dados devido a falhas técnicas, exclusões acidentais ou encerramento de contas.</p>

<h3>7. Sistema de Moderação e Sanções</h3>
<p>A Ambience RPG mantém um sistema de moderação para garantir um ambiente seguro e respeitoso para todos os usuários. Violações destes Termos ou das leis aplicáveis podem resultar nas seguintes sanções, aplicadas de forma progressiva conforme a gravidade e reincidência:</p>

<p><strong>Níveis de sanção:</strong></p>
<ul>
    <li><strong>Advertência:</strong> Notificação formal por escrito sobre a violação, com orientação para correção do comportamento</li>
    <li><strong>Remoção de conteúdo:</strong> Exclusão de publicações, comentários ou materiais que violem os Termos</li>
    <li><strong>Suspensão temporária:</strong> Bloqueio de acesso à conta por período determinado (de 24 horas a 30 dias)</li>
    <li><strong>Banimento permanente:</strong> Encerramento definitivo da conta e proibição de criar novas contas</li>
    <li><strong>Banimento de IP:</strong> Bloqueio do endereço IP em casos de violações graves ou reincidentes</li>
    <li><strong>Reporte às autoridades:</strong> Comunicação às autoridades competentes em casos de crimes (pornografia infantil, ameaças graves, etc.)</li>
</ul>

<p><strong>Processo de recurso:</strong> Se você discordar de uma sanção aplicada, pode apresentar recurso formal enviando email para o suporte da Plataforma dentro de 15 dias corridos após a notificação. Seu recurso será analisado pela equipe de moderação, e você receberá resposta em até 15 dias úteis.</p>

<p><strong>Violações graves:</strong> Determinados comportamentos podem resultar em banimento imediato sem advertência prévia, incluindo mas não limitado a: compartilhamento de pornografia infantil, ameaças de morte, doxxing, ataques coordenados à Plataforma, ou qualquer atividade criminosa.</p>

<h3>8. Proteção de Dados Pessoais (LGPD)</h3>
<p>A Ambience RPG trata dados pessoais em conformidade com a Lei Geral de Proteção de Dados Pessoais (Lei nº 13.709/2018 - LGPD), aplicando os princípios de finalidade, necessidade, adequação, transparência, segurança, prevenção e não discriminação.</p>

<p><strong>Dados coletados:</strong> Coletamos apenas os dados estritamente necessários para fornecer nossos serviços, incluindo informações de cadastro, perfil, conteúdo criado e logs de uso.</p>

<p><strong>Finalidade:</strong> Seus dados são utilizados exclusivamente para operar a Plataforma, melhorar a experiência do usuário, garantir segurança e cumprir obrigações legais. Não vendemos, alugamos ou compartilhamos seus dados para fins comerciais ou de marketing.</p>

<p><strong>Armazenamento:</strong> Todos os dados são armazenados em servidores localizados no Brasil, sem transferência internacional.</p>

<p><strong>Seus direitos:</strong> Você tem direito de acessar, corrigir, atualizar, portar e solicitar a exclusão de seus dados pessoais a qualquer momento. Para detalhes completos sobre tratamento de dados, consulte nossa Política de Privacidade.</p>

<h3>9. Exclusão de Conta e Cancelamento</h3>
<p><strong>Solicitação de exclusão:</strong> Você pode solicitar a exclusão de sua conta a qualquer momento através das configurações de perfil ou entrando em contato com o suporte.</p>

<p><strong>Processo:</strong> Após a solicitação de exclusão:</p>
<ul>
    <li>Sua conta será desativada imediatamente</li>
    <li>Seus dados pessoais serão permanentemente removidos em até 30 dias</li>
    <li>Conteúdo público que você criou pode permanecer na Plataforma de forma anônima, caso outros usuários tenham interagido com ele</li>
    <li>Logs de sistema essenciais para segurança e auditoria serão mantidos pelo período legal exigido</li>
</ul>

<p><strong>Exclusão por violação:</strong> A Ambience RPG pode encerrar sua conta imediatamente, sem aviso prévio, em caso de violação grave destes Termos.</p>

<p><strong>Efeitos da exclusão:</strong> Após a exclusão, você perderá acesso a todo conteúdo, progressos e dados associados à conta. Esta ação é irreversível.</p>

<h3>10. Segurança da Plataforma</h3>
<p>Implementamos medidas técnicas e organizacionais razoáveis para proteger a Plataforma e os dados dos usuários, incluindo:</p>
<ul>
    <li>Criptografia de senhas usando algoritmos seguros (bcrypt ou superior)</li>
    <li>Proteção contra ataques comuns (SQL injection, XSS, CSRF)</li>
    <li>Monitoramento de atividades suspeitas através de logs de sistema</li>
    <li>Controles de acesso interno restritivo</li>
    <li>Backups regulares para recuperação de desastres</li>
</ul>

<p><strong>Limitações:</strong> Apesar de nossos esforços, nenhum sistema é 100% seguro. Você reconhece que não podemos garantir a segurança absoluta contra acessos não autorizados, falhas técnicas, vírus ou ataques de terceiros.</p>

<p><strong>Responsabilidade do usuário:</strong> Você deve manter seus dispositivos, navegadores e softwares atualizados, usar senhas fortes e únicas, e reportar imediatamente qualquer atividade suspeita.</p>

<h3>11. Limitação de Responsabilidade</h3>
<p><strong>Serviço "como está":</strong> A Ambience RPG é fornecida gratuitamente, "como está" e "conforme disponível", sem garantias de qualquer tipo, expressas ou implícitas.</p>

<p><strong>Exclusões:</strong> Na máxima extensão permitida por lei, a Ambience RPG não será responsável por:</p>
<ul>
    <li>Danos indiretos, incidentais, especiais, consequenciais ou punitivos</li>
    <li>Perda de lucros, receitas, dados, uso, reputação ou outras perdas intangíveis</li>
    <li>Interrupções, erros, bugs ou indisponibilidade do serviço</li>
    <li>Perda ou corrupção de conteúdo criado por usuários</li>
    <li>Ações, conteúdo ou conduta de terceiros na Plataforma</li>
    <li>Acesso não autorizado, alteração ou uso de suas transmissões ou conteúdo</li>
    <li>Falhas de terceiros (provedores de hospedagem, internet, etc.)</li>
</ul>

<p><strong>Conteúdo de terceiros:</strong> Não nos responsabilizamos pelo conteúdo gerado ou compartilhado por usuários. Cada usuário é individualmente responsável por seu material.</p>

<p><strong>Indenização:</strong> Você concorda em indenizar, defender e isentar a Ambience RPG, seus diretores, funcionários e parceiros de quaisquer reivindicações, danos, obrigações, perdas, responsabilidades, custos ou dívidas resultantes de: (a) seu uso da Plataforma; (b) violação destes Termos; (c) violação de direitos de terceiros.</p>

<h3>12. Propriedade Intelectual da Plataforma</h3>
<p>Todos os direitos de propriedade intelectual sobre a Plataforma, incluindo mas não limitado a software, código-fonte, design, marca "Ambience RPG", logotipos, gráficos, ícones, textos e layout, pertencem exclusivamente à Ambience RPG ou aos seus licenciadores.</p>

<p><strong>Uso proibido:</strong> É expressamente proibido copiar, modificar, distribuir, vender, alugar, licenciar ou criar obras derivadas de qualquer parte da Plataforma sem autorização prévia por escrito.</p>

<p><strong>Marcas registradas:</strong> "Ambience RPG" e outros nomes, logotipos e marcas associadas são propriedade exclusiva da Plataforma e não podem ser usados sem permissão.</p>

<h3>13. Modificações nos Termos</h3>
<p>Reservamo-nos o direito de modificar estes Termos de Serviço a qualquer momento. Quando fizermos alterações significativas, notificaremos você através de:</p>
<ul>
    <li>Aviso destacado na Plataforma</li>
    <li>Email para o endereço cadastrado</li>
    <li>Notificação no painel do usuário</li>
</ul>

<p><strong>Aceitação de mudanças:</strong> Seu uso continuado da Plataforma após a publicação de alterações constitui aceitação dos novos Termos. Se você não concordar com as modificações, deve descontinuar o uso da Plataforma e excluir sua conta.</p>

<p><strong>Versão vigente:</strong> A versão mais recente destes Termos sempre estará disponível na Plataforma, com data da última atualização claramente indicada.</p>

<h3>14. Links para Sites de Terceiros</h3>
<p>A Plataforma pode conter links para sites ou serviços de terceiros que não são de propriedade ou controlados pela Ambience RPG. Não temos controle sobre e não assumimos responsabilidade pelo conteúdo, políticas de privacidade ou práticas de sites ou serviços de terceiros. Você reconhece e concorda que não seremos responsáveis, direta ou indiretamente, por quaisquer danos causados pelo uso de tais sites ou serviços.</p>

<h3>15. Rescisão e Suspensão</h3>
<p>Podemos suspender ou encerrar seu acesso à Plataforma imediatamente, sem aviso prévio ou responsabilidade, por qualquer motivo, incluindo violação destes Termos. Todas as disposições destes Termos que, por sua natureza, devam sobreviver à rescisão, sobreviverão, incluindo disposições sobre propriedade, isenções de garantia, indenização e limitações de responsabilidade.</p>

<h3>16. Disposições Gerais</h3>
<p><strong>Acordo completo:</strong> Estes Termos constituem o acordo completo entre você e a Ambience RPG sobre o uso da Plataforma, substituindo quaisquer acordos anteriores.</p>

<p><strong>Divisibilidade:</strong> Se qualquer disposição destes Termos for considerada inválida ou inexequível, as disposições restantes permanecerão em pleno vigor e efeito.</p>

<p><strong>Renúncia:</strong> A falha em exercer ou fazer cumprir qualquer direito ou disposição destes Termos não constituirá renúncia a tal direito ou disposição.</p>

<p><strong>Cessão:</strong> Você não pode transferir ou ceder seus direitos ou obrigações sob estes Termos sem nosso consentimento prévio por escrito. Podemos livremente ceder nossos direitos sob estes Termos.</p>

<p><strong>Comunicações:</strong> Você concorda em receber comunicações eletrônicas da Ambience RPG relacionadas ao uso da Plataforma.</p>

<h3>17. Legislação Aplicável e Foro</h3>
<p>Estes Termos de Serviço são regidos e interpretados de acordo com as leis da República Federativa do Brasil, especialmente:</p>
<ul>
    <li>Lei Geral de Proteção de Dados Pessoais (Lei nº 13.709/2018 - LGPD)</li>
    <li>Marco Civil da Internet (Lei nº 12.965/2014)</li>
    <li>Código de Defesa do Consumidor (Lei nº 8.078/1990)</li>
    <li>Estatuto da Criança e do Adolescente (Lei nº 8.069/1990)</li>
    <li>Lei de Direitos Autorais (Lei nº 9.610/1998)</li>
</ul>

<p><strong>Resolução de disputas:</strong> Quaisquer disputas, controvérsias ou reivindicações decorrentes destes Termos serão resolvidas amigavelmente sempre que possível. Caso não seja possível resolução amigável, as partes elegem o foro da comarca de São Paulo, Estado de São Paulo, Brasil, para dirimir quaisquer controvérsias, renunciando a qualquer outro, por mais privilegiado que seja.</p>

<h3>18. Contato</h3>
<p>Se você tiver dúvidas, comentários ou preocupações sobre estes Termos de Serviço, entre em contato conosco através de:</p>
<ul>
    <li><strong>Email:</strong> coralinecompany@gmai.com</li>
</ul>

<p><strong>Última atualização:</strong> Dezembro de 2025</p>
            </div>
            <div class="terms-modal-footer">
                <button id="termsConfirmBtn" class="terms-btn-confirm" disabled>✓ Li e Aceito</button>
            </div>
        </div>
    </div>

    <!-- Modal de Política de Privacidade -->
    <div id="privacyModal" class="terms-modal">
        <div class="terms-modal-content">
            <div class="terms-modal-header">
                <h2> Política de Privacidade</h2>
                <button class="terms-close" data-modal="privacyModal">&times;</button>
            </div>
            <div class="progress-container">
                <div id="privacyProgress" class="progress-bar"></div>
            </div>
            <span id="privacyProgressText" class="progress-text">0% concluído</span>
            <div id="privacyBody" class="terms-modal-body">
            <h3>1. Introdução e Compromisso com a Privacidade</h3>
<p>A Ambience RPG ("nós", "nosso" ou "Plataforma") está comprometida com a proteção da privacidade e dos dados pessoais de todos os seus usuários ("você" ou "usuário"). Esta Política de Privacidade explica como coletamos, usamos, armazenamos, compartilhamos e protegemos suas informações pessoais, em total conformidade com a Lei Geral de Proteção de Dados Pessoais (Lei nº 13.709/2018 - LGPD).</p>

<p>Segundo a LGPD, empresas devem fornecer transparência completa sobre o tratamento de dados pessoais. Por isso, esta política detalha de forma clara e objetiva todas as práticas de tratamento de dados da Plataforma.</p>

<p><strong>Controlador de dados:</strong> A Ambience RPG é o controlador responsável pelo tratamento de seus dados pessoais. Para questões sobre privacidade e proteção de dados, entre em contato com nosso Encarregado de Proteção de Dados (DPO) através do email: coralinecompany@gmail.com</p>

<h3>2. Dados Pessoais Coletados</h3>
<p>Coletamos apenas os dados estritamente necessários para fornecer e melhorar nossos serviços, seguindo o princípio da necessidade e adequação da LGPD. Os dados são categorizados da seguinte forma:</p>

<p><strong>2.1. Dados de Cadastro (obrigatórios):</strong></p>
<ul>
    <li><strong>Nome de usuário:</strong> Identificador único escolhido por você</li>
    <li><strong>Email:</strong> Para comunicações importantes, recuperação de conta e notificações</li>
    <li><strong>Senha:</strong> Armazenada exclusivamente em formato criptografado (hash bcrypt), nunca em texto puro</li>
    <li><strong>Data de nascimento:</strong> Para verificação de idade e conformidade com proteção de menores</li>
</ul>

<p><strong>2.2. Dados de Perfil (opcionais):</strong></p>
<ul>
    <li><strong>Nome completo:</strong> Se você optar por fornecer</li>
    <li><strong>Foto de perfil (avatar):</strong> Imagem opcional para personalização</li>
    <li><strong>Bio/descrição:</strong> Texto livre sobre você</li>
    <li><strong>Gênero:</strong> Informação opcional para personalização de experiência</li>
    <li><strong>Preferências de jogo:</strong> Classes favoritas, estilos de RPG, disponibilidade</li>
</ul>

<p><strong>2.3. Conteúdo e Interações:</strong></p>
<ul>
    <li><strong>Conteúdo criado:</strong> Personagens, fichas, histórias, aventuras, mapas, cenários</li>
    <li><strong>Arquivos enviados:</strong> PDFs, imagens, documentos relevantes para campanhas</li>
    <li><strong>Mensagens:</strong> Conversas em chats de campanha, mensagens privadas, comentários</li>
    <li><strong>Interações sociais:</strong> Curtidas, favoritos, seguidores, avaliações</li>
    <li><strong>Participações:</strong> Sessões de jogo, campanhas mestradas ou participadas, rolagens de dados</li>
</ul>

<p><strong>2.4. Dados de Uso e Técnicos (coletados automaticamente):</strong></p>
<ul>
    <li><strong>Logs de acesso:</strong> Data e hora de acessos, páginas visitadas, ações realizadas</li>
    <li><strong>Endereço IP:</strong> Para segurança, prevenção de fraudes e conformidade legal</li>
    <li><strong>User Agent:</strong> Informações sobre navegador, sistema operacional e dispositivo</li>
    <li><strong>Cookies e tecnologias similares:</strong> Para manter sessões ativas e preferências (veja seção 9)</li>
    <li><strong>Geolocalização aproximada:</strong> Baseada em IP, para estatísticas agregadas (não rastreamento preciso)</li>
</ul>

<p><strong>2.5. Dados Sensíveis:</strong></p>
<p>A Ambience RPG não coleta intencionalmente dados pessoais sensíveis (origem racial ou étnica, convicção religiosa, opinião política, filiação sindical, dados genéticos, biométricos ou de saúde). Caso você voluntariamente inclua tais informações em campos de texto livre (bio, descrições de personagem), recomendamos evitar compartilhar dados sensíveis desnecessários.</p>

<h3>3. Base Legal e Finalidades do Tratamento</h3>
<p>Tratamos seus dados pessoais com base nas seguintes bases legais previstas na LGPD (Art. 7º):</p>

<p><strong>3.1. Execução de contrato (Art. 7º, V):</strong></p>
<ul>
    <li>Fornecer acesso e funcionalidades da Plataforma</li>
    <li>Gerenciar sua conta de usuário</li>
    <li>Processar suas solicitações e comandos</li>
    <li>Permitir interações com outros usuários</li>
</ul>

<p><strong>3.2. Legítimo interesse (Art. 7º, IX):</strong></p>
<ul>
    <li>Melhorar e personalizar a experiência do usuário</li>
    <li>Desenvolver novos recursos e funcionalidades</li>
    <li>Realizar análises estatísticas agregadas sobre uso da Plataforma</li>
    <li>Prevenir fraudes e garantir segurança</li>
    <li>Enviar comunicações relacionadas ao serviço (não marketing)</li>
</ul>

<p><strong>3.3. Cumprimento de obrigação legal (Art. 7º, II):</strong></p>
<ul>
    <li>Atender requisições de autoridades competentes</li>
    <li>Cumprir ordens judiciais</li>
    <li>Manter registros para fins de auditoria e fiscalização</li>
    <li>Proteger direitos em processos judiciais ou administrativos</li>
</ul>

<p><strong>3.4. Consentimento (Art. 7º, I):</strong></p>
<ul>
    <li>Quando solicitamos explicitamente sua autorização para tratamentos específicos</li>
    <li>Para envio de comunicações promocionais (quando implementado)</li>
</ul>

<p><strong>3.5. Exercício regular de direitos (Art. 7º, VI):</strong></p>
<ul>
    <li>Defender-nos em processos judiciais, administrativos ou arbitrais</li>
    <li>Proteger direitos da Plataforma e de terceiros</li>
</ul>

<h3>4. Como Usamos Suas Informações</h3>
<p>Seus dados pessoais são utilizados exclusivamente para as seguintes finalidades legítimas:</p>

<p><strong>Operação da Plataforma:</strong></p>
<ul>
    <li>Autenticar seu acesso e manter sua sessão ativa</li>
    <li>Exibir seu perfil e conteúdo para outrosusuários conforme suas configurações</li>
<li>Facilitar interações sociais (mensagens, comentários, participação em campanhas)</li>
<li>Processar uploads de arquivos e criação de conteúdo</li>
<li>Gerenciar permissões e configurações de privacidade</li>
</ul><p><strong>Comunicações:</strong></p>
<ul>
    <li>Enviar notificações importantes sobre sua conta</li>
    <li>Informar sobre mudanças nos Termos de Serviço ou Política de Privacidade</li>
    <li>Responder suas dúvidas e solicitações de suporte</li>
    <li>Alertas de segurança (tentativas de acesso suspeitas, mudanças de senha)</li>
</ul><p><strong>Segurança e Conformidade:</strong></p>
<ul>
    <li>Detectar, prevenir e investigar atividades fraudulentas ou ilegais</li>
    <li>Proteger contra spam, abuso e violações dos Termos de Serviço</li>
    <li>Manter logs de auditoria para rastreabilidade de incidentes</li>
    <li>Cumprir obrigações legais e requisições de autoridades</li>
</ul><p><strong>Melhoria do Serviço:</strong></p>
<ul>
    <li>Analisar padrões de uso para identificar problemas e oportunidades de melhoria</li>
    <li>Realizar testes A/B de novos recursos (com dados anonimizados quando possível)</li>
    <li>Personalizar sua experiência baseando-se em suas preferências</li>
    <li>Gerar estatísticas agregadas e anonimizadas sobre a comunidade</li>
</ul><p><strong>O que NÃO fazemos com seus dados:</strong></p>
<ul>
    <li><strong>Não vendemos</strong> seus dados pessoais a terceiros sob nenhuma circunstância</li>
    <li><strong>Não alugamos</strong> suas informações para fins comerciais</li>
    <li><strong>Não compartilhamos</strong> dados para publicidade direcionada</li>
    <li><strong>Não usamos</strong> seus dados para criar perfis comportamentais para venda</li>
    <li><strong>Não coletamos</strong> dados além do necessário para operar a Plataforma</li>
</ul><h3>5. Compartilhamento de Dados</h3>
<p>Seus dados pessoais NÃO são compartilhados com terceiros para finalidades comerciais ou de marketing. O compartilhamento ocorre apenas nas seguintes situações limitadas:</p><p><strong>5.1. Prestadores de Serviços (Processadores):</strong></p>
<p>Podemos compartilhar dados com fornecedores que nos auxiliam a operar a Plataforma, mediante contratos que garantem proteção adequada:</p>
<ul>
    <li><strong>Hospedagem:</strong> Servidores e infraestrutura em nuvem (localizados no Brasil)</li>
    <li><strong>Email:</strong> Provedores de serviço de email transacional (notificações, recuperação de conta)</li>
    <li><strong>CDN:</strong> Redes de distribuição de conteúdo para melhor performance</li>
    <li><strong>Backup:</strong> Serviços de backup e recuperação de desastres</li>
    <li><strong>Monitoramento:</strong> Ferramentas de monitoramento de performance e erros</li>
</ul>
<p>Todos os processadores são cuidadosamente selecionados e contratualmente obrigados a proteger seus dados conforme a LGPD.</p><p><strong>5.2. Exigências Legais:</strong></p>
<p>Podemos divulgar dados pessoais quando legalmente obrigados, incluindo:</p>
<ul>
    <li>Ordens judiciais ou mandados de busca</li>
    <li>Requisições de autoridades governamentais competentes</li>
    <li>Cumprimento de leis, regulamentos ou processos legais</li>
    <li>Proteção contra fraudes ou atividades ilegais</li>
    <li>Defesa de direitos legais da Plataforma ou de terceiros</li>
</ul>
<p>Sempre que possível e legalmente permitido, notificaremos você sobre tais solicitações.</p><p><strong>5.3. Conteúdo Público:</strong></p>
<p>Informações que você escolhe tornar públicas na Plataforma (perfil público, personagens compartilhados, comentários públicos, participação em campanhas abertas) estarão visíveis para outros usuários conforme suas configurações de privacidade.</p><p><strong>5.4. Transferência de Propriedade:</strong></p>
<p>Em caso de fusão, aquisição, venda de ativos ou falência, seus dados pessoais podem ser transferidos para a entidade sucessora, sempre com proteções adequadas e notificação prévia aos usuários.</p><p><strong>5.5. Sem Transferência Internacional:</strong></p>
<p>Todos os seus dados são armazenados exclusivamente em servidores localizados no Brasil. Não realizamos transferência internacional de dados pessoais.</p><h3>6. Armazenamento e Segurança dos Dados</h3>
<p>Implementamos medidas técnicas e organizacionais robustas para proteger seus dados pessoais contra acessos não autorizados, perda, destruição, alteração ou divulgação indevida:</p><p><strong>Medidas de Segurança Técnica:</strong></p>
<ul>
    <li><strong>Criptografia:</strong> Senhas armazenadas com hash bcrypt (nunca em texto puro); conexões HTTPS/TLS para transmissão de dados</li>
    <li><strong>Controle de acesso:</strong> Autenticação forte, separação de privilégios, acesso baseado em funções (RBAC)</li>
    <li><strong>Firewall e proteção de rede:</strong> Firewalls configurados, detecção de intrusão, proteção DDoS</li>
    <li><strong>Monitoramento:</strong> Logs de segurança, alertas de atividades suspeitas, análise de comportamento</li>
    <li><strong>Backups:</strong> Backups regulares criptografados, armazenados em locais seguros e separados</li>
    <li><strong>Atualização:</strong> Sistemas e bibliotecas mantidos atualizados com patches de segurança</li>
    <li><strong>Testes:</strong> Testes regulares de segurança, revisão de código, análise de vulnerabilidades</li>
</ul><p><strong>Medidas Organizacionais:</strong></p>
<ul>
    <li><strong>Acesso restrito:</strong> Apenas funcionários autorizados acessam dados pessoais, com base em necessidade profissional</li>
    <li><strong>Treinamento:</strong> Equipe treinada em segurança da informação e proteção de dados</li>
    <li><strong>Políticas internas:</strong> Procedimentos documentados de segurança e resposta a incidentes</li>
    <li><strong>Contratos:</strong> Cláusulas de confidencialidade com funcionários e fornecedores</li>
    <li><strong>Auditoria:</strong> Revisões periódicas de práticas de segurança</li>
</ul><p><strong>Localização dos Dados:</strong></p>
<p>Todos os dados pessoais são armazenados em data centers localizados em território brasileiro, em conformidade com a LGPD e o Marco Civil da Internet.</p><p><strong>Limitações:</strong></p>
<p>Apesar de todos os esforços, nenhum sistema é 100% seguro. Não podemos garantir segurança absoluta contra ataques sofisticados, falhas de hardware, desastres naturais ou ações de terceiros fora de nosso controle. Em caso de incidente de segurança que afete seus dados, você será notificado conforme exigido pela LGPD.</p><h3>7. Retenção e Exclusão de Dados</h3>
<p>Mantemos seus dados pessoais apenas pelo tempo necessário para cumprir as finalidades informadas, seguindo o princípio da necessidade da LGPD:</p><p><strong>7.1. Durante o Uso Ativo:</strong></p>
<p>Enquanto sua conta estiver ativa, manteremos seus dados para fornecer o serviço completo.</p><p><strong>7.2. Após Exclusão de Conta:</strong></p>
<p>Quando você solicitar a exclusão de sua conta:</p>
<ul>
    <li><strong>Imediato:</strong> Conta desativada e inacessível para login</li>
    <li><strong>Até 30 dias:</strong> Dados pessoais identificáveis (nome, email, perfil) permanentemente removidos do banco de dados principal</li>
    <li><strong>Conteúdo público:</strong> Conteúdo que você criou e foi compartilhado publicamente (personagens em campanhas abertas, comentários públicos) pode ser mantido de forma anonimizada para preservar a integridade de campanhas ativas</li>
</ul><p><strong>7.3. Logs de Sistema e Segurança:</strong></p>
<p>Logs técnicos essenciais para segurança, auditoria e conformidade legal são mantidos por período limitado:</p>
<ul>
    <li><strong>Logs de acesso:</strong> Até 6 meses (conforme Marco Civil da Internet - Art. 13)</li>
    <li><strong>Logs de incidentes de segurança:</strong> Até 1 ano para investigação e defesa legal</li>
    <li><strong>Dados para cumprimento de obrigações legais:</strong> Pelo prazo exigido por lei (ex: 5 anos para obrigações tributárias)</li>
</ul><p><strong>7.4. Backups:</strong></p>
<p>Dados excluídos podem permanecer em backups de segurança por até 90 dias, após os quais são permanentemente apagados. Backups são isolados e não utilizados para operações regulares.</p><p><strong>7.5. Exclusão Definitiva:</strong></p>
<p>Após os prazos acima, seus dados pessoais são permanentemente apagados de todos os sistemas, incluindo backups, de forma irreversível.</p><h3>8. Seus Direitos como Titular de Dados (LGPD)</h3>
<p>A LGPD (Art. 18) garante aos titulares de dados pessoais diversos direitos, que você pode exercer gratuitamente a qualquer momento:</p><p><strong>8.1. Direito de Confirmação e Acesso (Art. 18, I e II):</strong></p>
<ul>
    <li>Confirmar se tratamos seus dados pessoais</li>
    <li>Acessar todos os dados que mantemos sobre você</li>
    <li>Receber cópia dos dados em formato legível</li>
</ul><p><strong>8.2. Direito de Correção (Art. 18, III):</strong></p>
<ul>
    <li>Corrigir dados incompletos, inexatos ou desatualizados</li>
    <li>Atualizar informações de perfil diretamente nas configurações</li>
</ul><p><strong>8.3. Direito de Anonimização, Bloqueio ou Eliminação (Art. 18, IV):</strong></p>
<ul>
    <li>Solicitar anonimização de dados desnecessários ou excessivos</li>
    <li>Bloquear temporariamente o tratamento de certos dados</li>
    <li>Solicitar eliminação permanente de dados tratados com seu consentimento ou quando não houver base legal</li>
</ul><p><strong>8.4. Direito de Portabilidade (Art. 18, V):</strong></p>
<ul>
    <li>Receber seus dados em formato estruturado, legível por máquina (JSON, CSV)</li>
    <li>Transferir dados para outro prestador de serviço (quando tecnicamente viável)</li>
</ul><p><strong>8.5. Direito de Informação (Art. 18, VI e VII):</strong></p>
<ul>
    <li>Saber com quais entidades públicas e privadas compartilhamos seus dados</li>
    <li>Conhecer a possibilidade de negar consentimento e suas consequências</li>
</ul><p><strong>8.6. Direito de Revogação do Consentimento (Art. 18, IX):</strong></p>
<ul>
    <li>Retirar consentimento previamente dado para tratamentos específicos</li>
    <li>Entender que isso pode limitar funcionalidades que dependem daquele consentimento</li>
</ul><p><strong>8.7. Direito de Oposição (Art. 18, § 2º):</strong></p>
<ul>
    <li>Opor-se a tratamentos realizados sem seu consentimento, quando aplicável</li>
    <li>Contestar decisões automatizadas que afetem significativamente seus interesses</li>
</ul><p><strong>8.8. Direito de Revisão de Decisões Automatizadas (Art. 20):</strong></p>
<ul>
    <li>Solicitar revisão de decisões tomadas exclusivamente por processamento automatizado</li>
    <li>Receber informações sobre critérios e procedimentos usados em decisões automatizadas</li>
</ul><p><strong>Como Exercer Seus Direitos:</strong></p>
<p>Para exercer qualquer destes direitos, você pode:</p>
<ul>
    <li><strong>Configurações da conta:</strong> Muitos dados podem ser acessados, corrigidos ou excluídos diretamente nas configurações de perfil</li>
    <li><strong>Email ao DPO:</strong> coralinecompany@gmail.com</li>
    <li><strong>Suporte:</strong> coralinecompany@gmail.com</li>
</ul><p><strong>Prazos de Resposta:</strong></p>
<p>Responderemos sua solicitação em até 15 dias corridos, podendo ser prorrogado por mais 15 dias mediante justificativa (conforme Art. 18, § 3º da LGPD).</p><p><strong>Verificação de Identidade:</strong></p>
<p>Para proteger sua privacidade, podemos solicitar verificação de identidade antes de processar certas solicitações.</p><h3>9. Cookies e Tecnologias Similares</h3>
<p>A Ambience RPG utiliza cookies e tecnologias similares para melhorar sua experiência, manter sua sessão ativa e coletar informações sobre uso da Plataforma:</p><p><strong>O que são cookies:</strong></p>
<p>Cookies são pequenos arquivos de texto armazenados pelo seu navegador, que permitem reconhecer você em visitas subsequentes.</p><p><strong>Tipos de cookies que usamos:</strong></p><p><strong>Cookies essenciais (necessários):</strong></p>
<ul>
    <li><strong>Sessão:</strong> Mantém você logado durante a navegação</li>
    <li><strong>Autenticação:</strong> Verifica sua identidade</li>
    <li><strong>Segurança:</strong> Previne CSRF e outras vulnerabilidades</li>
    <li><strong>Preferências:</strong> Lembra configurações básicas (idioma, tema)</li>
</ul>
<p>Estes cookies são indispensáveis para o funcionamento da Plataforma e não podem ser desabilitados.</p><p><strong>Cookies de funcionalidade (opcionais):</strong></p>
<ul>
    <li><strong>Preferências avançadas:</strong> Lembra configurações detalhadas de interface</li>
    <li><strong>Conteúdo personalizado:</strong> Adapta experiência baseada em seu uso</li>
</ul><p><strong>Cookies analíticos (opcionais):</strong></p>
<ul>
    <li><strong>Estatísticas de uso:</strong> Contabiliza visitantes, páginas populares, tempo de sessão</li>
    <li><strong>Performance:</strong> Identifica problemas técnicos e gargalos</li>
</ul>
<p>Estes dados são agregados e anonimizados sempre que possível.</p><p><strong>Cookies de terceiros:</strong></p>
<p>Não utilizamos cookies de terceiros para publicidade ou rastreamento. Cookies de prestadores de serviços essenciais (ex: CDN) são minimizados e cobertos por acordos de proteção de dados.</p><p><strong>Gestão de cookies:</strong></p>
<p>Você pode gerenciar preferências de cookies através das configurações do navegador. Note que bloquear cookies essenciais impedirá o uso da Plataforma. Consulte a documentação do seu navegador para instruções específicas:</p>
<ul>
    <li>Chrome: chrome://settings/cookies</li>
    <li>Firefox: about:preferences#privacy</li>
    <li>Safari: Preferências > Privacidade</li>
    <li>Edge: edge://settings/privacy</li>
</ul><p><strong>Armazenamento local:</strong></p>
<p>Além de cookies, podemos usar localStorage e sessionStorage do navegador para armazenar temporariamente dados de sessão e preferências localmente no seu dispositivo.</p><h3>10. Privacidade de Crianças e Adolescentes</h3>
<p>A proteção de dados de crianças e adolescentes é prioridade absoluta, conforme Estatuto da Criança e do Adolescente (ECA) e LGPD (Art. 14):</p><p><strong>Menores de 13 anos:</strong></p>
<ul>
    <li>Acesso restrito a funcionalidades limitadas</li>
    <li>Obrigatória supervisão de pais ou responsáveis</li>
    <li>Censura automática ativada em chats e conteúdo</li>
    <li>Proibição de compartilhamento de dados pessoais sensíveis</li>
    <li>Consentimento específico dos responsáveis legais</li>
</ul><p><strong>Adolescentes (13 a 17 anos):</strong></p>
<ul>
    <li>Podem usar a Plataforma com autorização dos pais/responsáveis</li>
    <li>Moderação reforçada de conteúdo</li>
    <li>Ferramentas de controle parental disponíveis</li>
    <li>Restrições a interações com desconhecidos</li>
</ul><p><strong>Responsabilidade dos pais:</strong></p>
<p>Pais e responsáveis que autorizarem o uso por menores devem supervisionar ativamente as atividades, configurar controles de privacidade e revisar conteúdos acessados.</p><p><strong>Coleta de dados de menores:</strong></p>
<p>Coletamos apenas dados mínimos necessários de usuários menores de idade, sempre com consentimento dos responsáveis e respeito ao melhor interesse da criança.</p><p><strong>Denúncia:</strong></p>
<p>Caso identifique uso inadequado da Plataforma por menores ou conteúdo inapropriado direcionado a eles, reporte imediatamente para: coralinecompany@gmail.com</p><h3>11. Alterações nesta Política de Privacidade</h3>
<p>Reservamo-nos o direito de atualizar esta Política de Privacidade periodicamente para refletir mudanças em nossas práticas, tecnologias ou requisitos legais.</p><p><strong>Notificação de mudanças:</strong></p>
<p>Mudanças significativas serão comunicadas através de:</p>
<ul>
    <li>Aviso destacado na Plataforma</li>
    <li>Email para o endereço cadastrado (para mudanças que afetem seus direitos)</li>
    <li>Notificação no painel do usuário</li>
</ul><p><strong>Data de vigência:</strong></p>
<p>Atualizações entram em vigor na data de publicação. Recomendamos revisar esta política regularmente.</p><p><strong>Versão anterior:</strong></p>
<p>Você pode solicitar cópias de versões anteriores desta política através do email: coralinecompany@gmail.com</p><h3>12. Transferência de Dados em Caso de Mudança de Controle</h3>
<p>Em caso de fusão, aquisição, venda de ativos ou reorganização da Ambience RPG, seus dados pessoais podem ser transferidos para a entidade sucessora.</p><p><strong>Proteções garantidas:</strong></p>
<ul>
    <li>A entidade sucessora deve continuar honrando esta Política de Privacidade</li>
    <li>Você será notificado com antecedência razoável sobre a transferência</li>
    <li>Terá oportunidade de excluir sua conta antes da transferência, se desejar</li>
    <li>Todas as proteções da LGPD continuarão aplicáveis</li>
</ul><h3>13. Jurisdição e Lei Aplicável</h3>
<p>Esta Política de Privacidade é regida pela legislação brasileira, especialmente:</p>
<ul>
    <li>Lei Geral de Proteção de Dados Pessoais (Lei nº 13.709/2018 - LGPD)</li>
    <li>Marco Civil da Internet (Lei nº 12.965/2014)</li>
    <li>Estatuto da Criança e do Adolescente (Lei nº 8.069/1990)</li>
    <li>Código de Defesa do Consumidor (Lei nº 8.078/1990)</li>
</ul><p>Quaisquer disputas relacionadas a esta política serão resolvidas no foro da comarca de São Paulo, Estado de São Paulo, Brasil.</p><h3>14. Direito de Reclamação à ANPD</h3>
<p>Se você acredita que seus direitos de proteção de dados foram violados, tem o direito de apresentar reclamação à Autoridade Nacional de Proteção de Dados (ANPD):</p>
<ul>
    <li><strong>Website:</strong> www.gov.br/anpd</li>
    <li><strong>Endereço:</strong> Esplanada dos Ministérios, Bloco T, Anexo II, Brasília/DF</li>
</ul><p>No entanto, encorajamos você a nos contatar primeiro para que possamos resolver qualquer preocupação diretamente.</p><h3>15. Contato e Encarregado de Proteção de Dados (DPO)</h3>
<p>Para questões, dúvidas, exercício de direitos ou reclamações sobre privacidade e proteção de dados pessoais, entre em contato:</p><p><strong>Encarregado de Proteção de Dados (DPO):</strong></p>
<ul>
    <li><strong>Email:</strong> coralinecompany@gmail.com</li>
    <li><strong>Assunto:</strong> Especifique claramente o motivo do contato (ex: "Solicitação de Acesso a Dados", "Exclusão de Conta", "Dúvida sobre Privacidade")</li>
</ul><p><strong>Suporte Geral:</strong></p>
<ul>
    <li><strong>Email:</strong> coralinecompany@gmail.com</li>
</ul><p><strong>Tempo de resposta:</strong></p>
<p>Respondemos solicitações relacionadas à LGPD em até 15 dias corridos, prorrogáveis por mais 15 dias mediante justificativa.</p><p><strong>Transparência:</strong></p>
<p>Estamos comprometidos com a transparência total. Se você tiver qualquer dúvida sobre como tratamos seus dados, não hesite em nos contatar.</p><p><strong>Última atualização:</strong> Dezembro de 2024</p><p style="margin-top: 2rem; padding: 1.5rem; background: rgba(34, 197, 94, 0.1); border-left: 4px solid var(--accent); border-radius: 8px;">
    <strong>Compromisso com sua privacidade:</strong> A Ambience RPG é uma plataforma gratuita, sem anúncios, criada por jogadores para jogadores. Nunca venderemos seus dados, nunca os usaremos para fins comerciais externos, e sempre priorizaremos sua privacidade acima de qualquer interesse financeiro. Esta é nossa promessa à comunidade RPG.
</p>
            </div>
            <div class="terms-modal-footer">
                <button id="privacyConfirmBtn" class="terms-btn-confirm" disabled>✓ Li e Aceito</button>
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