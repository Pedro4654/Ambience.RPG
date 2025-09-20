@extends('layout.app')

@section('content')
<h1>{{ $usuario->username }}</h1>

<div>
    <!-- Foto de Perfil -->
    <div style="margin-bottom: 20px;">
        <img src="{{ $usuario->avatar_url }}" alt="Avatar de {{ $usuario->username }}" 
             style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #ddd;">
    </div>

    <p><strong>Apelido:</strong> {{ $usuario->nickname ?? 'Não informado' }}</p>
    <p><strong>Nome:</strong> {{ $usuario->nome_completo ?? 'Não informado' }}</p>
    <p><strong>Email:</strong> {{ $usuario->email }}</p>
    <p><strong>Bio:</strong> {{ $usuario->bio ?? 'Não informada' }}</p>
    <p><strong>Membro desde:</strong> {{ $usuario->data_criacao->format('d/m/Y') }}</p>
    <!-- REMOVIDO: Último login -->
    <p><strong>Reputação:</strong> {{ $usuario->pontos_reputacao }} pontos</p>
    <p><strong>Ranking:</strong> #{{ $usuario->ranking_posicao ?: 'N/A' }}</p>
</div>

@can('update', $usuario)
    <a href="{{ route('usuarios.edit', $usuario) }}">Editar Perfil</a>
@endcan

@can('delete', $usuario)
    <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" onsubmit="return confirm('Tem certeza?')">
        @csrf
        @method('DELETE')
        <button type="submit">Excluir Conta</button>
    </form>
@endcan

<script>
console.log('Show profile for user:', {{ $usuario->id }});
console.log('Avatar URL from database:', '{{ $usuario->getOriginal("avatar_url") }}');
console.log('Avatar URL processed:', '{{ $usuario->avatar_url }}');
</script>
@endsection
