@extends('layout.app')

@section('content')
<h1>Usuários</h1>

@foreach($usuarios as $usuario)
<div style="display: flex; align-items: center; margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
    <!-- Foto de Perfil -->
    <div style="margin-right: 15px;">
        <img src="{{ $usuario->avatar_url }}" alt="Avatar de {{ $usuario->username }}" 
             style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
    </div>
    
    <!-- Informações do usuário -->
    <div>
        <h3><a href="{{ route('usuarios.show', $usuario) }}">{{ $usuario->username }}</a></h3>
        <p>{{ $usuario->nickname ?? 'Sem apelido' }}</p>
        <p>Membro desde: {{ $usuario->data_criacao->format('d/m/Y') }}</p>
        <!-- REMOVIDO: Último login -->
        <p>Reputação: {{ $usuario->pontos_reputacao }} pontos</p>
    </div>
</div>
@endforeach

{{ $usuarios->links() }}

<script>
console.log('Index page loaded with {{ count($usuarios) }} users');
@foreach($usuarios as $index => $usuario)
console.log('User {{ $index }}:', {
    id: {{ $usuario->id }},
    username: '{{ $usuario->username }}',
    avatar_db: '{{ $usuario->getOriginal("avatar_url") }}',
    avatar_url: '{{ $usuario->avatar_url }}'
});
@endforeach
</script>
@endsection
