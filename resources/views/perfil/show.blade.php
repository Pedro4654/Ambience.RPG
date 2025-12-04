@extends('layout.app')

@section('title', 'Perfil de ' . $usuario->username . ' - Ambience RPG')

@section('content')
<style>
/* ============================================
   VARIÁVEIS - Cores do Ambience
   ============================================ */
:root {
  --bg-dark: #0a0f14;
  --bg-secondary: #151b23;
  --card-bg: rgba(26, 35, 50, 0.85);
  --card-hover: rgba(31, 42, 61, 0.9);
  --border-color: rgba(34,197,94,0.2);
  --accent: #22c55e;
  --accent-light: #16a34a;
  --accent-dark: #15803d;
  --text-primary: #e6eef6;
  --text-secondary: #8b9ba8;
  --text-muted: #64748b;
  --hero-green: #052e16;
}

/* ============================================
   SCROLLBAR PERSONALIZADA - IDÊNTICA AO SHOW.BLADE.PHP
   ============================================ */

::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: var(--bg-dark);
}

::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 5px;
    transition: background 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}

/* Scrollbar para modais */
.modal-body::-webkit-scrollbar,
.rpg-modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track,
.rpg-modal-body::-webkit-scrollbar-track {
    background: rgba(17, 24, 39, 0.6);
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb,
.rpg-modal-body::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 4px;
    transition: background 0.3s ease;
}

.modal-body::-webkit-scrollbar-thumb:hover,
.rpg-modal-body::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}

/* Scrollbar para listas */
.notification-list::-webkit-scrollbar {
    width: 6px;
}

.notification-list::-webkit-scrollbar-track {
    background: rgba(17, 24, 39, 0.4);
    border-radius: 3px;
}

.notification-list::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 3px;
    transition: background 0.3s ease;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}

/* ============================================
   BACKGROUND
   ============================================ */
.profile-container {
  position: relative;
  min-height: calc(100vh - 140px);
  padding: 24px 0 48px;
}

.profile-container::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url('{{ asset("images/rpg-background.gif") }}') center/cover;
  filter: blur(4px) brightness(0.5);
  z-index: -1;
}

.profile-container::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(10, 15, 20, 0.7);
  z-index: -1;
}

/* ============================================
   LAYOUT
   ============================================ */
.profile-content {
  max-width: 1440px;
  margin: 0 auto;
  padding: 0 24px;
  position: relative;
  z-index: 1;
}

/* ============================================
   BANNER
   ============================================ */
.profile-banner {
  position: relative;
  height: 280px;
  border-radius: 16px;
  overflow: hidden;
  margin-bottom: 24px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.banner-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, var(--accent-dark) 0%, var(--accent) 100%);
  opacity: 0.9;
}

.banner-background {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, var(--hero-green), var(--accent-dark), var(--accent));
  opacity: 0.7;
}

.banner-content {
  position: relative;
  z-index: 2;
  padding: 32px;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  color: var(--text-primary);
}

.banner-back-btn {
  position: absolute;
  top: 24px;
  left: 24px;
  padding: 10px 20px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 8px;
  color: var(--text-primary);
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 8px;
}

.banner-back-btn:hover {
  background: rgba(255, 255, 255, 0.2);
  border-color: var(--accent);
  color: var(--accent);
}

.banner-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 36px;
  font-weight: 900;
  margin-bottom: 8px;
  color: #fff;
}

.banner-subtitle {
  font-size: 16px;
  color: rgba(255, 255, 255, 0.9);
  max-width: 600px;
}

/* ============================================
   GRID PRINCIPAL
   ============================================ */
.profile-grid {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 24px;
  align-items: start;
}

@media (max-width: 1200px) {
  .profile-grid {
    grid-template-columns: 1fr;
  }
}

/* ============================================
   CARD DE PERFIL
   ============================================ */
.profile-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 24px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
  transition: all 0.3s;
}

.profile-card:hover {
  border-color: var(--accent);
  box-shadow: 0 8px 24px rgba(34, 197, 94, 0.15);
}

/* Avatar e Informações Básicas */
.profile-header {
  display: flex;
  align-items: flex-start;
  gap: 32px;
  margin-bottom: 32px;
}

.profile-avatar {
  position: relative;
  flex-shrink: 0;
}

.avatar-image {
  width: 140px;
  height: 140px;
  border-radius: 20px;
  object-fit: cover;
  border: 4px solid var(--card-bg);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
  background: linear-gradient(135deg, #064e3b, #052e16);
}

.avatar-default {
  width: 140px;
  height: 140px;
  border-radius: 20px;
  background: linear-gradient(135deg, #064e3b, #052e16);
  border: 4px solid var(--card-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 48px;
  font-weight: 900;
  color: var(--accent);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
}

.profile-info {
  flex: 1;
}

.profile-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 32px;
  font-weight: 900;
  color: var(--text-primary);
  margin-bottom: 8px;
}

.profile-bio {
  color: var(--text-secondary);
  font-size: 15px;
  line-height: 1.6;
  margin-bottom: 20px;
  max-width: 600px;
}

/* Redes Sociais */
.social-links {
  display: flex;
  gap: 16px;
  margin-bottom: 24px;
}

.social-link {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: white;
  text-decoration: none;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.social-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
}

.social-link.discord { background: #5865F2; }
.social-link.youtube { background: #FF0000; }
.social-link.twitch { background: #9147ff; }

/* Estatísticas */
.profile-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  background: rgba(34, 197, 94, 0.08);
  border: 1px solid rgba(34, 197, 94, 0.2);
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 24px;
}

.stat-item {
  text-align: center;
}

.stat-number {
  display: block;
  font-size: 28px;
  font-weight: 900;
  color: var(--accent);
  margin-bottom: 4px;
}

.stat-label {
  font-size: 13px;
  font-weight: 600;
  color: var(--text-secondary);
}

.stat-link {
  text-decoration: none;
  transition: all 0.2s;
}

.stat-link:hover .stat-number {
  color: var(--accent-light);
}

/* Botões de Ação */
.profile-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.action-btn {
  padding: 12px 24px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
  text-decoration: none;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.action-btn.primary {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
  color: #052e16;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.action-btn.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
}

.action-btn.secondary {
  background: rgba(34, 197, 94, 0.1);
  color: var(--accent);
  border: 1px solid rgba(34, 197, 94, 0.3);
}

.action-btn.secondary:hover {
  background: rgba(34, 197, 94, 0.15);
  border-color: var(--accent);
}

.action-btn.following {
  background: rgba(139, 155, 168, 0.2);
  color: var(--text-secondary);
  border: 1px solid var(--border-color);
}

.action-btn.following:hover {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
  border-color: #ef4444;
}

/* ============================================
   TABS
   ============================================ */
.tabs-container {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  overflow: hidden;
  margin-bottom: 24px;
}

.tabs-header {
  display: flex;
  border-bottom: 1px solid var(--border-color);
}

.tab-btn {
  flex: 1;
  padding: 20px;
  background: transparent;
  border: none;
  border-bottom: 3px solid transparent;
  color: var(--text-secondary);
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.tab-btn:hover {
  color: var(--accent);
  background: rgba(34, 197, 94, 0.05);
}

.tab-btn.active {
  color: var(--accent);
  border-bottom-color: var(--accent);
  background: rgba(34, 197, 94, 0.1);
}

.tab-content {
  padding: 24px;
}

/* ============================================
   GRID DE POSTS
   ============================================ */
.posts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
  margin-top: 16px;
}

.post-card {
  background: rgba(10, 15, 20, 0.4);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s;
  text-decoration: none;
  display: block;
}

.post-card:hover {
  border-color: var(--accent);
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(34, 197, 94, 0.2);
}

.post-image {
  width: 100%;
  height: 180px;
  object-fit: cover;
  background: linear-gradient(135deg, var(--hero-green), var(--accent-dark));
}

.post-default-image {
  width: 100%;
  height: 180px;
  background: linear-gradient(135deg, var(--hero-green), var(--accent-dark));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 48px;
  color: rgba(255, 255, 255, 0.3);
}

.post-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  padding: 6px 12px;
  background: rgba(34, 197, 94, 0.9);
  color: #052e16;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.post-content {
  padding: 16px;
}

.post-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 8px;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.post-excerpt {
  font-size: 13px;
  color: var(--text-secondary);
  margin-bottom: 12px;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.post-stats {
  display: flex;
  gap: 16px;
  font-size: 12px;
  color: var(--text-muted);
}

/* ============================================
   ESTADO VAZIO
   ============================================ */
.empty-state {
  text-align: center;
  padding: 60px 40px;
  color: var(--text-secondary);
}

.empty-state-icon {
  font-size: 64px;
  margin-bottom: 16px;
  opacity: 0.5;
}

.empty-state-title {
  font-size: 20px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 8px;
}

.empty-state-description {
  margin-bottom: 24px;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}

/* ============================================
   FORMULÁRIO DE EDIÇÃO (SIDEBAR)
   ============================================ */
.edit-sidebar {
  position: sticky;
  top: 100px;
}

.edit-form-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.edit-form-header {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
  padding: 20px 24px;
  border-bottom: 1px solid var(--border-color);
}

.edit-form-header h3 {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 900;
  color: #052e16;
  margin-bottom: 4px;
}

.edit-form-header p {
  color: rgba(5, 46, 22, 0.8);
  font-size: 13px;
}

.edit-form-body {
  padding: 24px;
}

.form-group {
  margin-bottom: 16px;
}

.form-label {
  display: block;
  font-size: 13px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 6px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.form-input {
  width: 100%;
  padding: 10px 12px;
  background: rgba(10, 15, 20, 0.6);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-primary);
  font-size: 14px;
  transition: all 0.2s;
}

.form-input:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.form-input.error {
  border-color: #ef4444;
  background: rgba(239, 68, 68, 0.05);
}

textarea.form-input {
  min-height: 100px;
  resize: vertical;
}

.error-message {
  display: none;
  color: #ef4444;
  font-size: 12px;
  margin-top: 4px;
  font-weight: 500;
}

/* ============================================
   PAGINAÇÃO
   ============================================ */
.pagination-container {
  margin-top: 32px;
  display: flex;
  justify-content: center;
}

/* ============================================
   SVG ICONS
   ============================================ */
.svg-icon {
  width: 1.2em;
  height: 1.2em;
  vertical-align: -0.15em;
  fill: currentColor;
  overflow: hidden;
}
</style>

<div class="profile-container">
  <div class="profile-content">
    <!-- Banner -->
    <div class="profile-banner">
      <div class="banner-background"></div>
      <div class="banner-overlay"></div>
     
      <div class="banner-content">
        <h1 class="banner-title">Perfil de {{ $usuario->username }}</h1>
        <p class="banner-subtitle">Visualize informações, postagens e redes sociais</p>
      </div>
    </div>

    <!-- Grid Principal -->
    <div class="profile-grid">
      <!-- Coluna Principal -->
      <div class="main-column">
        <!-- Card de Perfil -->
        <div class="profile-card">
          <div class="profile-header">
            <div class="profile-avatar">
              @if($usuario->avatar_url)
                <img src="{{ $usuario->avatar_url }}" alt="{{ $usuario->username }}" class="avatar-image">
              @else
                <div class="avatar-default">{{ strtoupper(substr($usuario->username, 0, 1)) }}</div>
              @endif
            </div>
            
            <div class="profile-info">
              <h1 class="profile-name">{{ $usuario->username }}</h1>
              
              <p class="profile-bio">
                @if($usuario->bio)
                  {{ $usuario->bio }}
                @else
                  <span style="color: var(--text-muted); font-style: italic;">Sem bio ainda...</span>
                @endif
              </p>

              <!-- Redes Sociais -->
              @if($usuario->discord_url || $usuario->youtube_url || $usuario->twitch_url)
                <div class="social-links">
                  @if($usuario->discord_url)
                    <a href="{{ $usuario->discord_url }}" target="_blank" class="social-link discord" title="Discord">
                      <i class="fa-brands fa-discord"></i>
                    </a>
                  @endif
                  @if($usuario->youtube_url)
                    <a href="{{ $usuario->youtube_url }}" target="_blank" class="social-link youtube" title="YouTube">
                      <i class="fa-brands fa-youtube"></i>
                    </a>
                  @endif
                  @if($usuario->twitch_url)
                    <a href="{{ $usuario->twitch_url }}" target="_blank" class="social-link twitch" title="Twitch">
                      <i class="fa-brands fa-twitch"></i>
                    </a>
                  @endif
                </div>
              @endif

              <!-- Estatísticas -->
              <div class="profile-stats">
                <div class="stat-item">
                  <span class="stat-number">{{ $posts->total() }}</span>
                  <span class="stat-label">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M896 192H128c-17.6 0-32 14.4-32 32v576c0 17.6 14.4 32 32 32h768c17.6 0 32-14.4 32-32V224c0-17.6-14.4-32-32-32z m-32 576H160V256h704v512z"/>
                      <path d="M256 320h512v64H256zM256 448h384v64H256zM256 576h256v64H256z"/>
                    </svg>
                    Posts
                  </span>
                </div>
                <a href="{{ route('perfil.seguidores', $usuario->id) }}" class="stat-item stat-link">
                  <span class="stat-number">{{ $usuario->seguidores()->count() }}</span>
                  <span class="stat-label">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M768 576c70.4 0 128-57.6 128-128s-57.6-128-128-128-128 57.6-128 128 57.6 128 128 128z m128 64h-256c-70.4 0-128 57.6-128 128v64h512v-64c0-70.4-57.6-128-128-128zM384 576c70.4 0 128-57.6 128-128s-57.6-128-128-128-128 57.6-128 128 57.6 128 128 128z m128 64H256c-70.4 0-128 57.6-128 128v64h384v-64c0-70.4-57.6-128-128-128z"/>
                    </svg>
                    Seguidores
                  </span>
                </a>
                <a href="{{ route('perfil.seguindo', $usuario->id) }}" class="stat-item stat-link">
                  <span class="stat-number">{{ $usuario->seguindo()->count() }}</span>
                  <span class="stat-label">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M896 576c70.4 0 128-57.6 128-128s-57.6-128-128-128-128 57.6-128 128 57.6 128 128 128z m128 64h-256c-70.4 0-128 57.6-128 128v64h512v-64c0-70.4-57.6-128-128-128z"/>
                    </svg>
                    Seguindo
                  </span>
                </a>
              </div>

              <!-- Botões de Ação -->
              <div class="profile-actions">
                @auth
                  @if(Auth::id() === $usuario->id)
                    <a href="{{ route('comunidade.feed') }}" class="action-btn secondary">
                      <svg class="svg-icon" viewBox="0 0 1024 1024">
                        <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                        <path d="M320 320h384v64H320zM320 448h256v64H320z"/>
                      </svg>
                      Ver Feed
                    </a>
                    <a href="{{ route('comunidade.create') }}" class="action-btn primary">
                      <svg class="svg-icon" viewBox="0 0 1024 1024">
                        <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                        <path d="M480 320h64v192h192v64H480zM320 480h192V320h-64v192H320z"/>
                      </svg>
                      Criar Postagem
                    </a>
                  @else
                    @if($esta_seguindo)
                      <form action="{{ route('perfil.deixar_de_seguir', $usuario->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn following" onclick="return confirm('Deixar de seguir {{ $usuario->username }}?')">
                          <svg class="svg-icon" viewBox="0 0 1024 1024">
                            <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                            <path d="M672 352l-160 160-160-160-64 64 224 224 224-224z"/>
                          </svg>
                          Seguindo
                        </button>
                      </form>
                    @else
                      <form action="{{ route('perfil.seguir', $usuario->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="action-btn primary">
                          <svg class="svg-icon" viewBox="0 0 1024 1024">
                            <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                            <path d="M480 320h64v192h192v64H480zM320 480h192V320h-64v192H320z"/>
                          </svg>
                          Seguir
                        </button>
                      </form>
                    @endif
                  
                  @endif
                @else
                  <a href="{{ route('usuarios.login') }}" class="action-btn primary">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                      <path d="M416 448l192 128-192 128z"/>
                    </svg>
                    Fazer Login para Seguir
                  </a>
                @endauth
              </div>
            </div>
          </div>
        </div>

        <!-- Tabs -->
        <div class="tabs-container">
          <div class="tabs-header">
            <button class="tab-btn active" onclick="mostrarAba('posts')" id="tab-posts">
              <svg class="svg-icon" viewBox="0 0 1024 1024">
                <path d="M896 192H128c-17.6 0-32 14.4-32 32v576c0 17.6 14.4 32 32 32h768c17.6 0 32-14.4 32-32V224c0-17.6-14.4-32-32-32z m-32 576H160V256h704v512z"/>
                <path d="M256 320h512v64H256zM256 448h384v64H256zM256 576h256v64H256z"/>
              </svg>
              Postagens
            </button>
            @auth
              @if(Auth::id() === $usuario->id)
                <button class="tab-btn" onclick="mostrarAba('salvos')" id="tab-salvos">
                  <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                    <path d="M384 448l128 128 224-224 64 64-288 288-192-192z"/>
                  </svg>
                  Salvos
                </button>
              @endif
            @endauth
          </div>

          <!-- Conteúdo da Aba Posts -->
          <div class="tab-content" id="aba-posts">
            @if($posts->count() > 0)
              <div class="posts-grid">
                @foreach($posts as $post)
                  <a href="{{ route('comunidade.post.show', $post->slug) }}" class="post-card">
                    <div class="relative">
                      @if($post->arquivos->first() && $post->arquivos->first()->tipo === 'imagem')
                        <img src="{{ $post->arquivos->first()->url }}" alt="{{ $post->titulo }}" class="post-image">
                      @else
                        <div class="post-default-image">
                          <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 48px; height: 48px; opacity: 0.3;">
                            <path d="M896 192H128c-17.6 0-32 14.4-32 32v576c0 17.6 14.4 32 32 32h768c17.6 0 32-14.4 32-32V224c0-17.6-14.4-32-32-32z m-32 576H160V256h704v512z"/>
                            <path d="M256 320h512v64H256zM256 448h384v64H256zM256 576h256v64H256z"/>
                          </svg>
                        </div>
                      @endif
                      <span class="post-badge">{{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}</span>
                    </div>
                    <div class="post-content">
                      <h3 class="post-title">{{ $post->titulo }}</h3>
                      <p class="post-excerpt">{{ Str::limit($post->conteudo, 120) }}</p>
                      <div class="post-stats">
                        <span>
                          <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 12px; height: 12px;">
                            <path d="M512 832c-211.2 0-384-172.8-384-384 0-185.6 124.8-345.6 307.2-390.4 17.6-4.8 35.2 6.4 40 24 4.8 17.6-6.4 35.2-24 40-150.4 35.2-259.2 169.6-259.2 326.4 0 176 144 320 320 320s320-144 320-320c0-156.8-108.8-291.2-259.2-326.4-17.6-4.8-28.8-22.4-24-40s22.4-28.8 40-24c182.4 44.8 307.2 204.8 307.2 390.4 0 211.2-172.8 384-384 384z"/>
                          </svg>
                          {{ $post->curtidas()->count() }}
                        </span>
                        <span>
                          <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 12px; height: 12px;">
                            <path d="M832 64H192c-70.4 0-128 57.6-128 128v384c0 70.4 57.6 128 128 128h128v128l192-128h320c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 512H416l-96 64v-64H256V192h512v384z"/>
                          </svg>
                          {{ $post->comentarios()->count() }}
                        </span>
                      </div>
                    </div>
                  </a>
                @endforeach
              </div>
              
              <!-- Paginação -->
              @if($posts->hasPages())
                <div class="pagination-container">
                  {{ $posts->links() }}
                </div>
              @endif
            @else
              <div class="empty-state">
                <div class="empty-state-icon">
                  <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 64px; height: 64px;">
                    <path d="M896 192H128c-17.6 0-32 14.4-32 32v576c0 17.6 14.4 32 32 32h768c17.6 0 32-14.4 32-32V224c0-17.6-14.4-32-32-32z m-32 576H160V256h704v512z"/>
                    <path d="M256 320h512v64H256zM256 448h384v64H256zM256 576h256v64H256z"/>
                  </svg>
                </div>
                <h3 class="empty-state-title">Nenhuma postagem ainda</h3>
                <p class="empty-state-description">Compartilhe conteúdo com a comunidade</p>
                @auth
                  @if(Auth::id() === $usuario->id)
                    <a href="{{ route('comunidade.create') }}" class="action-btn primary" style="display: inline-block;">
                      <svg class="svg-icon" viewBox="0 0 1024 1024">
                        <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                        <path d="M480 320h64v192h192v64H480zM320 480h192V320h-64v192H320z"/>
                      </svg>
                      Criar Primeira Postagem
                    </a>
                  @endif
                @endauth
              </div>
            @endif
          </div>

          <!-- Conteúdo da Aba Salvos -->
          @auth
            @if(Auth::id() === $usuario->id)
              <div class="tab-content" id="aba-salvos" style="display: none;">
                @if($usuario->saved_posts()->count() > 0)
                  <div class="posts-grid">
                    @foreach($usuario->saved_posts as $saved)
                      @php $post = $saved->post; @endphp
                      <div class="post-card">
                        <div class="relative">
                          @if($post->arquivos->first() && $post->arquivos->first()->tipo === 'imagem')
                            <img src="{{ $post->arquivos->first()->url }}" alt="{{ $post->titulo }}" class="post-image">
                          @else
                            <div class="post-default-image">
                              <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 48px; height: 48px; opacity: 0.3;">
                                <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                                <path d="M384 448l128 128 224-224 64 64-288 288-192-192z"/>
                              </svg>
                            </div>
                          @endif
                          <span class="post-badge" style="background: #eab308; color: #052e16;">
                            <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 11px; height: 11px;">
                              <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                              <path d="M384 448l128 128 224-224 64 64-288 288-192-192z"/>
                            </svg>
                            Salvo
                          </span>
                        </div>
                        <div class="post-content">
                          <h3 class="post-title">{{ $post->titulo }}</h3>
                          <p class="post-excerpt">{{ Str::limit($post->conteudo, 120) }}</p>
                          <div class="post-stats">
                            <span>
                              <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 12px; height: 12px;">
                                <path d="M512 832c-211.2 0-384-172.8-384-384 0-185.6 124.8-345.6 307.2-390.4 17.6-4.8 35.2 6.4 40 24 4.8 17.6-6.4 35.2-24 40-150.4 35.2-259.2 169.6-259.2 326.4 0 176 144 320 320 320s320-144 320-320c0-156.8-108.8-291.2-259.2-326.4-17.6-4.8-28.8-22.4-24-40s22.4-28.8 40-24c182.4 44.8 307.2 204.8 307.2 390.4 0 211.2-172.8 384-384 384z"/>
                              </svg>
                              {{ $post->curtidas()->count() }}
                            </span>
                            <span>
                              <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 12px; height: 12px;">
                                <path d="M832 64H192c-70.4 0-128 57.6-128 128v384c0 70.4 57.6 128 128 128h128v128l192-128h320c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 512H416l-96 64v-64H256V192h512v384z"/>
                              </svg>
                              {{ $post->comentarios()->count() }}
                            </span>
                          </div>
                          <div style="display: flex; gap: 8px; margin-top: 12px;">
                            <a href="{{ route('comunidade.post.show', $post->slug) }}" class="action-btn secondary" style="flex: 1; padding: 8px; text-align: center;">
                              Ver Post
                            </a>
                            <form action="{{ route('comunidade.desalvar', $post->id) }}" method="POST" style="flex: 1;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="action-btn following" style="width: 100%; padding: 8px;" onclick="return confirm('Remover dos salvos?')">
                                <svg class="svg-icon" viewBox="0 0 1024 1024">
                                  <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                                  <path d="M320 320h384v384H320z"/>
                                </svg>
                              </button>
                            </form>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="empty-state">
                    <div class="empty-state-icon">
                      <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 64px; height: 64px;">
                        <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                        <path d="M384 448l128 128 224-224 64 64-288 288-192-192z"/>
                      </svg>
                    </div>
                    <h3 class="empty-state-title">Nenhuma postagem salva</h3>
                    <p class="empty-state-description">Salve suas postagens favoritas para acessá-las rapidamente</p>
                    <a href="{{ route('comunidade.feed') }}" class="action-btn primary" style="display: inline-block;">
                      <svg class="svg-icon" viewBox="0 0 1024 1024">
                        <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                        <path d="M320 320h384v64H320zM320 448h256v64H320z"/>
                      </svg>
                      Explorar Feed
                    </a>
                  </div>
                @endif
              </div>
            @endif
          @endauth
        </div>
      </div>

      <!-- Sidebar de Edição -->
      @auth
        @if(Auth::id() === $usuario->id)
          <div class="edit-sidebar">
            <div class="edit-form-card">
              <div class="edit-form-header">
                <h3>
                  <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M878.08 688c26.88-46.72 41.92-100.48 41.92-160 0-185.6-150.4-336-336-336s-336 150.4-336 336 150.4 336 336 336c59.52 0 113.28-15.04 160-41.92l158.08 158.08c12.48 12.48 32.64 12.48 45.12 0s12.48-32.64 0-45.12L878.08 688z m-158.08 0c-123.52 0-224-100.48-224-224s100.48-224 224-224 224 100.48 224 224-100.48 224-224 224z"/>
                    <path d="M224 512c0-17.6 14.4-32 32-32s32 14.4 32 32-14.4 32-32 32-32-14.4-32-32zM416 512c0-17.6 14.4-32 32-32s32 14.4 32 32-14.4 32-32 32-32-14.4-32-32zM608 512c0-17.6 14.4-32 32-32s32 14.4 32 32-14.4 32-32 32-32-14.4-32-32z"/>
                  </svg>
                  Editar Perfil
                </h3>
                <p>Atualize sua bio e redes sociais</p>
              </div>
              
              <form action="{{ route('perfil.update') }}" method="POST" class="edit-form-body" id="form-editar-perfil">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                  <label class="form-label">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M896 192H128c-17.6 0-32 14.4-32 32v576c0 17.6 14.4 32 32 32h768c17.6 0 32-14.4 32-32V224c0-17.6-14.4-32-32-32z m-32 576H160V256h704v512z"/>
                      <path d="M256 320h512v64H256zM256 448h384v64H256zM256 576h256v64H256z"/>
                    </svg>
                    Bio (até 500 caracteres)
                  </label>
                  <textarea name="bio" id="bio" maxlength="500" class="form-input" placeholder="Conte um pouco sobre você...">{{ old('bio', Auth::user()->bio) }}</textarea>
                  <small id="bio-warning" class="error-message">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                      <path d="M512 320c-17.6 0-32 14.4-32 32v192c0 17.6 14.4 32 32 32s32-14.4 32-32V352c0-17.6-14.4-32-32-32zM512 576c-17.6 0-32 14.4-32 32s14.4 32 32 32 32-14.4 32-32-14.4-32-32-32z"/>
                    </svg>
                    Conteúdo inapropriado detectado
                  </small>
                  @error('bio')
                    <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                  @enderror
                </div>
                
                <div class="form-group">
                  <label class="form-label">
                    <svg class="svg-icon" viewBox="0 0 1024 1024" style="color:#5865F2">
                      <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                      <path d="M416 320h192v64H416zM320 448h384v64H320zM416 576h192v64H416z"/>
                    </svg>
                    Discord
                  </label>
                  <input type="url" name="discord_url" id="discord_url" placeholder="https://discord.gg/seu-link" value="{{ old('discord_url', Auth::user()->discord_url) }}" class="form-input">
                  <small id="discord-warning" class="error-message">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                      <path d="M512 320c-17.6 0-32 14.4-32 32v192c0 17.6 14.4 32 32 32s32-14.4 32-32V352c0-17.6-14.4-32-32-32zM512 576c-17.6 0-32 14.4-32 32s14.4 32 32 32 32-14.4 32-32-14.4-32-32-32z"/>
                    </svg>
                    Conteúdo inapropriado detectado
                  </small>
                  @error('discord_url')
                    <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                  @enderror
                </div>
                
                <div class="form-group">
                  <label class="form-label">
                    <svg class="svg-icon" viewBox="0 0 1024 1024" style="color:#FF0000">
                      <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                      <path d="M416 448l192 128-192 128z"/>
                    </svg>
                    YouTube
                  </label>
                  <input type="url" name="youtube_url" id="youtube_url" placeholder="https://youtube.com/@seu-canal" value="{{ old('youtube_url', Auth::user()->youtube_url) }}" class="form-input">
                  <small id="youtube-warning" class="error-message">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                      <path d="M512 320c-17.6 0-32 14.4-32 32v192c0 17.6 14.4 32 32 32s32-14.4 32-32V352c0-17.6-14.4-32-32-32zM512 576c-17.6 0-32 14.4-32 32s14.4 32 32 32 32-14.4 32-32-14.4-32-32-32z"/>
                    </svg>
                    Conteúdo inapropriado detectado
                  </small>
                  @error('youtube_url')
                    <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                  @enderror
                </div>
                
                <div class="form-group">
                  <label class="form-label">
                    <svg class="svg-icon" viewBox="0 0 1024 1024" style="color:#9147ff">
                      <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                      <path d="M416 320h192v64H416zM320 448h384v64H320zM416 576h192v64H416z"/>
                    </svg>
                    Twitch
                  </label>
                  <input type="url" name="twitch_url" id="twitch_url" placeholder="https://twitch.tv/seu-user" value="{{ old('twitch_url', Auth::user()->twitch_url) }}" class="form-input">
                  <small id="twitch-warning" class="error-message">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                      <path d="M512 320c-17.6 0-32 14.4-32 32v192c0 17.6 14.4 32 32 32s32-14.4 32-32V352c0-17.6-14.4-32-32-32zM512 576c-17.6 0-32 14.4-32 32s14.4 32 32 32 32-14.4 32-32-14.4-32-32-32z"/>
                    </svg>
                    Conteúdo inapropriado detectado
                  </small>
                  @error('twitch_url')
                    <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                  @enderror
                </div>
                
                <div class="form-group">
                  <label class="form-label">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                      <path d="M448 320l128 192 128-192h-64v-64h-128v64zM320 576h384v64H320z"/>
                    </svg>
                    Website (opcional)
                  </label>
                  <input type="url" name="website" id="website" placeholder="https://seu-site.com" value="{{ old('website', Auth::user()->website) }}" class="form-input">
                  <small id="website-warning" class="error-message">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                      <path d="M512 320c-17.6 0-32 14.4-32 32v192c0 17.6 14.4 32 32 32s32-14.4 32-32V352c0-17.6-14.4-32-32-32zM512 576c-17.6 0-32 14.4-32 32s14.4 32 32 32 32-14.4 32-32-14.4-32-32-32z"/>
                    </svg>
                    Conteúdo inapropriado detectado
                  </small>
                  @error('website')
                    <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                  @enderror
                </div>
                
                <button type="submit" class="action-btn primary" style="width: 100%; margin-top: 8px;">
                  <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                    <path d="M448 320l128 128 192-192 64 64-256 256-192-192z"/>
                  </svg>
                  Salvar Alterações
                </button>
              </form>
            </div>
          </div>
        @endif
      @endauth
    </div>
  </div>
</div>

<script src="{{ asset('js/moderation.js') }}" defer></script>

<script>
// Sistema de Tabs
function mostrarAba(aba) {
  // Esconder todas as abas
  document.getElementById('aba-posts').style.display = 'none';
  const abaSalvos = document.getElementById('aba-salvos');
  if (abaSalvos) abaSalvos.style.display = 'none';
  
  // Remover classe active de todas as tabs
  document.getElementById('tab-posts').classList.remove('active');
  const tabSalvos = document.getElementById('tab-salvos');
  if (tabSalvos) tabSalvos.classList.remove('active');
  
  // Mostrar aba selecionada
  if (aba === 'posts') {
    document.getElementById('aba-posts').style.display = 'block';
    document.getElementById('tab-posts').classList.add('active');
  } else if (aba === 'salvos' && abaSalvos) {
    abaSalvos.style.display = 'block';
    tabSalvos.classList.add('active');
  }
}

// Font Awesome
if (!document.querySelector('link[href*="font-awesome"]')) {
  const faLink = document.createElement('link');
  faLink.rel = 'stylesheet';
  faLink.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css';
  document.head.appendChild(faLink);
}

// MODERAÇÃO DE TEXTO NO FORMULÁRIO DE EDIÇÃO DE PERFIL
document.addEventListener('DOMContentLoaded', async function() {
  // Inicializar sistema de moderação
  const state = await window.Moderation.init({
    csrfToken: '{{ csrf_token() }}',
    endpoint: '/moderate',
    debounceMs: 120,
  });

  // Função para aplicar warning em qualquer campo
  function applyWarning(elementId, result) {
    const el = document.getElementById(elementId);
    const warn = document.getElementById(elementId + '-warning');
    
    if (!el) return;
    
    if (result && result.inappropriate) {
      el.classList.add('error');
      if (warn) warn.style.display = 'block';
    } else {
      el.classList.remove('error');
      if (warn) warn.style.display = 'none';
    }
  }

  // Lista de campos para moderar no formulário de edição de perfil
  const camposParaModerar = [
    { id: 'bio', name: 'bio' },
    { id: 'discord_url', name: 'discord_url' },
    { id: 'youtube_url', name: 'youtube_url' },
    { id: 'twitch_url', name: 'twitch_url' },
    { id: 'website', name: 'website' }
  ];

  // Aplicar moderação a todos os campos
  camposParaModerar.forEach(campo => {
    window.Moderation.attachInput('#' + campo.id, campo.name, {
      onLocal: (res) => applyWarning(campo.id, res),
      onServer: (srv) => {
        if (srv && srv.data && srv.data.inappropriate) {
          applyWarning(campo.id, { inappropriate: true });
        }
      }
    });
  });

  // Validar formulário antes do envio
  const form = document.getElementById('form-editar-perfil');
  if (form) {
    form.addEventListener('submit', function(e) {
      let hasErrors = false;
      
      // Verificar todos os campos com classe 'error'
      const camposComErro = document.querySelectorAll('.form-input.error');
      
      if (camposComErro.length > 0) {
        hasErrors = true;
        const campos = Array.from(camposComErro).map(campo => {
          const label = campo.previousElementSibling?.textContent || campo.id;
          return `• ${label}`;
        }).join('\n');
        
        alert(`⚠️ Conteúdo inapropriado detectado nos seguintes campos:\n\n${campos}\n\nCorrija antes de continuar.`);
      }
      
      // Se houver erros, prevenir envio
      if (hasErrors) {
        e.preventDefault();
        return false;
      }
      
      // Tudo ok, pode enviar
      return true;
    });
  }
});
</script>
@endsection