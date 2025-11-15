@extends('emails.layout')

@section('title', 'Ticket Criado')

@section('header-title')
    ✅ Ticket Criado com Sucesso!
@endsection

@section('header-subtitle')
    Seu ticket foi registrado e nossa equipe já foi notificada
@endsection

@section('content')
    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Olá <strong>{{ $ticket->usuario->username }}</strong>,
    </p>

    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Seu ticket de suporte foi criado com sucesso! Nossa equipe já foi notificada e irá analisá-lo em breve.
    </p>

    <div class="ticket-info">
        <h3>📋 Detalhes do Ticket</h3>
        
        <div class="info-row">
            <span class="info-label">Número:</span>
            <span class="info-value"><strong>{{ $ticket->numero_ticket }}</strong></span>
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
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="badge status-{{ $ticket->status }}">{{ $ticket->getStatusLabel() }}</span>
            </span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Prioridade:</span>
            <span class="info-value">
                <span class="badge priority-{{ $ticket->prioridade }}">{{ $ticket->getPrioridadeLabel() }}</span>
            </span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Criado em:</span>
            <span class="info-value">{{ $ticket->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }} (GMT-3)</span>
        </div>
    </div>

    <div class="message-content">
        <p><strong>📝 Sua mensagem:</strong></p>
        <p>{{ $ticket->descricao }}</p>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('suporte.show', $ticket->id) }}" class="button">
            🔍 Ver Detalhes do Ticket
        </a>
    </div>

    <div class="divider"></div>

    <p style="font-size: 14px; color: #6b7280;">
        <strong>O que acontece agora?</strong>
    </p>
    <ul style="font-size: 14px; color: #6b7280; margin: 10px 0 0 20px;">
        <li>Nossa equipe irá analisar seu ticket</li>
        <li>Você receberá um email quando houver atualizações</li>
        <li>Você pode acompanhar o status acessando o link acima</li>
    </ul>
@endsection