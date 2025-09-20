@extends('layout.app')

@section('content')
<h1>Login</h1>

<form method="POST" action="{{ route('usuarios.login') }}">
    @csrf
    
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
    </div>
    
    <div>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <div>
        <label for="remember">
            <input type="checkbox" id="remember" name="remember" value="1">
            Lembrar de mim
        </label>
    </div>
    
    <button type="submit">Entrar</button>
</form>

<p><a href="{{ route('usuarios.create') }}">Não tem conta? Cadastre-se</a></p>
@endsection
