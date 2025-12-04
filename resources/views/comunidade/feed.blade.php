
{{-- resources/views/comunidade/feed.blade.php - FEED REFORMULADO E FUNCIONAL --}}
@extends('layout.app')

@section('title', 'Comunidade - Ambience RPG')

@section('content')
<style>
/* ============================================
   VARIÁVEIS - Cores do Ambience com fundo mais claro
   ============================================ */
:root {
  --bg-dark: #0a0f14;
  --bg-secondary: #151b23;
  --card-bg: rgba(26, 35, 50, 0.85); /* Mais transparente */
  --card-hover: rgba(31, 42, 61, 0.9);
  --border-color: rgba(34,197,94,0.2);
  --accent: #22c55e;
  --accent-light: #16a34a;
  --accent-dark: #15803d;
  --text-primary: #e6eef6;
  --text-secondary: #8b9ba8;
  --text-muted: #64748b;
}

/* ============================================
   BACKGROUND - Mais visível e claro
   ============================================ */
.feed-container {
  position: relative;
  min-height: 100vh;
}

.feed-container::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url('{{ asset("images/rpg-background.gif") }}') center/cover;
  /* Menos blur e mais brilho para ser mais visível */
  filter: blur(4px) brightness(0.5);
  z-index: -1;
}

/* Overlay mais suave para melhor visibilidade */
.feed-container::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(10, 15, 20, 0.7); /* Menos escuro */
  z-index: -1;
}

/* ============================================
   MAIN LAYOUT - Grid responsivo
   ============================================ */
.main-container {
  max-width: 1440px;
  margin: 24px auto;
  padding: 0 24px;
  display: grid;
  grid-template-columns: 1fr 312px;
  gap: 24px;
  align-items: start;
  position: relative;
  z-index: 1;
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
   FEED COLUMN - Posts
   ============================================ */
.feed-column {
  min-width: 0;
}

/* Header do Feed */
.feed-header {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px 24px;
  margin-bottom: 16px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.feed-header h1 {
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 900;
  color: #fff;
  margin-bottom: 6px;
}

.feed-header p {
  color: var(--text-secondary);
  font-size: 14px;
}

/* Search Bar */
.search-bar {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 16px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.search-form {
  display: flex;
  gap: 12px;
}

.search-input {
  flex: 1;
  padding: 12px 16px;
  background: rgba(10, 15, 20, 0.6);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-primary);
  font-size: 14px;
  transition: all 0.2s;
}

.search-input:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.search-input::placeholder {
  color: var(--text-muted);
}

/* ============================================
   POST CARD - Design limpo e funcional
   ============================================ */
.posts-grid {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.post-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.2s;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.post-card:hover {
  border-color: var(--accent);
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
  transform: translateY(-2px);
}

/* Header do Post */
.post-header {
  padding: 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid var(--border-color);
}

.post-author {
  display: flex;
  align-items: center;
  gap: 12px;
  flex: 1;
}

.author-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--border-color);
}

.author-info h4 {
  font-size: 14px;
  font-weight: 700;
  color: #fff;
}

.author-info p {
  font-size: 12px;
  color: var(--text-muted);
}

.post-badge {
  padding: 4px 10px;
  background: rgba(34, 197, 94, 0.15);
  color: var(--accent);
  border-radius: 12px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Body do Post */
.post-body {
  padding: 16px;
}

.post-title {
  font-size: 18px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 8px;
  line-height: 1.4;
  cursor: pointer;
  text-decoration: none;
  display: block;
}

.post-title:hover {
  color: var(--accent);
}

.post-text {
  color: var(--text-secondary);
  font-size: 14px;
  line-height: 1.6;
  margin-bottom: 12px;
}

/* Mídia do Post */
.post-media {
  margin-top: 12px;
  border-radius: 8px;
  overflow: hidden;
  max-height: 400px;
}

.post-media img,
.post-media video {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.post-media-placeholder {
  width: 100%;
  height: 200px;
  background: linear-gradient(135deg, #064e3b, #052e16);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 48px;
}

/* Footer do Post - Ações */
.post-footer {
  padding: 12px 16px;
  display: flex;
  align-items: center;
  gap: 8px;
  border-top: 1px solid var(--border-color);
}

.post-action-btn {
  background: transparent;
  border: none;
  color: var(--text-muted);
  font-size: 13px;
  font-weight: 600;
  padding: 8px 12px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 6px;
}

.post-action-btn:hover {
  background: rgba(34, 197, 94, 0.1);
  color: var(--accent);
}

.post-action-btn.active {
  color: var(--accent);
  background: rgba(34, 197, 94, 0.15);
}

/* ============================================
   SIDEBAR - Informações
   ============================================ */
.sidebar {
  position: sticky;
  top: 88px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.sidebar-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.sidebar-card h3 {
  font-size: 14px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.sidebar-card p {
  color: var(--text-secondary);
  font-size: 13px;
  line-height: 1.6;
}

.create-post-btn {
  width: 100%;
  padding: 19px;
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
  color: #052e16;
  border: none;
  border-radius: 8px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.create-post-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
}

/* ============================================
   EMPTY STATE
   ============================================ */
.empty-state {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 60px 40px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.empty-state-icon {
  font-size: 64px;
  margin-bottom: 16px;
  opacity: 0.5;
}

.empty-state h3 {
  font-size: 20px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 8px;
}

.empty-state p {
  color: var(--text-secondary);
  margin-bottom: 24px;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 1200px) {
  .main-container {
    grid-template-columns: 1fr;
  }
  
  .sidebar {
    display: none;
  }
}

@media (max-width: 768px) {
  .feed-header h1 {
    font-size: 20px;
  }
  
  .search-form {
    flex-direction: column;
  }
}

/* ============================================
   ANIMAÇÕES
   ============================================ */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.post-card {
  animation: fadeIn 0.3s ease;
}
</style>

<div class="feed-container">
  <!-- ============================================
       MAIN CONTENT - Feed e Sidebar
       ============================================ -->
  <div class="main-container">
    <!-- FEED COLUMN -->
    <div class="feed-column">
      <!-- Header do Feed -->
      <div class="feed-header">
        <h1>
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
          </svg> Comunidade Ambience
        </h1>
        <p>Compartilhe aventuras e criações com milhares de jogadores</p>
      </div>

      <!-- Barra de Busca -->
      <div class="search-bar">
        <form action="{{ route('comunidade.buscar') }}" method="GET" class="search-form">
          <input 
            type="text" 
            name="q" 
            placeholder="Buscar postagens..."
            class="search-input"
            style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: 16px center; padding-left: 44px;"
          >
          <button type="submit" class="btn primary" style="padding: 10px 20px; border-radius: 8px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%); color: #052e16; border: none; font-weight: 700; cursor: pointer;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            Buscar
          </button>
        </form>
      </div>

      <!-- Grid de Posts -->
      @if($posts->count() > 0)
        <div class="posts-grid">
          @foreach($posts as $post)
            <article class="post-card">
              
              <!-- Header do Post -->
              <div class="post-header">
                <div class="post-author">
                  <a href="{{ route('perfil.show', $post->autor->username) }}">
                    @php
                      // CONEXÃO DIRETA COM O BANCO - SEM MODEL
                      try {
                          $pdo = new PDO('mysql:host=127.0.0.1;dbname=ambience_rpg', 'root', '');
                          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                          
                          $stmt = $pdo->prepare('SELECT genero, classe_personagem FROM usuarios WHERE id = ?');
                          $stmt->execute([$post->autor->id]);
                          $dados = $stmt->fetch(PDO::FETCH_ASSOC);
                          
                          $genero = $dados['genero'] ?? 'masculino';
                          $classe = $dados['classe_personagem'] ?? 'ladino';
                          
                      } catch (Exception $e) {
                          $genero = 'masculino';
                          $classe = 'ladino';
                      }
                      
                      $avatarPath = "images/avatars/default/{$genero}/{$classe}.png";
                      
                      if (!file_exists(public_path($avatarPath))) {
                          $avatarPath = 'images/default-avatar.png';
                      }
                    @endphp
                    
                    <img src="{{ asset($avatarPath) }}" 
                         alt="{{ $post->autor->username }}" 
                         class="author-avatar">
                  </a>
                  <div class="author-info">
                    <h4>{{ $post->autor->username }}</h4>
                    <p>{{ $post->created_at->diffForHumans() }}</p>
                  </div>
                </div>
                <span class="post-badge">{{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}</span>
              </div>

              <!-- Body do Post -->
              <div class="post-body">
                <a href="{{ route('comunidade.post.show', $post->slug) }}" class="post-title">
                  {{ $post->titulo }}
                </a>
                <p class="post-text">{{ Str::limit($post->conteudo, 200) }}</p>
                
                <!-- Mídia (se houver) -->
                @if($post->arquivos->count() > 0)
                  <div class="post-media">
                    @php $arquivo = $post->arquivos->first(); @endphp
                    @if($arquivo->tipo === 'imagem')
                      <img src="{{ $arquivo->url }}" alt="{{ $post->titulo }}">
                    @elseif($arquivo->tipo === 'video')
                      <video controls>
                        <source src="{{ $arquivo->url }}" type="{{ $arquivo->tipo_mime }}">
                      </video>
                    @else
                      <div class="post-media-placeholder">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                          <polyline points="14 2 14 8 20 8"></polyline>
                          <line x1="16" y1="13" x2="8" y2="13"></line>
                          <line x1="16" y1="17" x2="8" y2="17"></line>
                          <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                      </div>
                    @endif
                  </div>
                @endif
              </div>

              <!-- Footer - Ações -->
              <div class="post-footer">
                @auth
                  @php
                    $curtido = $post->curtidas()->where('usuario_id', auth()->id())->exists();
                  @endphp
                  
                  <!-- Botão Curtir (Agora com AJAX) -->
                  <button 
                      class="post-action-btn {{ $curtido ? 'active' : '' }} like-btn"
                      data-post-id="{{ $post->id }}"
                      data-curtido="{{ $curtido ? 'true' : 'false' }}"
                      data-likes-count="{{ $post->curtidas()->count() }}"
                  >
                    @if($curtido)
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                      </svg>
                      <span class="likes-count">{{ $post->curtidas()->count() }}</span>
                    @else
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                      </svg>
                      <span class="likes-count">{{ $post->curtidas()->count() }}</span>
                    @endif
                  </button>
                @else
                  <button class="post-action-btn" onclick="window.location.href='{{ route('usuarios.login') }}'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    {{ $post->curtidas()->count() }}
                  </button>
                @endauth

                <!-- Comentários -->
                <a href="{{ route('comunidade.post.show', $post->slug) }}" class="post-action-btn" style="text-decoration:none">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                  </svg>
                  {{ $post->comentarios()->count() }}
                </a>
              </div>
            </article>
          @endforeach
        </div>

        <!-- Paginação -->
        <div style="margin-top:24px">
          {{ $posts->links() }}
        </div>
      @else
        <div class="empty-state">
          <div class="empty-state-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <path d="M12 8v4"></path>
              <path d="M12 16h.01"></path>
            </svg>
          </div>
          <h3>Nenhuma postagem ainda</h3>
          <p>Seja o primeiro a compartilhar algo incrível!</p>
          @auth
            <button class="btn primary" onclick="window.location.href='{{ route('comunidade.create') }}'" style="padding: 12px 24px; border-radius: 8px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%); color: #052e16; border: none; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              Criar Primeira Postagem
            </button>
          @endauth
        </div>
      @endif
    </div>

    <!-- SIDEBAR -->
    <aside class="sidebar">
      @auth
        <div class="sidebar-card">
          <button class="create-post-btn" onclick="window.location.href='{{ route('comunidade.create') }}'">
            <svg width="18"  height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Criar Postagem
          </button>
        </div>
      @endauth

      <div class="sidebar-card">
        <h3>Sobre a Comunidade</h3>
        <p>A maior comunidade de RPG do Brasil. Compartilhe fichas, aventuras, experiências e muito mais!</p>
      </div>

      <div class="sidebar-card">
        <h3>Regras</h3>
        <p>1. Seja respeitoso<br>2. Sem spam<br>3. Só conteúdo apropriado<br>4. Não é permitido qualquer tipo de NSFW</p>
      </div>
    </aside>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sistema de curtidas com AJAX
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const postId = this.dataset.postId;
            const isLiked = this.dataset.curtido === 'true';
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Mostrar loading
            const originalHtml = this.innerHTML;
            this.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>';
            this.disabled = true;
            
            try {
                if (isLiked) {
                    // Remover curtida
                    const response = await fetch(`/comunidade/descurtir/${postId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Atualizar botão
                        this.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg> <span class="likes-count">${data.total_curtidas}</span>`;
                        this.classList.remove('active');
                        this.dataset.curtido = 'false';
                        this.dataset.likesCount = data.total_curtidas;
                    }
                } else {
                    // Adicionar curtida
                    const response = await fetch('/comunidade/curtir', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ post_id: postId })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Atualizar botão
                        this.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg> <span class="likes-count">${data.total_curtidas}</span>`;
                        this.classList.add('active');
                        this.dataset.curtido = 'true';
                        this.dataset.likesCount = data.total_curtidas;
                    }
                }
            } catch (error) {
                console.error('Erro ao curtir:', error);
                this.innerHTML = originalHtml;
                alert('Erro ao processar curtida');
            }
            
            this.disabled = false;
        });
    });
});
</script>
@endsection