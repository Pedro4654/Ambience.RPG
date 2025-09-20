@extends('layout.app')

@section('content')
<h1>Cadastro</h1>

<form method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div>
        <label for="username">Nome de Usuário</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" required>
        @error('username')
            <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="nickname">Apelido</label>
        <input type="text" id="nickname" name="nickname" value="{{ old('nickname') }}">
        @error('nickname')
            <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required>
        @error('password')
            <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="avatar">Foto de Perfil</label>
        <input type="file" id="avatar" name="avatar" accept="image/*">
        <small>Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</small>
        @error('avatar')
            <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="data_de_nascimento">Data de Nascimento</label>
        <input type="date" id="data_de_nascimento" name="data_de_nascimento" value="{{ old('data_de_nascimento') }}" required>
        @error('data_de_nascimento')
            <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="bio">Bio</label>
        <textarea id="bio" name="bio">{{ old('bio') }}</textarea>
        @error('bio')
            <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <button type="submit">Cadastrar</button>
</form>
@endsection
