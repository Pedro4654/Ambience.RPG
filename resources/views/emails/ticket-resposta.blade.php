{{-- ===== resources/views/emails/ticket-resposta.blade.php ===== --}}

@extends('emails.layout')

@section('title', 'Nova Resposta')

@section('header-title')
    💬 Nova Resposta no Seu Ticket!
@endsection

@section('header-subtitle')
    Nossa equipe respondeu seu ticket #{{ $ticket->numero_ticket }}
@endsection

@section('content')
    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Olá <strong>{{ $ticket->usuario->username }}</strong>,
    </p>

    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Temos uma nova resposta para o seu ticket <strong>{{ $ticket->numero_ticket }}</strong>!
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

    <div class="message-content">
        <p><strong>👤 {{ $resposta->usuario->username }} respondeu:</strong></p>
        <p>{{ $resposta->mensagem }}</p>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('suporte.show', $ticket->id) }}" class="button">
            💬 Ver Resposta Completa
        </a>
    </div>

    <p style="font-size: 14px; color: #6b7280; margin-top: 20px;">
        Você pode responder diretamente no ticket acessando o link acima.
    </p>
@endsection