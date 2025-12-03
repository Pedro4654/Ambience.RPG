{{-- resources/views/comunidade/saved.blade.php --}}
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Posts Salvos - Ambience RPG</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
:root{--bg-dark:#0a0f14;--card:#1f2a33;--muted:#8b9ba8;--accent:#22c55e;--accent-light:#16a34a;--accent-dark:#15803d;--hero-green:#052e16;--text-on-primary:#e6eef6;--transition-speed:600ms;--header-bg:rgba(10,15,20,0.75);--gradient-start:#052e16;--gradient-mid:#064e3b;--gradient-end:#065f46;--btn-gradient-start:#22c55e;--btn-gradient-end:#16a34a;--accent-border:rgba(34,197,94,0.4)}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Inter,system-ui,-apple-system,sans-serif;background:#0a0f14;color:var(--text-on-primary);-webkit-font-smoothing:antialiased;line-height:1.5}

.header{position:sticky;top:0;z-index:100;background:var(--header-bg);backdrop-filter:blur(12px);border-bottom:1px solid rgba(34,197,94,0.12)}
.container{max-width:1280px;margin:0 auto;padding:0 32px}
.nav{display:flex;align-items:center;justify-content:space-between;padding:18px 0;height:70px}
.logo{display:flex;align-items:center;gap:12px;font-weight:800;font-size:19px;color:#fff;text-decoration:none}
.logo-img{height:50px;width:auto}
.nav-links{display:flex;gap:32px;align-items:center}
.nav-links a{color:rgba(255,255,255,0.9);text-decoration:none;font-weight:500;font-size:15px;transition:color .2s}
.nav-links a:hover{color:var(--accent)}
.btn{padding:11px 22px;border-radius:10px;font-weight:700;font-size:15px;border:none;cursor:pointer;transition:all .25s;display:inline-flex;align-items:center;gap:10px;text-decoration:none}
.btn.primary{background:linear-gradient(to right,var(--btn-gradient-start),var(--btn-gradient-end));color:#052e16;box-shadow:0 4px 14px rgba(34,197,94,0.3)}
.btn.primary:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(34,197,94,0.4)}

.saved-container{max-width:1100px;margin:60px auto;padding:0 32px}
.saved-header{background:linear-gradient(145deg,rgba(234,179,8,0.15),rgba(234,179,8,0.05));border:1px solid rgba(234,179,8,0.3);border-radius:20px;padding:32px;margin-bottom:48px;text-align:center}
.saved-header h1{font-family:Montserrat,sans-serif;font-size:48px;font-weight:900;color:#fff;margin-bottom:12px}
.saved-header p{color:rgba(255,255,255,0.8);font-size:17px}

.posts-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}

.post-card{background:linear-gradient(145deg,rgba(31,42,51,0.6),rgba(20,28,35,0.4));border:1px solid rgba(34,197,94,0.1);border-radius:16px;overflow:hidden;transition:all .3s cubic-bezier(.2,.8,.2,1);position:relative}
.post-card:hover{transform:translateY(-4px);border-color:rgba(34,197,94,0.3);box-shadow:0 12px 32px rgba(0,0,0,0.4)}

.saved-badge{position:absolute;top:12px;right:12px;padding:6px 12px;background:rgba(234,179,8,0.9);color:#000;border-radius:8px;font-size:11px;font-weight:700;z-index:10}

.post-media{height:200px;background:#000;position:relative;overflow:hidden}
.post-media img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.post-card:hover .post-media img{transform:scale(1.05)}

.post-content{padding:20px}
.post-title{font-size:17px;font-weight:700;color:#fff;margin-bottom:8px;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.post-text{color:rgba(255,255,255,0.7);font-size:14px;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:12px}

.post-footer{display:flex;gap:8px;padding:0 20px 20px}
.btn-view{flex:1;padding:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.15);color:var(--accent);border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:all .25s;text-decoration:none;text-align:center;display:block}
.btn-view:hover{background:rgba(34,197,94,0.15);transform:translateY(-2px)}
.btn-remove{flex:1;padding:10px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.3);color:#ef4444;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:all .25s;font-family:Inter,sans-serif}
.btn-remove:hover{background:rgba(239,68,68,0.15)}

.empty-state{text-align:center;padding:80px 32px;background:linear-gradient(145deg,rgba(31,42,51,0.6),rgba(20,28,35,0.4));border:1px solid rgba(34,197,94,0.1);border-radius:16px}
.empty-state-icon{font-size:64px;margin-bottom:16px}
.empty-state h3{font-size:24px;font-weight:700;color:#fff;margin-bottom:8px}
.empty-state p{color:var(--muted);margin-bottom:24px}

.footer{border-top:1px solid rgba(34,197,94,0.1);padding:48px 0 24px;background:#0d1419;margin-top:80px}
.footer-bottom{text-align:center;color:var(--muted);font-size:13px;max-width:1200px;margin:0 auto;padding:24px 32px}

@media(max-width:900px){
  .posts-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:768px){
  .nav-links{display:none}
  .posts-grid{grid-template-columns:1fr}
  .saved-header h1{font-size:36px}
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
      <a href="{{ route('comunidade.feed') }}" class="btn primary">← Voltar</a>
    </nav>
  </div>
</header>

<div class="saved-container">
  <!-- Header -->
  <div class="saved-header">
    <h1>💾 MEUS POSTS SALVOS</h1>
    <p>{{ $saved_posts->total() }} postagens salvas</p>
  </div>

  <!-- Grid de Posts Salvos -->
  @if($saved_posts->count() > 0)
    <div class="posts-grid">
      @foreach($saved_posts as $saved)
        @php $post = $saved->post; @endphp
        <article class="post-card">
          <span class="saved-badge">💾 Salvo</span>

          <div class="post-media">
            @if($post->arquivos->first() && $post->arquivos->first()->tipo === 'imagem')
              <img src="{{ $post->arquivos->first()->url }}" alt="{{ $post->titulo }}">
            @else
              <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#064e3b,#052e16)">
                <span style="font-size:48px">📝</span>
              </div>
            @endif
          </div>

          <div class="post-content">
            <h3 class="post-title">{{ $post->titulo }}</h3>
            <p class="post-text">{{ $post->conteudo }}</p>
          </div>

          <div class="post-footer">
            <a href="{{ route('comunidade.post.show', $post->slug) }}" class="btn-view">Ver Post</a>
            <form action="{{ route('comunidade.desalvar', $post->id) }}" method="POST" style="flex:1;margin:0">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-remove" style="width:100%" onclick="return confirm('Remover dos salvos?')">🗑️ Remover</button>
            </form>
          </div>
        </article>
      @endforeach
    </div>

    <!-- Paginação -->
    <div style="margin-top:40px">
      {{ $saved_posts->links() }}
    </div>
  @else
    <div class="empty-state">
      <div class="empty-state-icon">💾</div>
      <h3>Nenhuma postagem salva ainda</h3>
      <p>Salve suas postagens favoritas para acessá-las rapidamente</p>
      <a href="{{ route('comunidade.feed') }}" class="btn primary">Explorar Feed →</a>
    </div>
  @endif
</div>

<footer class="footer">
  <div class="footer-bottom">© 2025 Ambience RPG. Todos os direitos reservados.</div>
</footer>

</body>
</html>