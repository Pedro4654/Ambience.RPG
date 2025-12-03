{{-- resources/views/comunidade/show.blade.php - VISUALIZAÇÃO DE POST REFORMULADA --}}
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $post->titulo }} - Ambience RPG</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
/* Usando as mesmas variáveis do feed */
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

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Inter', sans-serif;
  background: var(--bg-dark);
  color: var(--text-primary);
  line-height: 1.6;
  position: relative;
  min-height: 100vh;
}

/* Background */
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

/* Header */
.header {
  position: sticky;
  top: 0;
  z-index: 100;
  background: rgba(26, 35, 50, 0.95);
  backdrop-filter: blur(16px) saturate(180%);
  border-bottom: 1px solid var(--border-color);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 24px;
}

.nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 64px;
}

.logo {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 800;
  font-size: 20px;
  color: #fff;
  text-decoration: none;
}

.logo-img { height: 42px; width: auto; }

.nav-links {
  display: flex;
  gap: 8px;
}

.nav-links a {
  color: var(--text-secondary);
  text-decoration: none;
  font-weight: 500;
  font-size: 14px;
  padding: 8px 16px;
  border-radius: 8px;
  transition: all 0.2s;
}

.nav-links a:hover {
  background: rgba(34, 197, 94, 0.1);
  color: var(--accent);
}

.btn {
  padding: 10px 20px;
  border-radius: 20px;
  font-weight: 700;
  font-size: 14px;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.btn.primary {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
  color: #052e16;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.btn.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
}

/* Post Container */
.post-container {
  max-width: 900px;
  margin: 40px auto;
  padding: 0 24px;
}

/* Post Main Card */
.post-main {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  overflow: hidden;
  margin-bottom: 24px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
}

/* Post Header */
.post-header {
  padding: 24px;
  border-bottom: 1px solid var(--border-color);
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.author-section {
  display: flex;
  gap: 14px;
  align-items: center;
}

.author-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--border-color);
}

.author-info h3 {
  font-size: 16px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 4px;
}

.author-info p {
  font-size: 13px;
  color: var(--text-muted);
}

.post-actions {
  display: flex;
  gap: 8px;
}

.btn-action {
  padding: 8px 14px;
  background: rgba(34, 197, 94, 0.08);
  border: 1px solid var(--border-color);
  color: var(--accent);
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
}

.btn-action:hover {
  background: rgba(34, 197, 94, 0.15);
  transform: translateY(-2px);
}

.btn-action.delete {
  background: rgba(239, 68, 68, 0.08);
  border-color: rgba(239, 68, 68, 0.3);
  color: #ef4444;
}

.btn-action.delete:hover {
  background: rgba(239, 68, 68, 0.15);
}

/* Post Body */
.post-body {
  padding: 28px 24px;
}

.post-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 32px;
  font-weight: 900;
  color: #fff;
  margin-bottom: 20px;
  line-height: 1.3;
}

.post-content {
  color: rgba(255, 255, 255, 0.9);
  font-size: 16px;
  line-height: 1.8;
  white-space: pre-wrap;
}

/* Post Media */
.post-media {
  padding: 0 24px 24px;
}

.media-item {
  margin-bottom: 16px;
  border-radius: 12px;
  overflow: hidden;
}

.media-item img,
.media-item video {
  width: 100%;
  height: auto;
  max-height: 600px;
  object-fit: contain;
  display: block;
  background: #000;
}

/* Post Stats */
.post-stats {
  padding: 20px 24px;
  border-top: 1px solid var(--border-color);
  border-bottom: 1px solid var(--border-color);
  display: flex;
  gap: 28px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--text-muted);
  font-size: 14px;
}

.stat-item strong {
  color: #fff;
  font-size: 18px;
}

/* Interaction Buttons */
.interaction-buttons {
  padding: 20px 24px;
  display: flex;
  gap: 10px;
}

.btn-interact {
  flex: 1;
  padding: 12px;
  background: rgba(34, 197, 94, 0.08);
  border: 1px solid var(--border-color);
  color: var(--accent);
  border-radius: 10px;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-family: 'Inter', sans-serif;
}

.btn-interact:hover {
  background: rgba(34, 197, 94, 0.15);
  transform: translateY(-2px);
}

.btn-interact.active {
  background: rgba(34, 197, 94, 0.2);
  border-color: var(--accent);
}

/* Comments Section */
.comments-section {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 28px 24px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
}

.comments-header {
  font-size: 22px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 24px;
}

/* Comment Form */
.comment-form {
  margin-bottom: 28px;
  padding-bottom: 28px;
  border-bottom: 1px solid var(--border-color);
}

.comment-input-wrapper {
  display: flex;
  gap: 14px;
}

.comment-avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--border-color);
  flex-shrink: 0;
}

.comment-input-group {
  flex: 1;
}

.comment-textarea {
  width: 100%;
  padding: 12px 16px;
  background: rgba(10, 15, 20, 0.6);
  border: 2px solid var(--border-color);
  border-radius: 10px;
  color: #fff;
  font-size: 14px;
  resize: vertical;
  min-height: 100px;
  font-family: 'Inter', sans-serif;
  transition: all 0.3s;
}

.comment-textarea:focus {
  outline: none;
  border-color: var(--accent);
  background: rgba(10, 15, 20, 0.8);
  box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
}

.comment-textarea::placeholder {
  color: var(--text-muted);
}

.error-message {
  display: none;
  color: #ef4444;
  font-size: 13px;
  margin-top: 8px;
}

.comment-submit {
  margin-top: 10px;
  padding: 10px 20px;
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
  color: #052e16;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
  border: none;
  transition: all 0.3s;
  font-family: 'Inter', sans-serif;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.comment-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
}

/* Comment Item */
.comment-item {
  background: rgba(10, 15, 20, 0.4);
  border: 1px solid var(--border-color);
  border-radius: 10px;
  padding: 18px;
  margin-bottom: 14px;
}

.comment-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 10px;
}

.comment-author-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--border-color);
}

.comment-author-name {
  font-size: 14px;
  font-weight: 700;
  color: #fff;
}

.comment-time {
  font-size: 12px;
  color: var(--text-muted);
  margin-left: auto;
}

.comment-text {
  color: rgba(255, 255, 255, 0.9);
  font-size: 14px;
  line-height: 1.6;
  margin-bottom: 10px;
}

.comment-actions {
  display: flex;
  gap: 14px;
}

.comment-action-btn {
  background: none;
  border: none;
  color: var(--text-muted);
  font-size: 12px;
  cursor: pointer;
  transition: color 0.2s;
  font-family: 'Inter', sans-serif;
}

.comment-action-btn:hover {
  color: var(--accent);
}

/* Footer */
.footer {
  background: var(--bg-secondary);
  border-top: 1px solid var(--border-color);
  padding: 32px 0;
  margin-top: 60px;
}

.footer-bottom {
  text-align: center;
  color: var(--text-muted);
  font-size: 13px;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
}

/* Responsive */
@media (max-width: 768px) {
  .nav-links { display: none; }
  .post-title { font-size: 24px; }
  .interaction-buttons { flex-direction: column; }
}

/* SVG Icon Style */
.svg-icon {
  width: 1.2em;
  height: 1.2em;
  vertical-align: -0.15em;
  fill: currentColor;
  overflow: hidden;
}
</style>
</head>
<body>

<!-- HEADER -->
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
      <button class="btn primary" onclick="window.location.href='{{ route('comunidade.feed') }}'">
        <svg class="svg-icon" viewBox="0 0 1024 1024">
          <path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-27.2 65.6 0 89.6l309.6 280z"/>
        </svg>
        Voltar ao Feed
      </button>
    </nav>
  </div>
</header>

<!-- POST CONTAINER -->
<div class="post-container">
  <!-- Post Principal -->
  <article class="post-main">
    <!-- Header -->
    <div class="post-header">
      <div class="author-section">
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
          <h3>{{ $post->autor->username }}</h3>
          <p>{{ $post->created_at->diffForHumans() }} • {{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}</p>
        </div>
      </div>

      @if(Auth::check() && Auth::id() === $post->usuario_id)
        <div class="post-actions">
          <a href="{{ route('comunidade.post.edit', $post->id) }}" class="btn-action">
            <svg class="svg-icon" viewBox="0 0 1024 1024">
              <path d="M878.08 688c26.88-46.72 41.92-100.48 41.92-160 0-185.6-150.4-336-336-336s-336 150.4-336 336 150.4 336 336 336c59.52 0 113.28-15.04 160-41.92l158.08 158.08c12.48 12.48 32.64 12.48 45.12 0s12.48-32.64 0-45.12L878.08 688z m-158.08 0c-123.52 0-224-100.48-224-224s100.48-224 224-224 224 100.48 224 224-100.48 224-224 224z"/>
              <path d="M224 512c0-17.6 14.4-32 32-32s32 14.4 32 32-14.4 32-32 32-32-14.4-32-32zM416 512c0-17.6 14.4-32 32-32s32 14.4 32 32-14.4 32-32 32-32-14.4-32-32zM608 512c0-17.6 14.4-32 32-32s32 14.4 32 32-14.4 32-32 32-32-14.4-32-32z"/>
            </svg>
            Editar
          </a>
          <form action="{{ route('comunidade.post.destroy', $post->id) }}" method="POST" style="display:inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action delete" onclick="return confirm('Tem certeza?')">
              <svg class="svg-icon" viewBox="0 0 1024 1024">
                <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                <path d="M320 320h384v384H320z"/>
              </svg>
              Deletar
            </button>
          </form>
        </div>
      @endif
    </div>

    <!-- Body -->
    <div class="post-body">
      <h1 class="post-title">{{ $post->titulo }}</h1>
      <p class="post-content">{{ $post->conteudo }}</p>
    </div>

    <!-- Mídia -->
    @if($post->arquivos->count() > 0)
      <div class="post-media">
        @foreach($post->arquivos as $arquivo)
          <div class="media-item">
            @if($arquivo->tipo === 'imagem')
              <img src="{{ $arquivo->url }}" alt="{{ $arquivo->nome_arquivo }}">
            @elseif($arquivo->tipo === 'video')
              <video controls>
                <source src="{{ $arquivo->url }}" type="{{ $arquivo->tipo_mime }}">
              </video>
            @elseif($arquivo->tipo === 'modelo_3d')
              <div style="background:rgba(10,15,20,0.8);padding:28px;text-align:center;border-radius:12px">
                <p style="color:#fff;font-weight:700;margin-bottom:12px">
                  <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                    <path d="M384 384h256v256H384z"/>
                  </svg>
                  Modelo 3D: {{ $arquivo->nome_arquivo }}
                </p>
                <a href="{{ $arquivo->url }}" download class="btn-action">
                  <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M512 704l192-192h-128V320h-128v192H320zM832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                  </svg>
                  Baixar Modelo
                </a>
              </div>
            @else
              <div style="background:rgba(10,15,20,0.8);padding:18px;border-radius:12px;display:flex;justify-content:space-between;align-items:center">
                <div>
                  <p style="color:#fff;font-weight:700">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                      <path d="M320 320h384v64H320zM320 448h384v64H320zM320 576h256v64H320z"/>
                    </svg>
                    {{ $arquivo->nome_arquivo }}
                  </p>
                  <p style="color:var(--text-muted);font-size:13px">{{ $arquivo->tamanho_formatado ?? '' }}</p>
                </div>
                <a href="{{ $arquivo->url }}" download class="btn-action">
                  <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M512 704l192-192h-128V320h-128v192H320zM832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                  </svg>
                  Baixar
                </a>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endif

    @if($post->tipo_conteudo === 'ficha_rpg' && $post->fichaRpg)
  @php $ficha = $post->fichaRpg; @endphp
  
  <div class="ficha-display" style="background:linear-gradient(145deg,rgba(5,46,22,0.3),rgba(6,78,59,0.2));border:2px solid #22c55e;border-radius:16px;padding:28px;margin-top:24px">
    <div style="display:flex;gap:20px;margin-bottom:24px">
      @if($ficha->foto_url)
        <img src="{{ $ficha->foto_url }}" alt="{{ $ficha->nome }}" style="width:120px;height:120px;border-radius:12px;object-fit:cover;border:2px solid #22c55e">
      @endif
      
      <div style="flex:1">
        <h2 style="font-size:32px;font-weight:900;color:#22c55e;margin-bottom:8px">{{ $ficha->nome }}</h2>
        <p style="font-size:18px;color:#8b9ba8">Nível {{ $ficha->nivel }} • {{ $ficha->raca }} • {{ $ficha->classe }}</p>
      </div>
    </div>
    
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px">
      <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:10px;padding:14px;text-align:center">
        <div style="font-size:11px;font-weight:700;color:#22c55e;text-transform:uppercase;margin-bottom:6px">FOR</div>
        <div style="font-size:28px;font-weight:900;color:#fff">{{ $ficha->forca }}</div>
      </div>
      
      <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:10px;padding:14px;text-align:center">
        <div style="font-size:11px;font-weight:700;color:#22c55e;text-transform:uppercase;margin-bottom:6px">AGI</div>
        <div style="font-size:28px;font-weight:900;color:#fff">{{ $ficha->agilidade }}</div>
      </div>
      
      <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:10px;padding:14px;text-align:center">
        <div style="font-size:11px;font-weight:700;color:#22c55e;text-transform:uppercase;margin-bottom:6px">VIG</div>
        <div style="font-size:28px;font-weight:900;color:#fff">{{ $ficha->vigor }}</div>
      </div>
      
      <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:10px;padding:14px;text-align:center">
        <div style="font-size:11px;font-weight:700;color:#22c55e;text-transform:uppercase;margin-bottom:6px">INT</div>
        <div style="font-size:28px;font-weight:900;color:#fff">{{ $ficha->inteligencia }}</div>
      </div>
      
      <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:10px;padding:14px;text-align:center">
        <div style="font-size:11px;font-weight:700;color:#22c55e;text-transform:uppercase;margin-bottom:6px">SAB</div>
        <div style="font-size:28px;font-weight:900;color:#fff">{{ $ficha->sabedoria }}</div>
      </div>
      
      <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:10px;padding:14px;text-align:center">
        <div style="font-size:11px;font-weight:700;color:#22c55e;text-transform:uppercase;margin-bottom:6px">CAR</div>
        <div style="font-size:28px;font-weight:900;color:#fff">{{ $ficha->carisma }}</div>
      </div>
    </div>
    
    @if($ficha->habilidades)
      <div style="margin-bottom:16px">
        <h3 style="font-size:14px;font-weight:700;color:#22c55e;text-transform:uppercase;margin-bottom:8px">
          <svg class="svg-icon" viewBox="0 0 1024 1024">
            <path d="M512 832c-211.2 0-384-172.8-384-384 0-185.6 124.8-345.6 307.2-390.4 17.6-4.8 35.2 6.4 40 24 4.8 17.6-6.4 35.2-24 40-150.4 35.2-259.2 169.6-259.2 326.4 0 176 144 320 320 320s320-144 320-320c0-156.8-108.8-291.2-259.2-326.4-17.6-4.8-28.8-22.4-24-40s22.4-28.8 40-24c182.4 44.8 307.2 204.8 307.2 390.4 0 211.2-172.8 384-384 384z"/>
          </svg>
          HABILIDADES
        </h3>
        <p style="color:#e6eef6;line-height:1.6">{{ $ficha->habilidades }}</p>
      </div>
    @endif
    
    @if($ficha->historico)
      <div>
        <h3 style="font-size:14px;font-weight:700;color:#22c55e;text-transform:uppercase;margin-bottom:8px">
          <svg class="svg-icon" viewBox="0 0 1024 1024">
            <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
            <path d="M256 320h512v64H256zM256 448h384v64H256zM256 576h256v64H256z"/>
          </svg>
          HISTÓRICO
        </h3>
        <p style="color:#e6eef6;line-height:1.6">{{ $ficha->historico }}</p>
      </div>
    @endif
  </div>
@endif

    <!-- Estatísticas -->
    <div class="post-stats">
      <div class="stat-item">
        <strong>{{ $post->curtidas()->count() }}</strong>
        <span>
          <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 14px; height: 14px;">
            <path d="M512 832c-211.2 0-384-172.8-384-384 0-185.6 124.8-345.6 307.2-390.4 17.6-4.8 35.2 6.4 40 24 4.8 17.6-6.4 35.2-24 40-150.4 35.2-259.2 169.6-259.2 326.4 0 176 144 320 320 320s320-144 320-320c0-156.8-108.8-291.2-259.2-326.4-17.6-4.8-28.8-22.4-24-40s22.4-28.8 40-24c182.4 44.8 307.2 204.8 307.2 390.4 0 211.2-172.8 384-384 384z"/>
          </svg>
          curtidas
        </span>
      </div>
      <div class="stat-item">
        <strong>{{ $post->comentarios()->count() }}</strong>
        <span>
          <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 14px; height: 14px;">
            <path d="M832 64H192c-70.4 0-128 57.6-128 128v384c0 70.4 57.6 128 128 128h128v128l192-128h320c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 512H416l-96 64v-64H256V192h512v384z"/>
          </svg>
          comentários
        </span>
      </div>
      <div class="stat-item">
        <strong>{{ $post->salvos()->count() }}</strong>
        <span>
          <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 14px; height: 14px;">
            <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
            <path d="M384 448l128 128 224-224 64 64-288 288-192-192z"/>
          </svg>
          salvos
        </span>
      </div>
    </div>

    <!-- Botões de Interação -->
    @if(Auth::check())
      <div class="interaction-buttons">
    @if(Auth::check())
        <!-- Botão Curtir com AJAX -->
        <button 
            id="like-btn"
            class="btn-interact {{ $curtido ? 'active' : '' }}"
            data-post-id="{{ $post->id }}"
            data-curtido="{{ $curtido ? 'true' : 'false' }}"
        >
            <span class="like-icon-svg">
                @if($curtido)
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M512 832c-211.2 0-384-172.8-384-384 0-185.6 124.8-345.6 307.2-390.4 17.6-4.8 35.2 6.4 40 24 4.8 17.6-6.4 35.2-24 40-150.4 35.2-259.2 169.6-259.2 326.4 0 176 144 320 320 320s320-144 320-320c0-156.8-108.8-291.2-259.2-326.4-17.6-4.8-28.8-22.4-24-40s22.4-28.8 40-24c182.4 44.8 307.2 204.8 307.2 390.4 0 211.2-172.8 384-384 384z" fill="#ef4444"/>
                    </svg>
                @else
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                      <path d="M512 832c-211.2 0-384-172.8-384-384 0-185.6 124.8-345.6 307.2-390.4 17.6-4.8 35.2 6.4 40 24 4.8 17.6-6.4 35.2-24 40-150.4 35.2-259.2 169.6-259.2 326.4 0 176 144 320 320 320s320-144 320-320c0-156.8-108.8-291.2-259.2-326.4-17.6-4.8-28.8-22.4-24-40s22.4-28.8 40-24c182.4 44.8 307.2 204.8 307.2 390.4 0 211.2-172.8 384-384 384z" stroke="currentColor" fill="none" stroke-width="40"/>
                    </svg>
                @endif
            </span>
            <span class="like-text">
                @if($curtido)
                    Curtido
                @else
                    Curtir
                @endif
            </span>
        </button>

        @if($salvo)
            <form action="{{ route('comunidade.desalvar', $post->id) }}" method="POST" style="flex:1">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-interact active">
                  <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                    <path d="M384 448l128 128 224-224 64 64-288 288-192-192z"/>
                  </svg>
                  Salvo
                </button>
            </form>
        @else
            <form action="{{ route('comunidade.salvar') }}" method="POST" style="flex:1">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <button type="submit" class="btn-interact">
                  <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                    <path d="M448 320l128 192 128-192h-64V256h-128v64z"/>
                  </svg>
                  Salvar
                </button>
            </form>
        @endif

        <a href="#comentarios" class="btn-interact" style="text-decoration:none">
          <svg class="svg-icon" viewBox="0 0 1024 1024">
            <path d="M832 64H192c-70.4 0-128 57.6-128 128v384c0 70.4 57.6 128 128 128h128v128l192-128h320c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 512H416l-96 64v-64H256V192h512v384z"/>
          </svg>
          Comentar
        </a>
    @else
        <div style="padding:20px 24px;text-align:center">
            <p style="color:var(--text-muted);margin-bottom:14px">Para interagir, você precisa estar logado.</p>
            <button class="btn primary" onclick="window.location.href='{{ route('usuarios.login') }}'">
              <svg class="svg-icon" viewBox="0 0 1024 1024">
                <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                <path d="M416 320h192v64H416zM320 448h384v64H320zM416 576h192v64H416z"/>
              </svg>
              Fazer Login
            </button>
        </div>
    @endif
</div>
    @endif
  </article>

  <!-- Seção de Comentários -->
  <section class="comments-section" id="comentarios">
    <h2 class="comments-header">
      <svg class="svg-icon" viewBox="0 0 1024 1024">
        <path d="M832 64H192c-70.4 0-128 57.6-128 128v384c0 70.4 57.6 128 128 128h128v128l192-128h320c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 512H416l-96 64v-64H256V192h512v384z"/>
      </svg>
      Comentários ({{ $post->comentarios->count() }})
    </h2>

    @if(Auth::check())
      <!-- Formulário de Comentário -->
      <form action="{{ route('comunidade.comentar') }}" method="POST" class="comment-form" id="form-comentario">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">

        <div class="comment-input-wrapper">
          <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" alt="Seu avatar" class="comment-avatar">
          <div class="comment-input-group">
            <textarea 
              id="comentario-conteudo"
              name="conteudo" 
              maxlength="1000"
              placeholder="Deixe um comentário..."
              class="comment-textarea"
            ></textarea>
            <small id="comentario-conteudo-warning" class="error-message">
              <svg class="svg-icon" viewBox="0 0 1024 1024">
                <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                <path d="M512 320c-17.6 0-32 14.4-32 32v192c0 17.6 14.4 32 32 32s32-14.4 32-32V352c0-17.6-14.4-32-32-32zM512 576c-17.6 0-32 14.4-32 32s14.4 32 32 32 32-14.4 32-32-14.4-32-32-32z"/>
              </svg>
              Conteúdo inapropriado detectado
            </small>
            <button type="submit" id="comentar-btn" class="comment-submit">Comentar</button>
          </div>
        </div>
      </form>
    @endif

    <!-- Lista de Comentários -->
    @if($post->comentarios->count() > 0)
      <div>
        @foreach($post->comentarios as $comentario)
          <div class="comment-item">
            <div class="comment-header">
             <a href="{{ route('perfil.show', $comentario->autor->username) }}">
            @php
              // Usar a mesma lógica do post para pegar avatar
              $autorComentario = $comentario->autor;
              
              // Tentar pegar do banco
              try {
                  $pdo = new PDO('mysql:host=127.0.0.1;dbname=ambience_rpg', 'root', '');
                  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  
                  $stmt = $pdo->prepare('SELECT genero, classe_personagem FROM usuarios WHERE id = ?');
                  $stmt->execute([$autorComentario->id]);
                  $dados = $stmt->fetch(PDO::FETCH_ASSOC);
                  
                  $genero = $dados['genero'] ?? 'masculino';
                  $classe = $dados['classe_personagem'] ?? 'ladino';
                  
              } catch (Exception $e) {
                  $genero = 'masculino';
                  $classe = 'ladino';
              }
              
              // Definir avatar
              $avatarPath = "images/avatars/default/{$genero}/{$classe}.png";
              if (!file_exists(public_path($avatarPath))) {
                  $avatarPath = 'images/default-avatar.png';
              }
            @endphp
            
            <img src="{{ asset($avatarPath) }}" 
                 alt="{{ $autorComentario->username }}" 
                 class="comment-author-avatar"
                 onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">
          </a>
              <span class="comment-author-name">{{ $comentario->autor->username }}</span>
              <span class="comment-time">{{ $comentario->created_at->diffForHumans() }}</span>
            </div>
            <p class="comment-text">{{ $comentario->conteudo }}</p>
            <div class="comment-item" id="comentario-{{ $comentario->id }}">
              @if(Auth::check() && Auth::id() === $comentario->usuario_id)
            <button type="button" 
        class="comment-action-btn deletar-comentario" 
        data-comentario-id="{{ $comentario->id }}"
        data-url="{{ route('comunidade.comentario.destroy', $comentario->id) }}">
    <svg class="svg-icon" viewBox="0 0 1024 1024">
      <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
      <path d="M320 320h384v384H320z"/>
    </svg>
    Deletar
</button>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div style="text-align:center;padding:44px 20px;color:var(--text-muted)">
        <p style="font-size:44px;margin-bottom:10px">
          <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 44px; height: 44px;">
            <path d="M832 64H192c-70.4 0-128 57.6-128 128v384c0 70.4 57.6 128 128 128h128v128l192-128h320c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 512H416l-96 64v-64H256V192h512v384z"/>
          </svg>
        </p>
        <p>Nenhum comentário ainda. Seja o primeiro!</p>
      </div>
    @endif
  </section>
</div>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-bottom">© 2025 Ambience RPG. Todos os direitos reservados.</div>
</footer>

<!-- SCRIPTS -->
<script src="{{ asset('js/moderation.js') }}" defer></script>
<script>
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
      el.style.borderColor = '#ef4444';
      el.style.background = 'rgba(239,68,68,0.05)';
      if (warn) warn.style.display = 'block';
    } else {
      el.style.borderColor = 'rgba(34,197,94,0.2)';
      el.style.background = 'rgba(10,15,20,0.6)';
      if (warn) warn.style.display = 'none';
    }
  }

  window.Moderation.attachInput('#comentario-conteudo', 'comentario', {
    onLocal: (res) => applyWarning('#comentario-conteudo', res),
    onServer: (srv) => {
      if (srv && srv.data && srv.data.inappropriate) {
        applyWarning('#comentario-conteudo', { inappropriate: true });
      }
    }
  });

  const formComentario = document.getElementById('form-comentario');
  if (formComentario) {
    formComentario.addEventListener('submit', function(e) {
      const comentarioInapropriado = document.querySelector('#comentario-conteudo[style*="border-color: rgb(239, 68, 68)"]');
      
      if (comentarioInapropriado) {
        e.preventDefault();
        alert('⚠️ Comentário contém conteúdo impróprio. Corrija antes de enviar.');
        return false;
      }
    });
  }
});

// Função para deletar comentário via AJAX
function deletarComentario(comentarioId) {
    if (!confirm('Tem certeza que deseja deletar este comentário?')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/comunidade/comentarios/${comentarioId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            // Remover o comentário da interface
            const comentarioElement = document.getElementById(`comentario-${comentarioId}`);
            if (comentarioElement) {
                comentarioElement.remove();
                
                // Mostrar mensagem de sucesso
                mostrarMensagem('Comentário deletado com sucesso!', 'success');
            }
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Erro ao deletar comentário');
            });
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarMensagem(error.message, 'error');
    });
}


// Sistema de curtidas com AJAX - CORRIGIDO
document.addEventListener('DOMContentLoaded', function() {
    const likeBtn = document.getElementById('like-btn');
    if (!likeBtn) return;
    
    likeBtn.addEventListener('click', async function(e) {
        e.preventDefault();
        
        const postId = this.dataset.postId;
        const isLiked = this.dataset.curtido === 'true';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const likeIcon = this.querySelector('.like-icon-svg svg path');
        const likeText = this.querySelector('.like-text');
        
        // Mostrar loading
        const originalText = likeText.textContent;
        likeText.textContent = 'Carregando...';
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
                    // Atualizar botão - SVG para não curtido
                    likeIcon.setAttribute('fill', 'none');
                    likeIcon.setAttribute('stroke', 'currentColor');
                    likeIcon.setAttribute('stroke-width', '40');
                    likeIcon.removeAttribute('fill'); // Já definido como none
                    likeText.textContent = 'Curtir';
                    this.classList.remove('active');
                    this.dataset.curtido = 'false';
                    
                    // Atualizar contador de curtidas na página
                    const likesCounter = document.querySelector('.stat-item:first-child strong');
                    if (likesCounter) {
                        likesCounter.textContent = data.total_curtidas;
                    }
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
                    // Atualizar botão - SVG para curtido
                    likeIcon.setAttribute('fill', '#ef4444');
                    likeIcon.setAttribute('stroke', 'none');
                    likeIcon.removeAttribute('stroke-width');
                    likeText.textContent = 'Curtido';
                    this.classList.add('active');
                    this.dataset.curtido = 'true';
                    
                    // Atualizar contador de curtidas na página
                    const likesCounter = document.querySelector('.stat-item:first-child strong');
                    if (likesCounter) {
                        likesCounter.textContent = data.total_curtidas;
                    }
                }
            }
        } catch (error) {
            console.error('Erro ao curtir:', error);
            likeText.textContent = originalText;
            alert('Erro ao processar curtida');
        }
        
        this.disabled = false;
    });
});

let processandoDelecao = false;

// Função para deletar comentário via AJAX
function deletarComentario(comentarioId, url) {
    if (processandoDelecao) return;
    
    if (!confirm('Tem certeza que deseja deletar este comentário?')) {
        return;
    }
    
    processandoDelecao = true;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    console.log('Tentando deletar comentário:', comentarioId, 'URL:', url);
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        processandoDelecao = false;
        
        console.log('Resposta recebida:', response.status);
        
        if (response.status === 404) {
            throw new Error('Rota não encontrada. Verifique a configuração.');
        }
        if (response.status === 403) {
            throw new Error('Não autorizado');
        }
        if (!response.ok) {
            throw new Error('Erro na requisição: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Dados recebidos:', data);
        
        if (data.success) {
            // Remover o comentário da interface
            const comentarioElement = document.getElementById(`comentario-${comentarioId}`);
            if (comentarioElement) {
                // Adicionar animação de saída
                comentarioElement.style.opacity = '0';
                comentarioElement.style.transform = 'translateX(-20px)';
                comentarioElement.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    comentarioElement.remove();
                    
                    // Mostrar mensagem de sucesso
                    mostrarMensagem('Comentário deletado com sucesso!', 'success');
                    
                    // Atualizar contador de comentários
                    atualizarContadorComentarios();
                    
                    // Verificar se não há mais comentários
                    verificarComentariosVazios();
                }, 300);
            }
        } else {
            throw new Error(data.message || 'Erro ao deletar comentário');
        }
    })
    .catch(error => {
        processandoDelecao = false;
        console.error('Erro completo:', error);
        mostrarMensagem(error.message || 'Erro ao deletar comentário', 'error');
    });
}

// Função para verificar se não há mais comentários
function verificarComentariosVazios() {
    const listaComentarios = document.getElementById('comentarios-lista');
    if (listaComentarios && listaComentarios.children.length === 0) {
        listaComentarios.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                  <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 64px; height: 64px;">
                    <path d="M832 64H192c-70.4 0-128 57.6-128 128v384c0 70.4 57.6 128 128 128h128v128l192-128h320c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 512H416l-96 64v-64H256V192h512v384z"/>
                  </svg>
                </div>
                <h3 class="empty-state-title">Nenhum comentário ainda</h3>
                <p class="empty-state-description">Seja o primeiro a comentar!</p>
            </div>
        `;
    }
}

// Atualizar event listener - APENAS UMA VEZ
function inicializarEventListeners() {
    // Remover event listeners antigos se existirem
    document.removeEventListener('click', handleClickDeletar);
    
    // Adicionar novo event listener
    document.addEventListener('click', handleClickDeletar);
}

// Função para lidar com clicks
function handleClickDeletar(e) {
    const botao = e.target.closest('.deletar-comentario');
    if (botao) {
        e.preventDefault();
        e.stopPropagation();
        
        const comentarioId = botao.getAttribute('data-comentario-id');
        const url = botao.getAttribute('data-url');
        
        console.log('Botão clicado - ID:', comentarioId, 'URL:', url);
        
        if (!url || url.includes('undefined')) {
            console.error('URL inválida:', url);
            mostrarMensagem('Erro: URL inválida para deletar comentário', 'error');
            return;
        }
        
        deletarComentario(comentarioId, url);
    }
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado, inicializando event listeners...');
    inicializarEventListeners();
});

// Se estiver usando TurboLinks ou Livewire
if (typeof Turbo !== 'undefined') {
    document.addEventListener('turbo:load', inicializarEventListeners);
}
// Função para atualizar contador de comentários
function atualizarContadorComentarios() {
    const contadorElement = document.querySelector('[data-comments-count]');
    if (contadorElement) {
        const contadorAtual = parseInt(contadorElement.textContent) || 0;
        contadorElement.textContent = Math.max(0, contadorAtual - 1);
    }
}

// Função para mostrar mensagens
function mostrarMensagem(texto, tipo) {
    // Remover mensagens anteriores
    const mensagensAntigas = document.querySelectorAll('.mensagem-flutuante');
    mensagensAntigas.forEach(msg => msg.remove());
    
    // Criar nova mensagem
    const mensagem = document.createElement('div');
    mensagem.className = `mensagem-flutuante mensagem-${tipo}`;
    mensagem.textContent = texto;
    mensagem.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${tipo === 'success' ? '#22c55e' : '#ef4444'};
        color: white;
        border-radius: 8px;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        animation: slideIn 0.3s ease;
        font-size: 14px;
        font-weight: 500;
    `;
    
    document.body.appendChild(mensagem);
    
    // Remover após 3 segundos
    setTimeout(() => {
        mensagem.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => mensagem.remove(), 300);
    }, 3000);
}

// Adicionar animações CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);




</script>
</body>
</html>