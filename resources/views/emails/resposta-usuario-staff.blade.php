{{-- ===== resources/views/emails/resposta-usuario-staff.blade.php ===== --}}

@extends('emails.layout')

@section('title', 'Usuário Respondeu')

@section('header-title')
    💬 Usuário Respondeu no Ticket
@endsection

@section('header-subtitle')
    Nova resposta no ticket #{{ $ticket->numero_ticket }}
@endsection

@section('content')
    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Olá <strong>{{ $ticket->atribuidoA->username }}</strong>,
    </p>

    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        O usuário <strong>{{ $resposta->usuario->username }}</strong> respondeu no ticket atribuído a você.
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
        <p><strong>👤 {{ $resposta->usuario->username }} escreveu:</strong></p>
        <p>{{ $resposta->mensagem }}</p>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('suporte.show', $ticket->id) }}" class="button">
            💬 Ver e Responder
        </a>
    </div>
@endsection