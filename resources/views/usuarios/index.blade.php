@extends('layout.app')

@section('content')
<h1>Usuários</h1>

@foreach($usuarios as $usuario)
    <div>
        <h3><a href="{{ route('usuarios.show', $usuario) }}">{{ $usuario->username }}</a></h3>
        <p>{{ $usuario->nickname ?? 'Sem apelido' }}</p>
        <p>Membro desde: {{ $usuario->data_criacao->format('d/m/Y') }}</p>
        <p>Reputação: {{ $usuario->pontos_reputacao }} pontos</p>
    </div>
@endforeach

{{ $usuarios->links() }}
@endsection