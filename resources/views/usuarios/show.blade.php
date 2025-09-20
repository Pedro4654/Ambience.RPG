@extends('layout.app')

@section('content')
<h1>{{ $usuario->username }}</h1>

<div>
    <p><strong>Apelido:</strong> {{ $usuario->nickname ?? 'Não informado' }}</p>
    <p><strong>Nome:</strong> {{ $usuario->nome_completo ?? 'Não informado' }}</p>
    <p><strong>Email:</strong> {{ $usuario->email }}</p>
    <p><strong>Bio:</strong> {{ $usuario->bio ?? 'Não informada' }}</p>
    <p><strong>Membro desde:</strong> {{ $usuario->data_criacao->format('d/m/Y') }}</p>
    <p><strong>Último login:</strong> {{ $usuario->data_ultimo_login?->format('d/m/Y H:i') ?? 'Nunca' }}</p>
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
@endsection
