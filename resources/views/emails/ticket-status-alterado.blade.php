{{-- ===== resources/views/emails/ticket-status-alterado.blade.php ===== --}}

@extends('emails.layout')

@section('title', 'Status Alterado')

@section('header-title')
    🔄 Status do Seu Ticket Alterado
@endsection

@section('header-subtitle')
    O status do ticket #{{ $ticket->numero_ticket }} foi atualizado
@endsection

@section('content')
    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Olá <strong>{{ $ticket->usuario->username }}</strong>,
    </p>

    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        O status do seu ticket foi alterado.
    </p>

    <div class="ticket-info">
        <h3>📋 Alteração de Status</h3>
        
        <div class="info-row">
            <span class="info-label">Ticket:</span>
            <span class="info-value"><strong>{{ $ticket->numero_ticket }}</strong></span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Status Anterior:</span>
            <span class="info-value">
                <span class="badge status-{{ $statusAntigo }}">
                    {{ ucfirst(str_replace('_', ' ', $statusAntigo)) }}
                </span>
            </span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Novo Status:</span>
            <span class="info-value">
                <span class="badge status-{{ $statusNovo }}">
                    {{ $ticket->getStatusLabel() }}
                </span>
            </span>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('suporte.show', $ticket->id) }}" class="button">
            🔍 Ver Detalhes do Ticket
        </a>
    </div>
@endsection