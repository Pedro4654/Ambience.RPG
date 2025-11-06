{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ambience RPG — Página Inicial</title>
    <style>
        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background: #0f172a; color: #e6eef8; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; }
        .card { background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.02)); padding:32px; border-radius:12px; box-shadow: 0 10px 30px rgba(2,6,23,0.6); width: 330px; text-align:center; }
        h1 { margin:0 0 12px 0; font-size:20px; letter-spacing: 0.6px; }
        p { margin:0 0 18px 0; color:#9fb0cc; font-size:14px; }
        .btn { display:block; width:100%; padding:12px 16px; margin-bottom:8px; text-decoration:none; border-radius:8px; font-weight:600; }
        .btn-ghost { background:transparent; border:1px solid rgba(255,255,255,0.08); color:#e6eef8; }
        .btn-primary { background:#2563eb; color:white; border: none; }
        .btn-secondary { background:#10b981; color:white; border: none; }
        .small { font-size:12px; color:#7f98b3; margin-top:10px; }
        .logout-form { margin-top:8px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Ambience RPG</h1>
        <p>Bem-vindo. Faça login ou crie uma conta — ou vá direto para as áreas internas se já estiver logado.</p>

        @guest
            <a class="btn btn-primary" href="{{ route('usuarios.login') }}">Entrar</a>
            <a class="btn btn-secondary" href="{{ route('usuarios.create') }}">Criar conta</a>
            <div class="small">Já tem conta? Clique em Entrar. Não tem? Crie uma — é rapidinho.</div>
        @else
            <a class="btn btn-primary" href="{{ route('usuarios.index') }}">Usuários</a>
            <a class="btn btn-secondary" href="{{ route('salas.index') }}">Salas</a>

            <form class="logout-form" method="POST" action="{{ route('usuarios.logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost" style="margin-top:12px;">Sair</button>
            </form>

            <div class="small">Logado como <strong>{{ auth()->user()->username ?? auth()->user()->email }}</strong></div>
        @endguest
    </div>
</body>
</html>
