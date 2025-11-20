{{-- resources/views/home.blade.php --}}
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Ambience RPG – Sua aventura começa aqui</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
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
  
  /* Variáveis para transição suave de temas */
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

/* ========== ESTILOS PARA NOTIFICAÇÕES ========== */
.notification-list {
  max-height: 400px;
  overflow-y: auto;
  padding: 0;
}

.notification-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 14px 20px;
  border-bottom: 1px solid rgba(34, 197, 94, 0.1);
  cursor: pointer;
  transition: all 0.2s;
  position: relative;
}

.notification-item:hover {
  background: rgba(34, 197, 94, 0.05);
}

.notification-item.nao-lida {
  background: rgba(34, 197, 94, 0.08);
}

.notification-item.nao-lida::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3px;
  background: var(--accent);
}

.notification-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.notification-icon.blue {
  background: rgba(59, 130, 246, 0.1);
  color: #3b82f6;
}

.notification-icon.green {
  background: rgba(34, 197, 94, 0.1);
  color: #22c55e;
}

.notification-icon.red {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.notification-icon.yellow {
  background: rgba(234, 179, 8, 0.1);
  color: #eab308;
}

.notification-icon.purple {
  background: rgba(168, 85, 247, 0.1);
  color: #a855f7;
}

.notification-icon svg {
  width: 20px;
  height: 20px;
}

.notification-content {
  flex: 1;
  min-width: 0;
}

.notification-message {
  font-size: 14px;
  color: #e5e7eb;
  margin-bottom: 4px;
  line-height: 1.4;
}

.notification-time {
  font-size: 12px;
  color: var(--muted);
}

.notification-actions {
  display: flex;
  gap: 6px;
  opacity: 0;
  transition: opacity 0.2s;
}

.notification-item:hover .notification-actions {
  opacity: 1;
}

.notification-action-btn {
  width: 28px;
  height: 28px;
  border-radius: 6px;
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
  color: var(--muted);
}

.notification-action-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
}

.notification-action-btn.marcar-lida:hover {
  color: var(--accent);
}

.notification-action-btn.remover:hover {
  color: #ef4444;
}

.notification-action-btn svg {
  width: 14px;
  height: 14px;
}

/* Scrollbar personalizada */
.notification-list::-webkit-scrollbar {
  width: 6px;
}

.notification-list::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 3px;
}

.notification-list::-webkit-scrollbar-thumb {
  background: rgba(34, 197, 94, 0.3);
  border-radius: 3px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
  background: rgba(34, 197, 94, 0.5);
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

/* Hero */
.hero{
  background:linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-mid) 50%, var(--gradient-end) 100%);
  padding:100px 0 140px;
  position:relative;
  overflow:hidden;
  text-align:center;
  transition:background var(--transition-speed) ease;
}
.hero::before{content:"";position:absolute;inset:0;background:radial-gradient(ellipse at top,rgba(34,197,94,0.15),transparent 60%);pointer-events:none}
.hero::after{content:"";position:absolute;left:0;right:0;bottom:0;height:120px;background:linear-gradient(180deg,transparent,#0a0f14)}
.hero-inner{position:relative;z-index:2;max-width:1100px;margin:0 auto;padding:0 32px}
.hero h1{font-family:Montserrat,sans-serif;font-size:84px;line-height:0.95;font-weight:900;letter-spacing:3px;color:#fff;text-transform:uppercase;margin-bottom:24px}
.hero h1 .second{display:block;color:var(--accent);margin-top:12px;transition:color var(--transition-speed) ease}
.hero p.lead{color:rgba(255,255,255,0.88);font-size:18px;max-width:700px;margin:0 auto 32px;line-height:1.6}
.hero-actions{display:flex;gap:16px;justify-content:center;align-items:center}
.hero-actions .btn{padding:14px 28px;font-size:16px;border-radius:12px}
.hero-actions .btn svg{width:18px;height:18px}
.hero-actions .btn.ghost{
  background:transparent;
  border:1px solid var(--accent-border);
  color:#fff;
  transition:all .3s ease;
}
.hero-actions .btn.ghost:hover{
  background:rgba(34,197,94,0.1);
  border-color:var(--accent);
}

/* Sections */
.section{padding:90px 0}
.section.dark{background:linear-gradient(180deg,#0d1419 0%, #0a0f14 100%)}
.section h2{font-size:38px;font-weight:700;text-align:center;margin-bottom:12px;color:#fff}
.section-subtitle{text-align:center;color:var(--muted);font-size:17px;max-width:800px;margin:0 auto 48px}

/* Features Grid */
.features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1200px;margin:0 auto;padding:0 32px}
.card{
  background:linear-gradient(145deg,rgba(31,42,51,0.6),rgba(20,28,35,0.4));
  border:1px solid rgba(34,197,94,0.1);
  border-radius:16px;
  padding:32px;
  transition:all .3s cubic-bezier(.2,.8,.2,1);
  position:relative;
  overflow:hidden;
}
.card::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at top left,rgba(34,197,94,0.05),transparent 70%);opacity:0;transition:opacity .3s}
.card:hover{
  transform:translateY(-8px);
  border-color:rgba(34,197,94,0.3);
  box-shadow:0 16px 40px rgba(0,0,0,0.4);
}
.card:hover::before{opacity:1}
.icon{
  width:52px;
  height:52px;
  border-radius:12px;
  background:linear-gradient(135deg,#064e3b,#052e16);
  display:flex;
  align-items:center;
  justify-content:center;
  margin-bottom:18px;
  box-shadow:0 4px 12px rgba(5,46,22,0.5);
}
.icon svg{width:26px;height:26px;fill:none;stroke:var(--accent);stroke-width:2;stroke-linecap:round;stroke-linejoin:round;transition:stroke var(--transition-speed) ease}
.card h3{font-size:21px;font-weight:700;margin-bottom:10px;color:#fff}
.card p{color:var(--muted);font-size:15px;line-height:1.65}

/* Bring Section */
.bring{display:grid;grid-template-columns:1fr 520px;gap:60px;align-items:center;max-width:1200px;margin:0 auto;padding:0 32px}
.bring h2{font-size:36px;font-weight:700;margin-bottom:18px;text-align:left;color:#fff}
.bring p{color:var(--muted);font-size:16px;line-height:1.7;margin-bottom:24px}
.bullets{list-style:none;padding:0}
.bullets li{margin:14px 0;color:#d0dce5;position:relative;padding-left:32px;font-size:16px}
.bullets li::before{content:'✓';position:absolute;left:0;top:1px;color:var(--accent);font-weight:900;font-size:18px;transition:color var(--transition-speed) ease}

/* Chat Mockup */
.chat-card{background:linear-gradient(145deg,rgba(31,42,51,0.4),rgba(20,28,35,0.3));padding:24px;border-radius:20px;border:1px solid rgba(34,197,94,0.1)}
.chat-mockup{background:linear-gradient(180deg,#1f2937,#111827);border-radius:18px;padding:20px;box-shadow:0 16px 48px rgba(0,0,0,0.5)}
.chat-top{display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid rgba(34,197,94,0.15)}
.window-dots{display:flex;gap:7px}
.dot{width:11px;height:11px;border-radius:50%}
.dot.red{background:#ef4444}
.dot.yellow{background:#eab308}
.dot.green{background:var(--accent);transition:background var(--transition-speed) ease}
.chat-title{flex:1;text-align:center;color:#fff;font-size:14px;font-weight:600}
.message{display:flex;gap:12px;align-items:flex-start;margin:16px 0}
.avatar{width:38px;height:38px;border-radius:50%;flex-shrink:0;object-fit:cover;background:#374151;border:2px solid rgba(34,197,94,0.2)}
.message-content{display:flex;flex-direction:column;gap:4px;max-width:320px}
.username{color:var(--accent);font-size:13px;font-weight:700;transition:color var(--transition-speed) ease}
.bubble{padding:10px 14px;border-radius:12px;font-size:14px;line-height:1.4;background:#374151;color:#e5eef1}
.timestamp{color:var(--muted);font-size:11px;margin-top:2px;padding-left:14px}

/* CTA Section */
.cta-big{text-align:center;padding:80px 32px}
.cta-big h2{font-size:42px;margin-bottom:14px;color:#fff}
.cta-big p{color:var(--muted);font-size:17px;margin-bottom:32px}
.cta-actions{display:flex;gap:16px;justify-content:center}
.cta-actions .btn{padding:14px 28px;font-size:16px;border-radius:12px}
.cta-actions .btn.ghost{
  background:transparent;
  border:1px solid var(--accent-border);
  color:#fff;
  transition:all .3s ease;
}
.cta-actions .btn.ghost:hover{
  background:rgba(34,197,94,0.1);
  border-color:var(--accent);
}
.cta-note{color:var(--muted);font-size:14px;margin-top:16px}

/* Footer */
.footer{border-top:1px solid rgba(34,197,94,0.1);padding:48px 0 24px;background:#0d1419}
.footer-columns{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;max-width:1200px;margin:0 auto 32px;padding:0 32px}
.footer-brand{display:flex;align-items:center;gap:12px;margin-bottom:14px;font-weight:800;font-size:18px}
.footer-brand .logo-img{height:40px;width:auto}
.footer h4{font-size:15px;font-weight:700;margin-bottom:14px;color:var(--accent);transition:color var(--transition-speed) ease}
.footer ul{list-style:none}
.footer ul li{margin:8px 0;color:var(--muted);font-size:14px;cursor:pointer;transition:color .2s}
.footer ul li:hover{color:var(--accent)}
.footer-text{color:var(--muted);font-size:14px;line-height:1.6;max-width:320px}
.social-links{display:flex;gap:16px;margin-top:12px}
.social-links svg{width:20px;height:20px;fill:var(--muted);cursor:pointer;transition:fill .2s}
.social-links svg:hover{fill:var(--accent)}
.footer-bottom{text-align:center;color:var(--muted);font-size:13px;padding-top:24px;border-top:1px solid rgba(34,197,94,0.1);max-width:1200px;margin:0 auto;padding:24px 32px 0}

/* CARROSSEL SECTION */
.carousel-section{
  position:relative;
  overflow:hidden;
  padding:80px 0 120px;
  background:linear-gradient(180deg, rgba(5,46,22,0.03), transparent 30%);
}

.carousel-section .carousel-bg{
  position:absolute;
  inset:0;
  background-repeat:no-repeat;
  background-position:center center;
  background-size:cover;
  filter:blur(28px) saturate(120%);
  transform:scale(1.06);
  z-index:2;
  opacity:1;
  transition:background-image 600ms ease, opacity 300ms ease;
  box-shadow:inset 0 0 0 2000px rgba(10,15,20,0.35);
  pointer-events:none;
}

.carousel-wrap{
  position:relative;
  display:flex;
  align-items:center;
  gap:18px;
  z-index:6;
  max-width:1100px;
  width:100%;
  margin:0 auto;
  padding:0 32px;
}
.carousel-frame{
  flex:1;
  display:flex;
  justify-content:center;
  align-items:center;
  overflow:visible;
}
.slides{
  display:flex;
  gap:18px;
  list-style:none;
  padding:0;
  margin:0;
  align-items:center;
  justify-content:center;
}
.slide{
  flex:0 0 56%;
  max-width:760px;
  border-radius:18px;
  overflow:hidden;
  box-shadow:0 18px 48px rgba(2,6,8,0.6);
  transform-origin:center center;
  transition:transform 450ms cubic-bezier(.2,.9,.2,.9),opacity 450ms;
  opacity:0.35;
  transform:scale(0.92);
}
.slide img{
  display:block;
  width:100%;
  height:420px;
  object-fit:cover;
  vertical-align:middle;
  border-radius:14px;
}
.slide.active{
  opacity:1;
  transform:scale(1);
}

.nav-carousel{
  background:rgba(10,15,20,0.5);
  border:1px solid rgba(255,255,255,0.04);
  color:#fff;
  width:52px;
  height:52px;
  border-radius:12px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  font-size:26px;
  cursor:pointer;
  transition:transform .18s,background .18s;
  position:relative;
  z-index:30;
  pointer-events:auto;
}
.nav-carousel:hover{
  transform:translateY(-3px);
  background:rgba(34,197,94,0.12);
}
.nav-carousel:active{
  transform:translateY(0);
}

.carousel-controls{
  margin-top:18px;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  width:100%;
  z-index:6;
}
.dots{
  display:flex;
  gap:8px;
}
.dots .dot{
  width:10px;
  height:10px;
  border-radius:50%;
  background:rgba(255,255,255,0.18);
  cursor:pointer;
  transition:transform .18s,background .18s;
  border:none;
}
.dots .dot.active{
  transform:scale(1.25);
  background:var(--accent);
}

.carousel-copy{
  max-width:880px;
  margin:28px auto 0;
  text-align:center;
  z-index:6;
  color:var(--text-on-primary);
}
.carousel-copy h3{
  font-size:28px;
  font-weight:700;
  margin-bottom:8px;
  color:#fff;
}
.carousel-copy p{
  color:var(--muted);
  font-size:15px;
  max-width:720px;
  margin:0 auto 12px;
}

/* Responsive */
@media(max-width:900px){
  .slide img{height:320px}
  .slide{flex:0 0 78%}
  .hero h1{font-size:64px}
  .features-grid{grid-template-columns:repeat(2,1fr)}
  .bring{grid-template-columns:1fr}
}
@media(max-width:768px){
  .hero h1{font-size:48px}
  .features-grid{grid-template-columns:1fr}
  .nav-links{display:none}
  .footer-columns{grid-template-columns:1fr}
  .notification-modal{
    right:16px;
    left:16px;
    width:auto;
  }
}
@media(max-width:560px){
  .slide img{height:220px}
  .nav-carousel{width:44px;height:44px}
  .carousel-wrap{gap:12px}
  .carousel-copy h3{font-size:20px}
}
</style>
</head>
<body>

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

<section class="hero">
  <div class="hero-inner">
    <h1>SUA AVENTURA<br><span class="second">COMEÇA AQUI</span></h1>
    <p class="lead">Ambiente imersivo para RPG, ferramentas completas e recursos para mestres e jogadores criarem histórias inesquecíveis.</p>
    <div class="hero-actions">
      @guest
        <button class="btn primary" onclick="window.location.href='{{ route('usuarios.create') }}'">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M8 5v14l11-7z"/>
          </svg>
          Iniciar Aventura
        </button>
      @else
        <button class="btn primary" onclick="window.location.href='{{ route('salas.index') }}'">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M8 5v14l11-7z"/>
          </svg>
          Minhas Salas
        </button>
      @endguest
      <button class="btn ghost" onclick="window.location.href='{{ route('salas.index') }}'">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <circle cx="11" cy="11" r="8"/>
          <path d="M21 21l-4.35-4.35"/>
        </svg>
        Explorar Salas
      </button>
    </div>
  </div>
</section>

<!-- CARROSSEL SECTION -->
<section class="section carousel-section" aria-labelledby="carousel-title">
  <div class="carousel-bg" aria-hidden="true"></div>

  <div class="carousel-wrap">
    <button id="carousel-prev" class="nav-carousel nav-prev" aria-label="Anterior" type="button">‹</button>

    <div class="carousel-frame" role="region" aria-roledescription="carousel" aria-label="Destaques Ambience">
      <ul class="slides" role="list">
        <li class="slide active" data-theme="medieval" role="listitem">
          <img src="{{ asset('images/tavern.webp') }}" alt="Cenário Medieval – Calabouço">
        </li>
        <li class="slide" data-theme="cyberpunk" role="listitem">
          <img src="{{ asset('images/cyberpunk.webp') }}" alt="Cenário Cyberpunk – Neon">
        </li>
        <li class="slide" data-theme="forest" role="listitem">
          <img src="{{ asset('images/forest.webp') }}" alt="Cenário Florestal – Selvagem">
        </li>
      </ul>
    </div>

    <button id="carousel-next" class="nav-carousel nav-next" aria-label="Próximo" type="button">›</button>
  </div>

  <div class="carousel-controls">
    <div class="dots" role="tablist" aria-hidden="false"></div>
    <div class="carousel-copy" id="carousel-title">
      <h3>Aqui na Ambience, nos adaptamos à sua aventura</h3>
      <p>Toque as setas para mudar a paleta do site conforme o tema – medieval, cyberpunk ou floresta.</p>
    </div>
  </div>
</section>

<section class="section dark">
  <div class="container">
    <h2>Tudo Que Você Precisa Para Aventuras Épicas</h2>
    <p class="section-subtitle">Mergulhe em um mundo de possibilidades infinitas com nosso kit completo de ferramentas para RPG</p>
    
    <div class="features-grid">
      <div class="card">
        <div class="icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M9 18V5l12-2v13"/>
            <circle cx="6" cy="18" r="3"/>
            <circle cx="18" cy="16" r="3"/>
          </svg>
        </div>
        <h3>Ambientes Sonoros</h3>
        <p>Sons ambientes e músicas dinâmicas que se adaptam ao clima e cenário da sua campanha</p>
      </div>

      <div class="card">
        <div class="icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M3 6l6-3 6 3 6-3v15l-6 3-6-3-6 3V6z"/>
          </svg>
        </div>
        <h3>Salas Personalizadas</h3>
        <p>Crie salas públicas ou privadas com controle total de permissões e convites</p>
      </div>

      <div class="card">
        <div class="icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
        <h3>Gestão de Personagens</h3>
        <p>Fichas completas e acompanhamento de progressão para jogadores e NPCs</p>
      </div>

      <div class="card">
        <div class="icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
        </div>
        <h3>Sistema de Sessões</h3>
        <p>Inicie sessões ao vivo com chat em tempo real e controles para o mestre</p>
      </div>

      <div class="card">
        <div class="icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <circle cx="15.5" cy="8.5" r="1.5"/>
            <circle cx="8.5" cy="15.5" r="1.5"/>
            <circle cx="15.5" cy="15.5" r="1.5"/>
          </svg>
        </div>
        <h3>Rolador de Dados</h3>
        <p>Sistema avançado de rolagem com modificadores personalizados e cálculos automáticos</p>
      </div>

      <div class="card">
        <div class="icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
          </svg>
        </div>
        <h3>Comunidade Ativa</h3>
        <p>Compartilhe suas aventuras, ideias e conecte-se com outros jogadores e mestres</p>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="bring">
    <div>
      <h2>Dê Vida às Suas Campanhas</h2>
      <p>Experimente a combinação perfeita entre RPG de mesa tradicional e ferramentas digitais modernas. Nossa plataforma aprimora a narrativa sem substituir a magia do jogo presencial.</p>
      <ul class="bullets">
        <li>Ferramentas de colaboração em tempo real</li>
        <li>Grid Modular 2D</li>
        <li>Chat com Comandos para sessão</li>
      </ul>
    </div>
    <div>
      <div class="chat-card">
        <div class="chat-mockup">
          <div class="chat-top">
            <div class="window-dots" aria-hidden="true">
              <div class="dot red"></div>
              <div class="dot yellow"></div>
              <div class="dot green"></div>
            </div>
            <div class="chat-title"># Calabouço das Sombras</div>
          </div>

          <div class="message">
            <img src="https://i.pravatar.cc/150?img=12" alt="Avatar" class="avatar">
            <div class="message-content">
              <span class="username">Theron</span>
              <div class="bubble">Pessoal, tem algo estranho nessa sala... vejam essas marcas nas paredes</div>
              <span class="timestamp">14:32</span>
            </div>
          </div>

          <div class="message">
            <img src="https://i.pravatar.cc/150?img=28" alt="Avatar" class="avatar">
            <div class="message-content">
              <span class="username">Lyra</span>
              <div class="bubble">Cuidado! Sinto uma presença mágica muito forte aqui. Vou tentar identificar...</div>
              <span class="timestamp">14:33</span>
            </div>
          </div>

          <div class="message">
            <img src="https://i.pravatar.cc/150?img=33" alt="Avatar" class="avatar">
            <div class="message-content">
              <span class="username">Mestre Drake</span>
              <div class="bubble">Enquanto Lyra se concentra, vocês ouvem um som de correntes vindo do corredor à esquerda...</div>
              <span class="timestamp">14:34</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section dark cta-big">
  <h2>Pronto Para Começar Sua Jornada Épica?</h2>
  <p>Junte-se a milhares de jogadores e mestres que já usam o Ambience RPG para criar aventuras inesquecíveis.</p>
  <div class="cta-actions">
    @guest
      <button class="btn primary" onclick="window.location.href='{{ route('usuarios.create') }}'">🚀 Criar Conta Grátis</button>
    @else
      <button class="btn primary" onclick="window.location.href='{{ route('salas.index') }}'">🚀 Ir Para Minhas Salas</button>
    @endguest
    <button class="btn ghost" onclick="window.location.href='{{ route('salas.index') }}'">▶ Explorar Salas</button>
  </div>
  <p class="cta-note">Sem necessidade de cartão de crédito • Totalmente gratuito</p>
</section>

<footer class="footer">
  <div class="footer-columns">
    <div>
      <div class="footer-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
      </div>
      <p class="footer-text">Capacitando mestres e aventureiros em todo o mundo com a plataforma definitiva para RPG.</p>
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
        <li>Tutoriais</li>
        <li>FAQ</li>
      </ul>
    </div>
    <div>
      <h4>Conecte-se</h4>
      <div class="social-links" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515a.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0a12.64 12.64 0 0 0-.617-1.25a.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057a19.9 19.9 0 0 0 5.993 3.03a.078.078 0 0 0 .084-.028a14.09 14.09 0 0 0 1.226-1.994a.076.076 0 0 0-.041-.106a13.107 13.107 0 0 1-1.872-.892a.077.077 0 0 1-.008-.128a10.2 10.2 0 0 0 .372-.292a.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127a12.299 12.299 0 0 1-1.873.892a.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028a19.839 19.839 0 0 0 6.002-3.03a.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/>
        </svg>
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53a4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
        </svg>
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <circle cx="12" cy="12" r="10"/>
          <circle cx="12" cy="12" r="3"/>
          <path d="M12 5c3.87 0 7 3.13 7 7"/>
        </svg>
      </div>
    </div>
  </div>
  <div class="footer-bottom">© 2025 Ambience RPG. Todos os direitos reservados.</div>
</footer>

<script>
(function(){
  console.log('[carousel] Inicializando...');

  const section = document.querySelector('.carousel-section');
  if(!section){
    console.warn('[carousel] Seção não encontrada');
    return;
  }

  const slides = Array.from(section.querySelectorAll('.slide'));
  const bg = section.querySelector('.carousel-bg');
  const dotsWrap = section.querySelector('.dots');
  const prevBtn = document.getElementById('carousel-prev');
  const nextBtn = document.getElementById('carousel-next');

  if(slides.length === 0 || !prevBtn || !nextBtn){
    console.warn('[carousel] Elementos necessários não encontrados');
    return;
  }

  // Paletas de cores temáticas - baseadas nas imagens fornecidas
  const THEMES = {
    medieval: {
      accent: '#d4a574',
      accentLight: '#e6b888',
      accentDark: '#b8862f',
      headerBg: 'rgba(25, 20, 15, 0.75)',
      accentBorder: 'rgba(212, 165, 116, 0.4)',
      gradientStart: '#1a1410',
      gradientMid: '#2d2418',
      gradientEnd: '#3d3020',
      btnGradientStart: '#d4a574',
      btnGradientEnd: '#b8862f'
    },
    cyberpunk: {
      accent: '#ff54c8',
      accentLight: '#ff7dd6',
      accentDark: '#e030a8',
      headerBg: 'rgba(15, 10, 25, 0.75)',
      gradientStart: '#0f0a1a',
      gradientMid: '#1a0f2d',
      gradientEnd: '#2d1a45',
      accentBorder: 'rgba(255, 84, 200, 0.4)',
      btnGradientStart: '#ff54c8',
      btnGradientEnd: '#c430a0'
    },
    forest: {
      accent: '#7cb342',
      accentLight: '#9ccc65',
      accentDark: '#558b2f',
      accentBorder: 'rgba(124, 179, 66, 0.4)',
      headerBg: 'rgba(15, 20, 15, 0.75)',
      gradientStart: '#0f1410',
      gradientMid: '#1a2418',
      gradientEnd: '#253020',
      btnGradientStart: '#7cb342',
      btnGradientEnd: '#558b2f'
    }
  };

  const originalTheme = {
    accent: '#22c55e',
    accentLight: '#16a34a',
    accentDark: '#15803d',
    headerBg: 'rgba(10, 15, 20, 0.75)',
    gradientStart: '#052e16',
    gradientMid: '#064e3b',
    gradientEnd: '#065f46',
    btnGradientStart: '#22c55e',
    accentBorder: 'rgba(34, 197, 94, 0.4)',
    btnGradientEnd: '#16a34a'
  };

  let idx = slides.findIndex(s => s.classList.contains('active'));
  if(idx === -1) idx = 0;

  // Criar dots
  slides.forEach((s, i) => {
    const dot = document.createElement('button');
    dot.className = 'dot' + (i === idx ? ' active' : '');
    dot.setAttribute('aria-label', `Ir para slide ${i + 1}`);
    dot.addEventListener('click', () => goTo(i, {manual: true}));
    dotsWrap.appendChild(dot);
  });

  const dots = Array.from(dotsWrap.children);

  // Lazy loading das imagens
  slides.forEach(s => {
    const img = s.querySelector('img');
    if(img && !img.hasAttribute('loading')) {
      img.setAttribute('loading', 'lazy');
    }
  });

  function setActive(i) {
    slides.forEach((s, j) => s.classList.toggle('active', i === j));
    dots.forEach((d, j) => d.classList.toggle('active', i === j));
    
    const img = slides[i].querySelector('img');
    if(img && img.src && bg) {
      bg.style.backgroundImage = `url("${img.src}")`;
    }
  }

  function applyTheme(theme) {
    const root = document.documentElement;
    root.style.setProperty('--accent', theme.accent);
    root.style.setProperty('--accent-light', theme.accentLight);
    root.style.setProperty('--accent-dark', theme.accentDark);
    root.style.setProperty('--accent-border', theme.accentBorder);
    root.style.setProperty('--header-bg', theme.headerBg);
    root.style.setProperty('--gradient-start', theme.gradientStart);
    root.style.setProperty('--gradient-mid', theme.gradientMid);
    root.style.setProperty('--gradient-end', theme.gradientEnd);
    root.style.setProperty('--btn-gradient-start', theme.btnGradientStart);
    root.style.setProperty('--btn-gradient-end', theme.btnGradientEnd);
  }

  setActive(idx);

  // Auto-advance (sem trocar cores)
  let autoTimer = setInterval(() => {
    idx = (idx + 1) % slides.length;
    setActive(idx);
  }, 4500);

  function goTo(i, {manual = false} = {}) {
    const newIndex = ((i % slides.length) + slides.length) % slides.length;
    idx = newIndex;
    setActive(idx);
    
    if(manual) {
      const themeName = slides[idx].dataset.theme;
      if(themeName && THEMES[themeName]) {
        applyTheme(THEMES[themeName]);
      }
      
      clearInterval(autoTimer);
      autoTimer = setInterval(() => {
        idx = (idx + 1) % slides.length;
        setActive(idx);
      }, 4500);
    }
  }

  // Event listeners dos botões
  prevBtn.addEventListener('click', (e) => {
    e.preventDefault();
    goTo(idx - 1, {manual: true});
  });

  nextBtn.addEventListener('click', (e) => {
    e.preventDefault();
    goTo(idx + 1, {manual: true});
  });

  // Suporte a teclado
  document.addEventListener('keydown', (e) => {
    if(e.key === 'ArrowLeft') {
      e.preventDefault();
      goTo(idx - 1, {manual: true});
    }
    if(e.key === 'ArrowRight') {
      e.preventDefault();
      goTo(idx + 1, {manual: true});
    }
  });

  // Reverter tema quando sair da seção do carrossel
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if(!entry.isIntersecting) {
        applyTheme(originalTheme);
      }
    });
  }, {threshold: 0.3});

  observer.observe(section);

  console.log('[carousel] Inicializado com sucesso!');
})();

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