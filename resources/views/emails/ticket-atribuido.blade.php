{{-- ===== resources/views/emails/ticket-atribuido.blade.php ===== --}}

@extends('emails.layout')

@section('title', 'Ticket Atribuído')

@section('header-title')
    📋 Novo Ticket Atribuído a Você
@endsection

@section('header-subtitle')
    Ticket #{{ $ticket->numero_ticket }} requer sua atenção
@endsection

@section('content')
    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Olá <strong>{{ $staff->username }}</strong>,
    </p>

    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Um ticket foi atribuído a você e aguarda sua análise.
    </p>

    <div class="ticket-info">
        <h3>📋 Detalhes do Ticket</h3>
        
        <div class="info-row">
            <span class="info-label">Número:</span>
            <span class="info-value"><strong>{{ $ticket->numero_ticket }}</strong></span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Usuário:</span>
            <span class="info-value">{{ $ticket->usuario->username }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Categoria:</span>
            <span class="info-value">{{ $ticket->getCategoriaLabel() }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Assunto:</span>
            <span class="info-value"><strong>{{ $ticket->assunto }}</strong></span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Prioridade:</span>
            <span class="info-value">
                <span class="badge priority-{{ $ticket->prioridade }}">{{ $ticket->getPrioridadeLabel() }}</span>
            </span>
        </div>
    </div>

    <div class="message-content">
        <p><strong>📝 Descrição:</strong></p>
        <p>{{ Str::limit($ticket->descricao, 300) }}</p>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('suporte.show', $ticket->id) }}" class="button">
            👁️ Visualizar e Responder
        </a>
    </div>
@endsection