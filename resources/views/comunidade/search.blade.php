{{-- resources/views/comunidade/search.blade.php --}}
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Buscar - Ambience RPG</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
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
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background: #0a0f14;
  color: var(--text-primary);
  -webkit-font-smoothing: antialiased;
  line-height: 1.5;
  min-height: 100vh;
  position: relative;
}

/* Background animado igual ao feed */
body::before {
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

body::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(10, 15, 20, 0.7);
  z-index: -1;
}

/* Header estilo feed */
.header {
  position: sticky;
  top: 0;
  z-index: 100;
  background: rgba(10, 15, 20, 0.75);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(34, 197, 94, 0.12);
}

.container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 32px;
}

.nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 0;
  height: 70px;
}

.logo {
  display: flex;
  align-items: center;
  gap: 12px;
  font-weight: 800;
  font-size: 19px;
  color: #fff;
  text-decoration: none;
}

.logo-img {
  height: 50px;
  width: auto;
}

.nav-links {
  display: flex;
  gap: 32px;
  align-items: center;
}

.nav-links a {
  color: rgba(255, 255, 255, 0.9);
  text-decoration: none;
  font-weight: 500;
  font-size: 15px;
  transition: color 0.2s;
}

.nav-links a:hover {
  color: var(--accent);
}

.btn {
  padding: 11px 22px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 15px;
  border: none;
  cursor: pointer;
  transition: all 0.25s;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
}

.btn.primary {
  background: linear-gradient(to right, var(--accent), var(--accent-light));
  color: #052e16;
  box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
}

.btn.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
}

/* Container principal estilo feed */
.search-container {
  max-width: 1280px;
  margin: 40px auto;
  padding: 0 32px;
  position: relative;
  z-index: 1;
}

/* Header da busca */
.search-header {
  text-align: center;
  margin-bottom: 40px;
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 40px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.search-header h1 {
  font-family: 'Montserrat', sans-serif;
  font-size: 42px;
  font-weight: 900;
  color: #fff;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 16px;
}

.search-header p {
  color: var(--text-secondary);
  font-size: 17px;
}

/* Caixa de busca estilo feed */
.search-box {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 32px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.search-form {
  display: grid;
  grid-template-columns: 2fr 1fr auto;
  gap: 16px;
}

.search-input,
.search-select {
  padding: 16px 20px;
  background: rgba(10, 15, 20, 0.6);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  color: var(--text-primary);
  font-size: 15px;
  transition: all 0.3s;
  font-family: 'Inter', sans-serif;
}

.search-input:focus,
.search-select:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.search-input::placeholder {
  color: var(--text-muted);
}

.search-select {
  cursor: pointer;
}

.search-select option {
  background: var(--bg-dark);
  color: var(--text-primary);
  padding: 12px;
}

/* Info de resultados */
.results-info {
  background: rgba(34, 197, 94, 0.08);
  border-left: 4px solid var(--accent);
  padding: 20px 24px;
  border-radius: 12px;
  margin-bottom: 32px;
  border: 1px solid var(--border-color);
}

.results-info p {
  color: #fff;
  font-size: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

/* Grid de posts estilo feed */
.posts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
  gap: 24px;
}

.post-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.post-card:hover {
  transform: translateY(-4px);
  border-color: var(--accent);
  box-shadow: 0 12px 32px rgba(34, 197, 94, 0.15);
}

/* Mídia do post */
.post-media {
  height: 200px;
  background: #000;
  position: relative;
  overflow: hidden;
}

.post-media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.post-card:hover .post-media img {
  transform: scale(1.05);
}

.post-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  padding: 6px 12px;
  background: rgba(34, 197, 94, 0.9);
  color: #052e16;
  border-radius: 8px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
}

/* Conteúdo do post */
.post-content {
  padding: 20px;
}

.post-title {
  font-size: 18px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 12px;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.post-text {
  color: var(--text-secondary);
  font-size: 14px;
  line-height: 1.6;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  margin-bottom: 16px;
}

/* Footer do post */
.post-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 20px 20px;
  border-top: 1px solid var(--border-color);
}

.post-stats {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 13px;
}

.post-stats span {
  display: flex;
  align-items: center;
  gap: 6px;
}

.btn-view {
  padding: 10px 20px;
  background: rgba(34, 197, 94, 0.1);
  border: 1px solid rgba(34, 197, 94, 0.2);
  color: var(--accent);
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.25s;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-view:hover {
  background: rgba(34, 197, 94, 0.2);
  transform: translateY(-2px);
  border-color: var(--accent);
}

/* Estado vazio */
.empty-state {
  text-align: center;
  padding: 80px 32px;
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.empty-state-icon {
  font-size: 64px;
  margin-bottom: 24px;
}

.empty-state h3 {
  font-size: 28px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 12px;
}

.empty-state p {
  color: var(--text-secondary);
  margin-bottom: 32px;
  font-size: 16px;
}

/* Paginação */
.pagination-container {
  margin-top: 48px;
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

/* Footer */
.footer {
  border-top: 1px solid rgba(34, 197, 94, 0.1);
  padding: 48px 0 24px;
  background: #0d1419;
  margin-top: 80px;
  position: relative;
  z-index: 1;
}

.footer-bottom {
  text-align: center;
  color: var(--text-secondary);
  font-size: 13px;
  max-width: 1200px;
  margin: 0 auto;
  padding: 24px 32px;
}

/* Responsividade */
@media (max-width: 1024px) {
  .search-form {
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  
  .posts-grid {
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  }
}

@media (max-width: 768px) {
  .container,
  .search-container {
    padding: 0 20px;
  }
  
  .nav-links {
    display: none;
  }
  
  .search-header h1 {
    font-size: 32px;
    flex-direction: column;
    gap: 12px;
  }
  
  .search-form {
    grid-template-columns: 1fr;
  }
  
  .posts-grid {
    grid-template-columns: 1fr;
  }
  
  .search-header {
    padding: 32px 24px;
  }
  
  .search-box {
    padding: 24px;
  }
}

@media (max-width: 480px) {
  .post-footer {
    flex-direction: column;
    gap: 16px;
    align-items: flex-start;
  }
  
  .btn-view {
    align-self: stretch;
    justify-content: center;
  }
}

/* Animações */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.post-card {
  animation: fadeIn 0.4s ease;
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
      <a href="{{ route('comunidade.feed') }}" class="btn primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
        Voltar ao Feed
      </a>
    </nav>
  </div>
</header>

<div class="search-container">
  <div class="search-header">
    <h1>
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
      </svg>
      BUSCAR NA COMUNIDADE
    </h1>
    <p>Encontre fichas, imagens, vídeos e muito mais</p>
  </div>

  <!-- Caixa de Busca -->
  <div class="search-box">
    <form action="{{ route('comunidade.buscar') }}" method="GET" class="search-form">
      <input 
        type="text" 
        name="q" 
        value="{{ $termo ?? '' }}"
        placeholder="Digite título ou conteúdo..."
        class="search-input"
      >
      <select name="tipo" class="search-select">
        <option value="">Todos os tipos</option>
        <option value="texto" {{ ($tipo ?? '') == 'texto' ? 'selected' : '' }}>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
            <path d="M12 20h9"></path>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
          </svg>
          Texto
        </option>
        <option value="imagem" {{ ($tipo ?? '') == 'imagem' ? 'selected' : '' }}>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <circle cx="8.5" cy="8.5" r="1.5"></circle>
            <polyline points="21 15 16 10 5 21"></polyline>
          </svg>
          Imagem
        </option>
        <option value="video" {{ ($tipo ?? '') == 'video' ? 'selected' : '' }}>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
            <polygon points="23 7 16 12 23 17 23 7"></polygon>
            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
          </svg>
          Vídeo
        </option>
        <option value="modelo_3d" {{ ($tipo ?? '') == 'modelo_3d' ? 'selected' : '' }}>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <path d="M15 3v18"></path>
            <path d="M3 9h18"></path>
            <path d="M3 15h18"></path>
          </svg>
          Modelo 3D
        </option>
        <option value="ficha" {{ ($tipo ?? '') == 'ficha' ? 'selected' : '' }}>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
          </svg>
          Ficha RPG
        </option>
        <option value="outros" {{ ($tipo ?? '') == 'outros' ? 'selected' : '' }}>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
            <path d="M10 20v-6m4 6v-6m4 6v-6m-8-4v-4h8v4"></path>
            <rect x="3" y="4" width="18" height="16" rx="2"></rect>
          </svg>
          Outros
        </option>
      </select>
      <button type="submit" class="btn primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        Buscar
      </button>
    </form>
  </div>

  <!-- Info de Resultados -->
  @if(isset($termo) && $termo !== '')
    <div class="results-info">
      <p>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <strong>{{ $posts->total() }}</strong> resultado(s) encontrado(s) para: 
        <strong style="color:var(--accent)">"{{ $termo }}"</strong>
        @if($tipo ?? false)
          | Tipo: <strong style="color:var(--accent)">{{ ucfirst(str_replace('_', ' ', $tipo)) }}</strong>
        @endif
      </p>
    </div>
  @endif

  <!-- Grid de Resultados -->
  @if($posts->count() > 0)
    <div class="posts-grid">
      @foreach($posts as $post)
        <article class="post-card">
          <div class="post-media">
            @if($post->arquivos->first() && $post->arquivos->first()->tipo === 'imagem')
              <img src="{{ $post->arquivos->first()->url }}" alt="{{ $post->titulo }}">
            @else
              <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#064e3b,#052e16)">
                @switch($post->tipo_conteudo)
                  @case('video')
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <polygon points="23 7 16 12 23 17 23 7"></polygon>
                      <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                    </svg>
                    @break
                  @case('modelo_3d')
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                      <path d="M15 3v18"></path>
                      <path d="M3 9h18"></path>
                      <path d="M3 15h18"></path>
                    </svg>
                    @break
                  @case('ficha')
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                      <polyline points="14 2 14 8 20 8"></polyline>
                      <line x1="16" y1="13" x2="8" y2="13"></line>
                      <line x1="16" y1="17" x2="8" y2="17"></line>
                      <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    @break
                  @default
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 20h9"></path>
                      <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                @endswitch
              </div>
            @endif
            <span class="post-badge">{{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}</span>
          </div>

          <div class="post-content">
            <h3 class="post-title">{{ $post->titulo }}</h3>
            <p class="post-text">{{ Str::limit($post->conteudo, 150) }}</p>
          </div>

          <div class="post-footer">
            <div class="post-stats">
              <span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                {{ $post->curtidas()->count() }}
              </span>
              <span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                {{ $post->comentarios()->count() }}
              </span>
            </div>
            <a href="{{ route('comunidade.post.show', $post->slug) }}" class="btn-view">
              Ver Postagem
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </a>
          </div>
        </article>
      @endforeach
    </div>

    <!-- Paginação -->
    <div class="pagination-container">
      {{ $posts->appends(['q' => $termo ?? '', 'tipo' => $tipo ?? ''])->links() }}
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
      <h3>Nenhum resultado encontrado</h3>
      @if(isset($termo))
        <p>Tente buscar por outros termos ou selecione um tipo diferente</p>
        <a href="{{ route('comunidade.buscar') }}" class="btn primary" style="margin-top: 16px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          Nova Busca
        </a>
      @else
        <p>Digite algo para buscar na comunidade</p>
      @endif
    </div>
  @endif
</div>

<footer class="footer">
  <div class="footer-bottom">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; vertical-align: middle;">
      <circle cx="12" cy="12" r="10"></circle>
      <line x1="12" y1="8" x2="12" y2="12"></line>
      <line x1="12" y1="16" x2="12.01" y2="16"></line>
    </svg>
    © 2025 Ambience RPG. Todos os direitos reservados.
  </div>
</footer>

</body>
</html>