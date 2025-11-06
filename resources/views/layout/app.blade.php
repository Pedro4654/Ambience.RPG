<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambience RPG</title>

    <!-- nosso módulo global -->
    <script src="{{ asset('js/moderation.js') }}"></script>
</head>

<body>
    <nav>
        @auth
        <a href="{{ route('usuarios.index') }}">Usuários</a>
        <a href="{{ route('usuarios.show', Auth::user()) }}">Meu Perfil</a>
        <form method="POST" action="{{ route('usuarios.logout') }}" style="display: inline;">
            @csrf
            <button type="submit">Sair</button>
        </form>
        @else
        <a href="{{ route('usuarios.login') }}">Login</a>
        <a href="{{ route('usuarios.create') }}">Cadastrar</a>
        @endauth
    </nav>

    <main>
        @if(session('success'))
        <div>{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div>
            @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        @yield('content')
    </main>
</body>

</html>