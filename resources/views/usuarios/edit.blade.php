@extends('layout.app')

@section('content')
<h1>Editar Perfil</h1>

<form method="POST" action="{{ route('usuarios.update', $usuario) }}">
    @csrf
    @method('PUT')
    
    <div>
        <label for="username">Nome de Usuário:</label>
        <input type="text" id="username" name="username" value="{{ old('username', $usuario->username) }}" required>
    </div>
    
    <div>
        <label for="nickname">Apelido:</label>
        <input type="text" id="nickname" name="nickname" value="{{ old('nickname', $usuario->nickname) }}">
    </div>
    
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
    </div>
    
    <div>
        <label for="password">Nova Senha (deixe vazio para manter a atual):</label>
        <input type="password" id="password" name="password">
    </div>
    
    <div>
        <label for="nome_completo">Nome Completo:</label>
        <input type="text" id="nome_completo" name="nome_completo" value="{{ old('nome_completo', $usuario->nome_completo) }}">
    </div>
    
    <div>
        <label for="data_de_nascimento">Data de Nascimento:</label>
        <input type="date" id="data_de_nascimento" name="data_de_nascimento" value="{{ old('data_de_nascimento', $usuario->data_de_nascimento?->format('Y-m-d')) }}" required>
    </div>
    
    <div>
        <label for="bio">Bio:</label>
        <textarea id="bio" name="bio">{{ old('bio', $usuario->bio) }}</textarea>
    </div>
    
    <button type="submit">Atualizar</button>
    <a href="{{ route('usuarios.show', $usuario) }}">Cancelar</a>
</form>
@endsection