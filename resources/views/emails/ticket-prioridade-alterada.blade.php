{{-- ===== resources/views/emails/ticket-prioridade-alterada.blade.php ===== --}}

@extends('emails.layout')

@section('title', 'Prioridade Alterada')

@section('header-title')
    ⚠️ Prioridade do Ticket Alterada
@endsection

@section('header-subtitle')
    A prioridade do ticket #{{ $ticket->numero_ticket }} foi ajustada
@endsection

@section('content')
    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Olá <strong>{{ $ticket->usuario->username }}</strong>,
    </p>

    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        A prioridade do seu ticket foi alterada por nossa equipe.
    </p>

    <div class="ticket-info">
        <h3>⚠️ Alteração de Prioridade</h3>
        
        <div class="info-row">
            <span class="info-label">Ticket:</span>
            <span class="info-value"><strong>{{ $ticket->numero_ticket }}</strong></span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Prioridade Anterior:</span>
            <span class="info-value">
                <span class="badge priority-{{ $prioridadeAnterior }}">
                    {{ ucfirst($prioridadeAnterior) }}
                </span>
            </span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Nova Prioridade:</span>
            <span class="info-value">
                <span class="badge priority-{{ $prioridadeNova }}">
                    {{ $ticket->getPrioridadeLabel() }}
                </span>
            </span>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('suporte.show', $ticket->id) }}" class="button">
            🔍 Ver Ticket
        </a>
    </div>
@endsection
