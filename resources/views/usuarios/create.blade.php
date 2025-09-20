@extends('layout.app')

@section('content')
<h1>Cadastro</h1>

<form method="POST" action="{{ route('usuarios.store') }}">
    @csrf
    
    <div>
        <label for="username">Nome de Usuário:</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" required>
    </div>
    
    <div>
        <label for="nickname">Apelido:</label>
        <input type="text" id="nickname" name="nickname" value="{{ old('nickname') }}">
    </div>
    
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
    </div>
    
    <div>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <div>
        <label for="nome_completo">Nome Completo:</label>
        <input type="text" id="nome_completo" name="nome_completo" value="{{ old('nome_completo') }}">
    </div>
    
    <div>
        <label for="data_de_nascimento">Data de Nascimento:</label>
        <input type="date" id="data_de_nascimento" name="data_de_nascimento" value="{{ old('data_de_nascimento') }}" required>
    </div>
    
    <div>
        <label for="bio">Bio:</label>
        <textarea id="bio" name="bio">{{ old('bio') }}</textarea>
    </div>
    
    <button type="submit">Cadastrar</button>
</form>
@endsection