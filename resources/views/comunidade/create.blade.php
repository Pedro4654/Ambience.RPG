{{-- ============================================================
VIEW: create.blade.php - REFORMULADO COM FICHA RPG
============================================================
DESCRIÇÃO:
- Design moderno seguindo estilo do feed
- Sistema de Ficha RPG completo com preview
- Tipos: Texto, Imagem, Vídeo, Ficha RPG, Outros
- Sistema de moderação integrado
- Preview ao vivo para todos os tipos
============================================================ --}}

@extends('layout.app')

@section('content')



<style>
/* ============================================
   VARIÁVEIS - Mesmas do Feed
   ============================================ */
:root {
  --bg-dark: #0a0f14;
  --bg-secondary: #151b23;
  --card-bg: rgba(26, 35, 50, 0.9);
  --card-light: rgba(31, 42, 51, 0.7);
  --border-color: rgba(34,197,94,0.2);
  --accent: #22c55e;
  --accent-light: #16a34a;
  --text-primary: #e6eef6;
  --text-secondary: #8b9ba8;
  --text-muted: #64748b;
}

/* ============================================
   BACKGROUND
   ============================================ */
.create-wrapper {
  min-height: 100vh;
  position: relative;
}

.create-wrapper::before {
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

.create-wrapper::after {
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
   CONTAINER PRINCIPAL
   ============================================ */
.create-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 40px 24px;
}

/* ============================================
   HEADER
   ============================================ */
.create-header {
  text-align: center;
  margin-bottom: 40px;
  animation: fadeInDown 0.6s ease;
}

.create-header h1 {
  font-family: 'Montserrat', sans-serif;
  font-size: 42px;
  font-weight: 900;
  color: #fff;
  margin-bottom: 12px;
  letter-spacing: 1px;
  text-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
}

.create-header svg {
  width: 48px;
  height: 48px;
  color: #fff;
  filter: drop-shadow(0 2px 8px rgba(34, 197, 94, 0.3));
}

.create-header p {
  color: var(--text-secondary);
  font-size: 16px;
}

/* ============================================
   FORM CARD
   ============================================ */
.form-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
  animation: fadeInUp 0.6s ease;
}

.form-section {
  padding: 32px;
  border-bottom: 1px solid var(--border-color);
}

.form-section:last-child {
  border-bottom: none;
}

.form-section-title {
  font-size: 18px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

/* ============================================
   TIPO DE CONTEÚDO - CARDS
   ============================================ */
.tipo-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 12px;
}

.tipo-card {
  position: relative;
  cursor: pointer;
  transition: all 0.3s;
}

.tipo-card input[type="radio"] {
  display: none;
}

.tipo-card-inner {
  background: rgba(10, 15, 20, 0.6);
  border: 2px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  transition: all 0.3s;
}

.tipo-card:hover .tipo-card-inner {
  transform: translateY(-4px);
  border-color: var(--accent);
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
}

.tipo-card input[type="radio"]:checked + .tipo-card-inner {
  background: linear-gradient(135deg, rgba(6, 78, 59, 0.4), rgba(5, 46, 22, 0.3));
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
}

.tipo-icon {
  font-size: 36px;
  margin-bottom: 8px;
  filter: grayscale(1);
  transition: filter 0.3s;
}

.tipo-card input[type="radio"]:checked + .tipo-card-inner .tipo-icon {
  filter: grayscale(0);
}

.tipo-label {
  font-size: 14px;
  font-weight: 700;
  color: var(--text-muted);
  transition: color 0.3s;
}

.tipo-card input[type="radio"]:checked + .tipo-card-inner .tipo-label {
  color: var(--accent);
}

/* ============================================
   CAMPOS DE FORMULÁRIO
   ============================================ */
.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 10px;
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 14px 18px;
  background: rgba(10, 15, 20, 0.8);
  border: 2px solid var(--border-color);
  border-radius: 10px;
  color: #fff;
  font-size: 15px;
  font-family: 'Inter', sans-serif;
  transition: all 0.3s;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: var(--accent);
  background: rgba(10, 15, 20, 0.95);
  box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
}

.form-input::placeholder,
.form-textarea::placeholder {
  color: var(--text-muted);
}

.form-textarea {
  resize: vertical;
  min-height: 150px;
}

.char-counter {
  text-align: right;
  font-size: 13px;
  color: var(--text-muted);
  margin-top: 6px;
}

.error-message {
  display: none;
  color: #ef4444;
  font-size: 13px;
  margin-top: 8px;
}

.form-input.error,
.form-textarea.error {
  border-color: #ef4444;
  background: rgba(239, 68, 68, 0.05);
}

/* ============================================
   UPLOAD DE ARQUIVOS
   ============================================ */
.upload-zone {
  border: 2px dashed var(--border-color);
  border-radius: 12px;
  padding: 40px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s;
  position: relative;
  background: rgba(10, 15, 20, 0.4);
}

.upload-zone:hover {
  border-color: var(--accent);
  background: rgba(34, 197, 94, 0.05);
}

.upload-zone input[type="file"] {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
}

.upload-icon {
  margin-bottom: 12px;
  transition: transform 0.3s;
  position: absolute;
}

.upload-zone:hover .upload-icon {
  transform: scale(1.1);
}

.upload-text {
  color: #fff;
  font-weight: 600;
  margin-bottom: 8px;
}

.upload-hint {
  color: var(--text-muted);
  font-size: 13px;
}

.preview-container {
  margin-top: 20px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 12px;
}

.preview-item {
  position: relative;
  border-radius: 10px;
  overflow: hidden;
  background: rgba(10, 15, 20, 0.6);
  border: 1px solid var(--border-color);
}

.preview-item img,
.preview-item video {
  width: 100%;
  height: 200px;
  object-fit: cover;
  display: block;
}

.preview-remove {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 28px;
  height: 28px;
  background: rgba(239, 68, 68, 0.9);
  border: none;
  border-radius: 50%;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.preview-remove:hover {
  background: #ef4444;
  transform: scale(1.1);
}

/* ============================================
   FICHA RPG - LAYOUT ESPECIAL
   ============================================ */
.ficha-container {
  background: linear-gradient(145deg, rgba(5, 46, 22, 0.2), rgba(6, 78, 59, 0.1));
  border: 2px solid rgba(34, 197, 94, 0.3);
  border-radius: 16px;
  padding: 28px;
}

.ficha-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
  margin-bottom: 20px;
}

.ficha-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-top: 20px;
}

.stat-box {
  background: rgba(10, 15, 20, 0.6);
  border: 1px solid var(--border-color);
  border-radius: 10px;
  padding: 12px;
  text-align: center;
}

.stat-label {
  font-size: 11px;
  font-weight: 700;
  color: var(--accent);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
}

.stat-input {
  width: 100%;
  background: rgba(10, 15, 20, 0.8);
  border: 1px solid var(--border-color);
  border-radius: 6px;
  padding: 8px;
  text-align: center;
  color: #fff;
  font-weight: 700;
  font-size: 18px;
}

.stat-input:focus {
  outline: none;
  border-color: var(--accent);
}

/* Preview da Ficha */
.ficha-preview {
  margin-top: 28px;
  padding-top: 28px;
  border-top: 2px solid rgba(34, 197, 94, 0.2);
}

.ficha-preview-title {
  font-size: 16px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 16px;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.ficha-card-preview {
  background: linear-gradient(145deg, rgba(10, 15, 20, 0.9), rgba(6, 78, 59, 0.2));
  border: 2px solid var(--accent);
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 8px 24px rgba(34, 197, 94, 0.2);
}

.ficha-header-preview {
  display: flex;
  gap: 20px;
  align-items: start;
  margin-bottom: 20px;
  padding-bottom: 20px;
  border-bottom: 1px solid rgba(34, 197, 94, 0.2);
}

.ficha-avatar-preview {
  width: 100px;
  height: 100px;
  border-radius: 10px;
  object-fit: cover;
  border: 2px solid var(--accent);
  background: rgba(10, 15, 20, 0.8);
}

.ficha-info-preview h3 {
  font-size: 24px;
  font-weight: 900;
  color: var(--accent);
  margin-bottom: 6px;
}

.ficha-info-preview p {
  color: var(--text-secondary);
  font-size: 14px;
}

.ficha-stats-preview {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-bottom: 16px;
}

.stat-preview-box {
  background: rgba(34, 197, 94, 0.1);
  border: 1px solid rgba(34, 197, 94, 0.3);
  border-radius: 8px;
  padding: 10px;
  text-align: center;
}

.stat-preview-label {
  font-size: 10px;
  font-weight: 700;
  color: var(--accent);
  text-transform: uppercase;
  margin-bottom: 4px;
}

.stat-preview-value {
  font-size: 24px;
  font-weight: 900;
  color: #fff;
}

.ficha-section-preview {
  margin-top: 16px;
}

.ficha-section-preview h4 {
  font-size: 13px;
  font-weight: 700;
  color: var(--accent);
  text-transform: uppercase;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.ficha-section-preview p {
  color: var(--text-secondary);
  font-size: 14px;
  line-height: 1.6;
}

/* ============================================
   FOOTER - BOTÕES
   ============================================ */
.form-footer {
  padding: 24px 32px;
  background: rgba(5, 46, 22, 0.1);
  border-top: 1px solid var(--border-color);
  display: flex;
  gap: 12px;
}

.btn {
  flex: 1;
  padding: 14px 24px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 15px;
  border: none;
  cursor: pointer;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  text-decoration: none;
  font-family: 'Inter', sans-serif;
}

.btn-primary {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
  color: #052e16;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.05);
  border: 2px solid var(--border-color);
  color: var(--text-secondary);
}

.btn-secondary:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: var(--accent);
  color: #fff;
}

/* ============================================
   ANIMAÇÕES
   ============================================ */
@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.hidden {
  display: none !important;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 768px) {
  .create-header h1 {
    font-size: 32px;
  }
  
  .create-header svg {
    width: 36px;
    height: 36px;
  }
  
  .tipo-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .ficha-grid {
    grid-template-columns: 1fr;
  }
  
  .ficha-stats,
  .ficha-stats-preview {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .form-footer {
    flex-direction: column;
  }
}
</style>

<div class="create-wrapper">
  <div class="create-container">
    
    {{-- ========== HEADER ========== --}}
    <div class="create-header">
      <h1>
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
        </svg>
        CRIAR POSTAGEM
      </h1>
      <p>Compartilhe seu conteúdo com a comunidade RPG</p>
    </div>

    {{-- ========== FORMULÁRIO ========== --}}
    <form action="{{ route('comunidade.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="form-criar-post"
          class="form-card">
      @csrf

      {{-- ========== SEÇÃO 1: TIPO DE CONTEÚDO ========== --}}
      <div class="form-section">
        <div class="form-section-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <circle cx="12" cy="12" r="6"></circle>
            <circle cx="12" cy="12" r="2"></circle>
          </svg>
          Tipo de Conteúdo
        </div>
        
        <div class="tipo-grid">
          {{-- TEXTO --}}
          <label class="tipo-card">
            <input type="radio" name="tipo_conteudo" value="texto" required>
            <div class="tipo-card-inner" onclick="mostrarCamposTipo('texto')">
              <div class="tipo-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
              </div>
              <div class="tipo-label">Texto</div>
            </div>
          </label>

          {{-- IMAGEM --}}
          <label class="tipo-card">
            <input type="radio" name="tipo_conteudo" value="imagem">
            <div class="tipo-card-inner" onclick="mostrarCamposTipo('imagem')">
              <div class="tipo-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
              </div>
              <div class="tipo-label">Imagem</div>
            </div>
          </label>

          {{-- VÍDEO --}}
          <label class="tipo-card">
            <input type="radio" name="tipo_conteudo" value="video">
            <div class="tipo-card-inner" onclick="mostrarCamposTipo('video')">
              <div class="tipo-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="23 7 16 12 23 17 23 7"></polygon>
                  <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                </svg>
              </div>
              <div class="tipo-label">Vídeo</div>
            </div>
          </label>

          {{-- FICHA RPG --}}
          <label class="tipo-card">
            <input type="radio" name="tipo_conteudo" value="ficha_rpg">
            <div class="tipo-card-inner" onclick="mostrarCamposTipo('ficha_rpg')">
              <div class="tipo-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <path d="M15 3v18"></path>
                  <path d="M3 9h18"></path>
                  <path d="M3 15h18"></path>
                </svg>
              </div>
              <div class="tipo-label">Ficha RPG</div>
            </div>
          </label>

          {{-- OUTROS --}}
          <label class="tipo-card">
            <input type="radio" name="tipo_conteudo" value="outros">
            <div class="tipo-card-inner" onclick="mostrarCamposTipo('outros')">
              <div class="tipo-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10 20v-6m4 6v-6m4 6v-6m-8-4v-4h8v4"></path>
                  <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                </svg>
              </div>
              <div class="tipo-label">Outros</div>
            </div>
          </label>
        </div>

        @error('tipo_conteudo')
          <p class="error-message" style="display:block">{{ $message }}</p>
        @enderror
      </div>

      {{-- ========== SEÇÃO 2: CAMPOS COMUNS (Título e Descrição) ========== --}}
      <div id="campos-comuns" class="form-section hidden">
        <div class="form-section-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9"></path>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
          </svg>
          Informações Básicas
        </div>

        {{-- Título --}}
        <div class="form-group">
          <label for="titulo" class="form-label">Título *</label>
          <input 
            type="text" 
            id="titulo" 
            name="titulo" 
            required
            maxlength="200"
            placeholder="Dê um título chamativo para sua postagem..."
            value="{{ old('titulo') }}"
            class="form-input"
          >
          <small id="titulo-warning" class="error-message">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; margin-right:4px">
              <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
              <line x1="12" y1="9" x2="12" y2="13"></line>
              <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
            Conteúdo inapropriado detectado
          </small>
          @error('titulo')
            <p class="error-message" style="display:block">{{ $message }}</p>
          @enderror
        </div>

        {{-- Descrição --}}
        <div class="form-group">
          <label for="conteudo" class="form-label">Descrição *</label>
          <textarea 
            id="conteudo" 
            name="conteudo" 
            required
            maxlength="5000"
            placeholder="Descreva sua postagem..."
            class="form-textarea"
          >{{ old('conteudo') }}</textarea>
          <div class="char-counter">
            <span id="contador-chars">0</span>/5000 caracteres
          </div>
          <small id="conteudo-warning" class="error-message">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; margin-right:4px">
              <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
              <line x1="12" y1="9" x2="12" y2="13"></line>
              <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
            Conteúdo inapropriado detectado
          </small>
          @error('conteudo')
            <p class="error-message" style="display:block">{{ $message }}</p>
          @enderror
        </div>
      </div>

      {{-- ========== SEÇÃO 3: UPLOAD DE IMAGEM ========== --}}
      <div id="campos-imagem" class="form-section hidden">
        <div class="form-section-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <circle cx="8.5" cy="8.5" r="1.5"></circle>
            <polyline points="21 15 16 10 5 21"></polyline>
          </svg>
          Upload de Imagem
        </div>

        {{-- Container para alertas NSFW --}}
        <div id="nsfw-alert-container" style="display: none; margin: 16px 0;"></div>

        <div class="upload-zone" id="drop-zone-imagem">
          <input type="file" id="arquivo-imagem" name="arquivos[]" accept="image/*">
          <div class="upload-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
              <circle cx="12" cy="13" r="4"></circle>
            </svg>
          </div>
          <div class="upload-text">Clique ou arraste uma imagem aqui</div>
          <div class="upload-hint">Formatos: JPG, PNG, GIF, WEBP (Máx: 50MB)</div>
        </div>

        <div id="preview-imagem" class="preview-container"></div>
      </div>

      {{-- ========== SEÇÃO 4: UPLOAD DE VÍDEO ========== --}}
      <div id="campos-video" class="form-section hidden">
        <div class="form-section-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="23 7 16 12 23 17 23 7"></polygon>
            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
          </svg>
          Upload de Vídeo
        </div>

        <div class="upload-zone" id="drop-zone-video">
          <input type="file" id="arquivo-video" name="arquivos[]" accept="video/*">
          <div class="upload-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="23 7 16 12 23 17 23 7"></polygon>
              <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
            </svg>
          </div>
          <div class="upload-text">Clique ou arraste um vídeo aqui</div>
          <div class="upload-hint">Formatos: MP4, WebM, MOV (Máx: 50MB)</div>
        </div>

        <div id="preview-video" class="preview-container"></div>
      </div>

      {{-- ========== SEÇÃO 5: FICHA RPG ========== --}}
      <div id="campos-ficha" class="form-section hidden">
        <div class="form-section-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <path d="M15 3v18"></path>
            <path d="M3 9h18"></path>
            <path d="M3 15h18"></path>
          </svg>
          Dados do Personagem
        </div>

        <div class="ficha-container">
          {{-- Grid de Informações Básicas --}}
          <div class="ficha-grid">
            <div class="form-group">
              <label class="form-label">Nome do Personagem *</label>
              <input type="text" name="ficha_nome" id="ficha-nome" maxlength="100" 
                     placeholder="Ex: Krogar, o Bárbaro" class="form-input">
              <small id="ficha-nome-warning" class="error-message">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; margin-right:4px">
                  <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                  <line x1="12" y1="9" x2="12" y2="13"></line>
                  <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                Conteúdo inapropriado detectado
              </small>
            </div>

            <div class="form-group">
              <label class="form-label">Nível</label>
              <input type="number" name="ficha_nivel" id="ficha-nivel" min="1" max="100" value="1" 
                     placeholder="1" class="form-input">
            </div>

            <div class="form-group">
              <label class="form-label">Raça</label>
              <input type="text" name="ficha_raca" id="ficha-raca" maxlength="50" 
                     placeholder="Ex: Orc, Elfo, Humano" class="form-input">
              <small id="ficha-raca-warning" class="error-message">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; margin-right:4px">
                  <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                  <line x1="12" y1="9" x2="12" y2="13"></line>
                  <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                Conteúdo inapropriado detectado
              </small>
            </div>

            <div class="form-group">
              <label class="form-label">Classe</label>
              <input type="text" name="ficha_classe" id="ficha-classe" maxlength="50" 
                     placeholder="Ex: Guerreiro, Mago" class="form-input">
              <small id="ficha-classe-warning" class="error-message">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; margin-right:4px">
                  <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                  <line x1="12" y1="9" x2="12" y2="13"></line>
                  <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                Conteúdo inapropriado detectado
              </small>
            </div>
          </div>

          {{-- Foto do Personagem --}}
          <div class="form-group">
            <label class="form-label">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                <circle cx="12" cy="13" r="4"></circle>
              </svg>
              Foto do Personagem
            </label>
            <div id="nsfw-alert-ficha" style="display: none; margin: 8px 0;"></div>
            <div class="upload-zone">
              <input type="file" name="ficha_foto" id="ficha-foto" accept="image/*">
              <div class="upload-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </div>
              <div class="upload-text">Clique para adicionar foto</div>
              <div class="upload-hint">JPG, PNG, GIF (Máx: 5MB)</div>
            </div>
          </div>

          {{-- Atributos/Stats --}}
          <div>
            <label class="form-label">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px">
                <path d="M12 20v-6M6 20V10M18 20V4"></path>
              </svg>
              Atributos
            </label>
            <div class="ficha-stats">
              <div class="stat-box">
                <div class="stat-label">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                  </svg>
                  Força
                </div>
                <input type="number" name="ficha_forca" id="ficha-forca" 
                       min="1" max="20" value="10" class="stat-input">
              </div>

              <div class="stat-box">
                <div class="stat-label">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                  </svg>
                  Agilidade
                </div>
                <input type="number" name="ficha_agilidade" id="ficha-agilidade" 
                       min="1" max="20" value="10" class="stat-input">
              </div>

              <div class="stat-box">
                <div class="stat-label">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                  </svg>
                  Vigor
                </div>
                <input type="number" name="ficha_vigor" id="ficha-vigor" 
                       min="1" max="20" value="10" class="stat-input">
              </div>

              <div class="stat-box">
                <div class="stat-label">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                  </svg>
                  Inteligência
                </div>
                <input type="number" name="ficha_inteligencia" id="ficha-inteligencia" 
                       min="1" max="20" value="10" class="stat-input">
              </div>

              <div class="stat-box">
                <div class="stat-label">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 16v-4M12 8h.01"></path>
                  </svg>
                  Sabedoria
                </div>
                <input type="number" name="ficha_sabedoria" id="ficha-sabedoria" 
                       min="1" max="20" value="10" class="stat-input">
              </div>

              <div class="stat-box">
                <div class="stat-label">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                  </svg>
                  Carisma
                </div>
                <input type="number" name="ficha_carisma" id="ficha-carisma" 
                       min="1" max="20" value="10" class="stat-input">
              </div>
            </div>
          </div>

          {{-- Habilidades --}}
          <div class="form-group">
            <label class="form-label">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
              </svg>
              Habilidades
            </label>
            <textarea name="ficha_habilidades" id="ficha-habilidades" rows="3" maxlength="2000"
                      placeholder="Liste as habilidades do personagem..." 
                      class="form-textarea"></textarea>
            <small id="ficha-habilidades-warning" class="error-message">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; margin-right:4px">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
              </svg>
              Conteúdo inapropriado detectado
            </small>
          </div>

          {{-- Histórico --}}
          <div class="form-group">
            <label class="form-label">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
              </svg>
              Histórico/Lore
            </label>
            <textarea name="ficha_historico" id="ficha-historico" rows="4" maxlength="3000"
                      placeholder="Conte a história do personagem..." 
                      class="form-textarea"></textarea>
            <small id="ficha-historico-warning" class="error-message">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; margin-right:4px">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
              </svg>
              Conteúdo inapropriado detectado
            </small>
          </div>

          {{-- PREVIEW DA FICHA --}}
          <div class="ficha-preview">
            <div class="ficha-preview-title">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              Preview da Ficha
            </div>
            <div class="ficha-card-preview">
              <div class="ficha-header-preview">
                <img src="https://via.placeholder.com/100" alt="Avatar" class="ficha-avatar-preview" id="preview-avatar">
                <div class="ficha-info-preview">
                  <h3 id="preview-nome">Nome do Personagem</h3>
                  <p id="preview-info">Nível 1 • Raça • Classe</p>
                </div>
              </div>

              <div class="ficha-stats-preview">
                <div class="stat-preview-box">
                  <div class="stat-preview-label">FOR</div>
                  <div class="stat-preview-value" id="preview-forca">10</div>
                </div>
                <div class="stat-preview-box">
                  <div class="stat-preview-label">AGI</div>
                  <div class="stat-preview-value" id="preview-agilidade">10</div>
                </div>
                <div class="stat-preview-box">
                  <div class="stat-preview-label">VIG</div>
                  <div class="stat-preview-value" id="preview-vigor">10</div>
                </div>
                <div class="stat-preview-box">
                  <div class="stat-preview-label">INT</div>
                  <div class="stat-preview-value" id="preview-inteligencia">10</div>
                </div>
                <div class="stat-preview-box">
                  <div class="stat-preview-label">SAB</div>
                  <div class="stat-preview-value" id="preview-sabedoria">10</div>
                </div>
                <div class="stat-preview-box">
                  <div class="stat-preview-label">CAR</div>
                  <div class="stat-preview-value" id="preview-carisma">10</div>
                </div>
              </div>

              <div class="ficha-section-preview">
                <h4>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                  </svg>
                  Habilidades
                </h4>
                <p id="preview-habilidades" style="font-style:italic;color:var(--text-muted)">Nenhuma habilidade ainda...</p>
              </div>

              <div class="ficha-section-preview">
                <h4>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                  </svg>
                  Histórico
                </h4>
                <p id="preview-historico" style="font-style:italic;color:var(--text-muted)">Nenhum histórico ainda...</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ========== SEÇÃO 6: UPLOAD OUTROS ========== --}}
      <div id="campos-outros" class="form-section hidden">
        <div class="form-section-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 20v-6m4 6v-6m4 6v-6m-8-4v-4h8v4"></path>
            <rect x="3" y="4" width="18" height="16" rx="2"></rect>
          </svg>
          Arquivos (Opcional)
        </div>

        <div class="upload-zone" id="drop-zone-outros">
          <input type="file" id="arquivo-outros" name="arquivos[]" multiple accept="*/*">
          <div class="upload-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M10 20v-6m4 6v-6m4 6v-6m-8-4v-4h8v4"></path>
              <rect x="3" y="4" width="18" height="16" rx="2"></rect>
            </svg>
          </div>
          <div class="upload-text">Clique ou arraste arquivos aqui</div>
          <div class="upload-hint">Qualquer tipo de arquivo (Máx: 50MB por arquivo)</div>
        </div>

        <div id="preview-outros" class="preview-container"></div>
      </div>

      {{-- ========== FOOTER COM BOTÕES ========== --}}
      <div class="form-footer">
        <button type="submit" id="submit-btn" class="btn btn-primary">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
          Publicar Postagem
        </button>
        <a href="{{ route('comunidade.feed') }}" class="btn btn-secondary">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
          Cancelar
        </a>
      </div>
    </form>
  </div>
</div>

{{-- ========== SCRIPTS ========== --}}
<script src="{{ asset('js/moderation.js') }}" defer></script>
<script src="https://unpkg.com/nsfwjs@2.4.2/dist/nsfwjs.min.js"></script>


<script>
/* ============================================
   CONTROLE DE VISIBILIDADE DOS CAMPOS
   ============================================ */
function mostrarCamposTipo(tipo) {
  // Esconder todos os campos específicos
  document.getElementById('campos-comuns').classList.add('hidden');
  document.getElementById('campos-imagem').classList.add('hidden');
  document.getElementById('campos-video').classList.add('hidden');
  document.getElementById('campos-ficha').classList.add('hidden');
  document.getElementById('campos-outros').classList.add('hidden');
  
  // Mostrar campos comuns (título e descrição) para todos
  document.getElementById('campos-comuns').classList.remove('hidden');
  
  // Mostrar campos específicos baseado no tipo
  if (tipo === 'imagem') {
    document.getElementById('campos-imagem').classList.remove('hidden');
  } else if (tipo === 'video') {
    document.getElementById('campos-video').classList.remove('hidden');
  } else if (tipo === 'ficha_rpg') {
    document.getElementById('campos-ficha').classList.remove('hidden');
  } else if (tipo === 'outros') {
    document.getElementById('campos-outros').classList.remove('hidden');
  }
  // tipo 'texto' só precisa dos campos comuns
}

/* ============================================
   CONTADOR DE CARACTERES
   ============================================ */
const conteudoTextarea = document.getElementById('conteudo');
const contadorChars = document.getElementById('contador-chars');

if (conteudoTextarea) {
  conteudoTextarea.addEventListener('input', function() {
    contadorChars.textContent = this.value.length;
    
    if (this.value.length > 4500) {
      contadorChars.style.color = '#ef4444';
      contadorChars.style.fontWeight = '700';
    } else {
      contadorChars.style.color = 'var(--text-muted)';
      contadorChars.style.fontWeight = '400';
    }
  });
}

/* ============================================
   SISTEMA DE DETECÇÃO NSFW (INTEGRADO DIRETO)
   ============================================ */
const NSFWDetector = (function() {
    const CONFIG = {
        modelPath: '/models/nsfwjs-master/models/mobilenet_v2/',
        thresholds: {
            porn: 0.60,
            hentai: 0.60,
            sexy: 0.80
        },
        maxFileSize: 50 * 1024 * 1024,
        allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
    };

    let nsfwModel = null;
    let isModelLoaded = false;
    let modelLoadPromise = null;

    async function loadModel() {
        if (isModelLoaded && nsfwModel) return nsfwModel;
        if (modelLoadPromise) return modelLoadPromise;

        modelLoadPromise = (async () => {
            try {
                console.log('Carregando modelo NSFW...');
                
                if (typeof nsfwjs === 'undefined') {
                    throw new Error('NSFW.js não encontrado');
                }

                nsfwModel = await nsfwjs.load(CONFIG.modelPath);
                isModelLoaded = true;
                console.log('Modelo NSFW carregado');
                return nsfwModel;
            } catch (error) {
                console.error('Erro ao carregar modelo NSFW:', error);
                modelLoadPromise = null;
                throw error;
            }
        })();

        return modelLoadPromise;
    }

    function validateFile(file) {
        if (!file) return { valid: false, error: 'Nenhum arquivo' };
        if (!CONFIG.allowedTypes.includes(file.type)) {
            return { valid: false, error: 'Tipo inválido. Use: JPG, PNG, GIF, WEBP' };
        }
        if (file.size > CONFIG.maxFileSize) {
            return { valid: false, error: `Arquivo muito grande. Máx: ${(CONFIG.maxFileSize / 1024 / 1024)}MB` };
        }
        return { valid: true };
    }

    function createImageElement(file) {
        return new Promise((resolve, reject) => {
            const img = document.createElement('img');
            const url = URL.createObjectURL(file);
            
            img.onload = () => {
                URL.revokeObjectURL(url);
                resolve(img);
            };
            
            img.onerror = () => {
                URL.revokeObjectURL(url);
                reject(new Error('Erro ao carregar imagem'));
            };
            
            img.src = url;
        });
    }

    function processResults(predictions) {
        const scores = {};
        predictions.forEach(pred => {
            scores[pred.className] = pred.probability;
        });

        const reasons = [];
        let isBlocked = false;
        let riskLevel = 'safe';

        if (scores.Porn && scores.Porn >= CONFIG.thresholds.porn) {
            isBlocked = true;
            riskLevel = 'unsafe';
            reasons.push(`Conteúdo pornográfico (${(scores.Porn * 100).toFixed(1)}%)`);
        }

        if (scores.Hentai && scores.Hentai >= CONFIG.thresholds.hentai) {
            isBlocked = true;
            riskLevel = 'unsafe';
            reasons.push(`Conteúdo hentai (${(scores.Hentai * 100).toFixed(1)}%)`);
        }

        if (scores.Sexy && scores.Sexy >= CONFIG.thresholds.sexy) {
            if (!isBlocked) riskLevel = 'warning';
            reasons.push(`Conteúdo sensual (${(scores.Sexy * 100).toFixed(1)}%)`);
        }

        return { isBlocked, riskLevel, reasons, scores, predictions };
    }

    async function analyze(file) {
        const validation = validateFile(file);
        if (!validation.valid) throw new Error(validation.error);

        try {
            const model = await loadModel();
            const img = await createImageElement(file);
            const startTime = Date.now();
            const predictions = await model.classify(img);
            const analysisTime = Date.now() - startTime;

            const result = processResults(predictions);
            result.analysisTime = analysisTime;
            
            console.log('Análise NSFW:', result);
            return result;
        } catch (error) {
            console.error('Erro na análise NSFW:', error);
            throw error;
        }
    }

    function isLoaded() { return isModelLoaded; }
    function getConfig() { return { ...CONFIG }; }

    return { analyze, isLoaded, getConfig, loadModel };
})();

/* ============================================
   ALERTA NSFW VISUAL
   ============================================ */
const NSFWAlert = (function() {
    function showAlert(containerId, result) {
        const container = document.getElementById(containerId);
        if (!container) return;

        let icon, title, color, message;
        
        if (result.riskLevel === 'safe') {
            icon = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
            title = 'Imagem Aprovada';
            color = '#22c55e';
            message = 'Nenhum conteúdo inapropriado detectado.';
        } else if (result.riskLevel === 'warning') {
            icon = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>`;
            title = 'Conteúdo Sugestivo';
            color = '#f59e0b';
            message = result.reasons.join(', ');
        } else {
            icon = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`;
            title = 'Imagem Bloqueada';
            color = '#ef4444';
            message = result.reasons.join(', ');
        }

        container.innerHTML = `
            <div style="
                background: rgba(26, 35, 50, 0.95);
                border: 2px solid ${color};
                border-radius: 10px;
                padding: 16px;
                margin: 16px 0;
                color: white;
                animation: fadeIn 0.3s ease;
            ">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <span style="font-size: 24px;">${icon}</span>
                    <strong style="font-size: 16px;">${title}</strong>
                </div>
                <div style="font-size: 14px; color: #e6eef6;">${message}</div>
                <div style="font-size: 12px; color: #8b9ba8; margin-top: 8px;">
                    Análise: ${result.analysisTime}ms
                </div>
            </div>
        `;
        container.style.display = 'block';
    }

    function showLoading(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        container.innerHTML = `
            <div style="
                background: rgba(26, 35, 50, 0.95);
                border: 2px solid #22c55e;
                border-radius: 10px;
                padding: 16px;
                margin: 16px 0;
                color: white;
                text-align: center;
            ">
                <div style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                    <div class="spinner" style="
                        width: 20px;
                        height: 20px;
                        border: 3px solid rgba(34, 197, 94, 0.3);
                        border-radius: 50%;
                        border-top-color: #22c55e;
                        animation: spin 1s linear infinite;
                    "></div>
                    <span>Analisando imagem...</span>
                </div>
            </div>
        `;
        container.style.display = 'block';
    }

    function clearAlert(containerId) {
        const container = document.getElementById(containerId);
        if (container) {
            container.innerHTML = '';
            container.style.display = 'none';
        }
    }

    return { showAlert, showLoading, clearAlert };
})();

/* ============================================
   PREVIEW DE IMAGEM COM DETECÇÃO NSFW
   ============================================ */
const arquivoImagem = document.getElementById('arquivo-imagem');
const previewImagem = document.getElementById('preview-imagem');
let currentNSFWResult = null;

if (arquivoImagem) {
    arquivoImagem.addEventListener('change', async function(e) {
        previewImagem.innerHTML = '';
        NSFWAlert.clearAlert('nsfw-alert-container');
        currentNSFWResult = null;
        
        const file = e.target.files[0];
        
        if (file && file.type.startsWith('image/')) {
            // Mostrar loading
            NSFWAlert.showLoading('nsfw-alert-container');
            
            try {
                // Analisar imagem com NSFW
                const result = await NSFWDetector.analyze(file);
                currentNSFWResult = result;
                
                // Mostrar resultado
                NSFWAlert.showAlert('nsfw-alert-container', result);
                
                // Se for bloqueada, não mostrar preview
                if (result.isBlocked) {
                    arquivoImagem.value = '';
                    return;
                }
                
                // Mostrar preview normal
                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${event.target.result}" alt="Preview">
                        <button type="button" class="preview-remove" onclick="limparPreview('imagem')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    `;
                    previewImagem.appendChild(div);
                };
                reader.readAsDataURL(file);
                
            } catch (error) {
                console.error('Erro na análise:', error);
                NSFWAlert.clearAlert('nsfw-alert-container');
                
                // Em caso de erro, permitir upload mas mostrar aviso
                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${event.target.result}" alt="Preview">
                        <button type="button" class="preview-remove" onclick="limparPreview('imagem')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    `;
                    previewImagem.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        }
    });
}

/* ============================================
   PREVIEW DE VÍDEO
   ============================================ */
const arquivoVideo = document.getElementById('arquivo-video');
const previewVideo = document.getElementById('preview-video');

if (arquivoVideo) {
    arquivoVideo.addEventListener('change', function(e) {
        previewVideo.innerHTML = '';
        const file = e.target.files[0];
        
        if (file && file.type.startsWith('video/')) {
            const url = URL.createObjectURL(file);
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.innerHTML = `
                <video controls>
                    <source src="${url}" type="${file.type}">
                </video>
                <button type="button" class="preview-remove" onclick="limparPreview('video')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            `;
            previewVideo.appendChild(div);
        }
    });
}

/* ============================================
   PREVIEW OUTROS ARQUIVOS
   ============================================ */
const arquivoOutros = document.getElementById('arquivo-outros');
const previewOutros = document.getElementById('preview-outros');

if (arquivoOutros) {
    arquivoOutros.addEventListener('change', function(e) {
        previewOutros.innerHTML = '';
        
        Array.from(e.target.files).forEach((file, index) => {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.style.padding = '16px';
            div.style.display = 'flex';
            div.style.alignItems = 'center';
            div.style.justifyContent = 'space-between';
            
            div.innerHTML = `
                <div style="flex:1;min-width:0">
                    <div style="color:#fff;font-weight:700;font-size:14px;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${file.name}</div>
                    <div style="color:var(--text-muted);font-size:12px">${formatFileSize(file.size)}</div>
                </div>
                <button type="button" class="preview-remove" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            `;
            previewOutros.appendChild(div);
        });
    });
}

/* ============================================
   PREVIEW AO VIVO DA FICHA RPG
   ============================================ */
// Foto do personagem
const fichaFoto = document.getElementById('ficha-foto');
const previewAvatar = document.getElementById('preview-avatar');

if (fichaFoto) {
    fichaFoto.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            // Analisar foto do personagem também
            try {
                NSFWAlert.showLoading('nsfw-alert-ficha');
                const result = await NSFWDetector.analyze(file);
                
                if (result.isBlocked) {
                    NSFWAlert.showAlert('nsfw-alert-ficha', result);
                    fichaFoto.value = '';
                    return;
                }
                
                NSFWAlert.clearAlert('nsfw-alert-ficha');
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewAvatar.src = event.target.result;
                };
                reader.readAsDataURL(file);
            } catch (error) {
                console.error('Erro na análise da foto:', error);
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewAvatar.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    });
}

// Atualizar preview em tempo real
const camposFicha = {
    'ficha-nome': 'preview-nome',
    'ficha-nivel': 'preview-info',
    'ficha-raca': 'preview-info',
    'ficha-classe': 'preview-info',
    'ficha-forca': 'preview-forca',
    'ficha-agilidade': 'preview-agilidade',
    'ficha-vigor': 'preview-vigor',
    'ficha-inteligencia': 'preview-inteligencia',
    'ficha-sabedoria': 'preview-sabedoria',
    'ficha-carisma': 'preview-carisma',
    'ficha-habilidades': 'preview-habilidades',
    'ficha-historico': 'preview-historico'
};

Object.keys(camposFicha).forEach(campoId => {
    const campo = document.getElementById(campoId);
    if (campo) {
        campo.addEventListener('input', atualizarPreviewFicha);
    }
});

function atualizarPreviewFicha() {
    const nome = document.getElementById('ficha-nome')?.value || 'Nome do Personagem';
    const nivel = document.getElementById('ficha-nivel')?.value || '1';
    const raca = document.getElementById('ficha-raca')?.value || 'Raça';
    const classe = document.getElementById('ficha-classe')?.value || 'Classe';
    
    document.getElementById('preview-nome').textContent = nome;
    document.getElementById('preview-info').textContent = `Nível ${nivel} • ${raca} • ${classe}`;
    
    ['forca', 'agilidade', 'vigor', 'inteligencia', 'sabedoria', 'carisma'].forEach(stat => {
        const valor = document.getElementById(`ficha-${stat}`)?.value || '10';
        document.getElementById(`preview-${stat}`).textContent = valor;
    });
    
    const habilidades = document.getElementById('ficha-habilidades')?.value;
    document.getElementById('preview-habilidades').innerHTML = habilidades || 
        '<span style="font-style:italic;color:var(--text-muted)">Nenhuma habilidade ainda...</span>';
    
    const historico = document.getElementById('ficha-historico')?.value;
    document.getElementById('preview-historico').innerHTML = historico || 
        '<span style="font-style:italic;color:var(--text-muted)">Nenhum histórico ainda...</span>';
}

/* ============================================
   FUNÇÕES AUXILIARES
   ============================================ */
function limparPreview(tipo) {
    if (tipo === 'imagem') {
        document.getElementById('arquivo-imagem').value = '';
        document.getElementById('preview-imagem').innerHTML = '';
        NSFWAlert.clearAlert('nsfw-alert-container');
        currentNSFWResult = null;
    } else if (tipo === 'video') {
        document.getElementById('arquivo-video').value = '';
        document.getElementById('preview-video').innerHTML = '';
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

/* ============================================
   VALIDAÇÃO DO FORMULÁRIO COM NSFW E MODERAÇÃO DE TEXTO
   ============================================ */
const form = document.getElementById('form-criar-post');
if (form) {
    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        
        // Verificar todos os campos com classe 'error'
        const camposComErro = document.querySelectorAll('.form-input.error, .form-textarea.error');
        
        // Verificar se há imagem NSFW bloqueada
        if (currentNSFWResult && currentNSFWResult.isBlocked) {
            hasErrors = true;
            alert('Imagem bloqueada por conter conteúdo inapropriado. Por favor, remova a imagem ou escolha outra.');
        }
        
        // Verificar campos de texto inapropriados
        if (camposComErro.length > 0) {
            hasErrors = true;
            const campos = Array.from(camposComErro).map(campo => {
                const label = campo.previousElementSibling?.textContent || campo.id;
                return `• ${label}`;
            }).join('\n');
            
            alert(`Conteúdo inapropriado detectado nos seguintes campos:\n\n${campos}\n\nCorrija antes de continuar.`);
        }
        
        // Verificar se o tipo é imagem mas não há arquivo selecionado
        const tipoConteudo = document.querySelector('input[name="tipo_conteudo"]:checked');
        if (tipoConteudo && tipoConteudo.value === 'imagem') {
            const arquivo = document.getElementById('arquivo-imagem').files[0];
            if (!arquivo) {
                hasErrors = true;
                alert('Para postagens do tipo "Imagem", é necessário selecionar uma imagem.');
            }
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

/* ============================================
   MODERAÇÃO LOCAL (Sistema existente) - ATUALIZADO
   ============================================ */
window.addEventListener('DOMContentLoaded', async () => {
    // Carregar modelo NSFW em background
    try {
        await NSFWDetector.loadModel();
        console.log('Modelo NSFW carregado com sucesso');
    } catch (error) {
        console.warn('Não foi possível carregar o modelo NSFW:', error);
    }
    
    // Inicializar sistema de moderação de texto
    const state = await window.Moderation.init({
        csrfToken: '{{ csrf_token() }}',
        endpoint: '/moderate',
        debounceMs: 120,
    });

    // Função para aplicar warning em qualquer campo
    function applyWarning(seletor, res) {
        const el = document.querySelector(seletor);
        const warn = document.querySelector(seletor + '-warning');
        if (!el) return;
        
        if (res && res.inappropriate) {
            el.classList.add('error');
            if (warn) warn.style.display = 'block';
        } else {
            el.classList.remove('error');
            if (warn) warn.style.display = 'none';
        }
    }

    // Lista de todos os campos que precisam de moderação
    const camposParaModerar = [
        { id: '#titulo', name: 'titulo' },
        { id: '#conteudo', name: 'conteudo' },
        { id: '#ficha-nome', name: 'ficha_nome' },
        { id: '#ficha-raca', name: 'ficha_raca' },
        { id: '#ficha-classe', name: 'ficha_classe' },
        { id: '#ficha-habilidades', name: 'ficha_habilidades' },
        { id: '#ficha-historico', name: 'ficha_historico' }
    ];

    // Aplicar moderação a todos os campos
    camposParaModerar.forEach(campo => {
        window.Moderation.attachInput(campo.id, campo.name, {
            onLocal: (res) => applyWarning(campo.id, res),
            onServer: (srv) => {
                if (srv && srv.data && srv.data.inappropriate) {
                    applyWarning(campo.id, { inappropriate: true });
                }
            }
        });
    });
});

/* ============================================
   ESTILOS PARA ANIMAÇÕES
   ============================================ */
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>

{{-- Adicione estes containers para alertas NSFW --}}
<div id="nsfw-alert-container" style="display: none; margin: 16px 0;"></div>
<div id="nsfw-alert-ficha" style="display: none; margin: 16px 0;"></div>
@endsection