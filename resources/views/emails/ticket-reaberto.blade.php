{{-- ===== resources/views/emails/ticket-reaberto.blade.php ===== --}}

@extends('emails.layout')

@section('title', 'Ticket Reaberto')

@section('header-title')
    🔓 Seu Ticket Foi Reaberto
@endsection

@section('header-subtitle')
    Ticket #{{ $ticket->numero_ticket }} voltou para análise
@endsection

@section('content')
    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Olá <strong>{{ $ticket->usuario->username }}</strong>,
    </p>

    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Seu ticket foi reaberto e está novamente em análise por nossa equipe.
    </p>

    <div class="ticket-info">
        <h3>📋 Informações do Ticket</h3>
        
        <div class="info-row">
            <span class="info-label">Número:</span>
            <span class="info-value"><strong>{{ $ticket->numero_ticket }}</strong></span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Assunto:</span>
            <span class="info-value">{{ $ticket->assunto }}</span>
        </div>
    </div>

    @if($observacao)
    <div class="message-content">
        <p><strong>📝 Motivo da Reabertura:</strong></p>
        <p>{{ $observacao }}</p>
    </div>
    @endif

    <div style="text-align: center;">
        <a href="{{ route('suporte.show', $ticket->id) }}" class="button">
            💬 Acessar Ticket
        </a>
    </div>
@endsection