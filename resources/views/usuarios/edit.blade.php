@extends('layout.app')

@section('content')
<h1>Editar Perfil</h1>

<form method="POST" action="{{ route('usuarios.update', $usuario) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div style="margin-bottom: 15px;">
        <label for="username">Nome de Usuário</label><br>
        <input type="text" id="username" name="username" value="{{ old('username', $usuario->username) }}" 
               required style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        @error('username')
            <small style="color: red; display: block;">{{ $message }}</small>
        @enderror
    </div>

    <div style="margin-bottom: 15px;">
        <label for="nickname">Apelido</label><br>
        <input type="text" id="nickname" name="nickname" value="{{ old('nickname', $usuario->nickname) }}" 
               style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        @error('nickname')
            <small style="color: red; display: block;">{{ $message }}</small>
        @enderror
    </div>

    <div style="margin-bottom: 15px;">
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" 
               required style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        @error('email')
            <small style="color: red; display: block;">{{ $message }}</small>
        @enderror
    </div>

    <div style="margin-bottom: 15px;">
        <label for="password">Nova Senha (deixe vazio para manter a atual)</label><br>
        <input type="password" id="password" name="password" 
               style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        @error('password')
            <small style="color: red; display: block;">{{ $message }}</small>
        @enderror
    </div>

    <!-- FOTO DE PERFIL - VERSÃO SIMPLES -->
    <div style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
        <label style="font-weight: bold; margin-bottom: 10px; display: block;">Foto de Perfil</label>
        
        @if($usuario->getOriginal('avatar_url'))
            <div style="margin-bottom: 15px;">
                <p>Foto atual:</p>
                <img src="{{ $usuario->avatar_url }}" alt="Avatar atual" 
                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
            </div>
        @endif
        
        <div>
            <label for="avatar">Nova foto:</label><br>
            <input type="file" id="avatar" name="avatar" accept="image/*" 
                   style="margin-top: 5px;">
            <small style="display: block; margin-top: 5px; color: #666;">
                JPG, PNG, GIF - máximo 2MB
            </small>
            @error('avatar')
                <small style="color: red; display: block;">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="data_de_nascimento">Data de Nascimento</label><br>
        <input type="date" id="data_de_nascimento" name="data_de_nascimento" 
               value="{{ old('data_de_nascimento', $usuario->data_de_nascimento?->format('Y-m-d')) }}" 
               required style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        @error('data_de_nascimento')
            <small style="color: red; display: block;">{{ $message }}</small>
        @enderror
    </div>

    <div style="margin-bottom: 20px;">
        <label for="bio">Bio</label><br>
        <textarea id="bio" name="bio" 
                  style="width: 300px; height: 100px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">{{ old('bio', $usuario->bio) }}</textarea>
        @error('bio')
            <small style="color: red; display: block;">{{ $message }}</small>
        @enderror
    </div>

    <!-- BOTÕES -->
    <div style="margin-top: 30px;">
        <button type="submit" 
                style="background-color: #007bff; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-right: 15px;">
            Salvar Alterações
        </button>
        
        <a href="{{ route('usuarios.show', $usuario) }}" 
           style="display: inline-block; padding: 12px 24px; text-decoration: none; color: #6c757d; border: 1px solid #6c757d; border-radius: 4px;">
            Cancelar
        </a>
    </div>
</form>

<!-- SEÇÃO PARA REMOVER FOTO SEPARADA -->
@if($usuario->getOriginal('avatar_url'))
<div style="margin-top: 30px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px;">
    <h4>Remover Foto de Perfil</h4>
    <p>Se quiser remover sua foto atual, clique no botão abaixo:</p>
    
    <form action="{{ route('usuarios.deleteAvatar', $usuario) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" 
                onclick="return confirm('Tem certeza que deseja remover sua foto de perfil?')"
                style="background-color: #dc3545; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">
            Remover Foto
        </button>
    </form>
</div>
@endif

<script>
// Debug simples - sem interferência no formulário
console.log('Página de edição carregada');
console.log('User ID: {{ $usuario->id }}');
console.log('Form action: {{ route("usuarios.update", $usuario) }}');

// Apenas log quando o formulário for enviado - SEM INTERCEPTAR
document.querySelector('form').addEventListener('submit', function() {
    console.log('Formulário enviado!');
});
</script>
@endsection
