{{-- ===== resources/views/emails/novo-ticket-staff.blade.php ===== --}}

@extends('emails.layout')

@section('title', 'Novo Ticket')

@section('header-title')
    @if($ticket->ehDenuncia())
        🚨 Nova Denúncia Recebida
    @else
        📩 Novo Ticket de Suporte
    @endif
@endsection

@section('header-subtitle')
    Ticket #{{ $ticket->numero_ticket }} requer atenção da equipe
@endsection

@section('content')
    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Olá equipe,
    </p>

    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        @if($ticket->ehDenuncia())
            Uma nova <strong>denúncia</strong> foi registrada e requer atenção imediata.
        @else
            Um novo ticket de suporte foi criado.
        @endif
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

        @if($ticket->ehDenuncia() && $ticket->usuarioDenunciado)
        <div class="info-row">
            <span class="info-label">Usuário Denunciado:</span>
            <span class="info-value"><strong>{{ $ticket->usuarioDenunciado->username }}</strong></span>
        </div>
        @endif
    </div>

    <div class="message-content">
        <p><strong>📝 Descrição:</strong></p>
        <p>{{ Str::limit($ticket->descricao, 300) }}</p>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('suporte.show', $ticket->id) }}" class="button">
            👁️ Visualizar e Atribuir
        </a>
    </div>
@endsection