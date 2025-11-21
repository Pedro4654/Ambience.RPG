{{-- ===== resources/views/emails/ticket-fechado.blade.php ===== --}}

@extends('emails.layout')

@section('title', 'Ticket Fechado')

@section('header-title')
    ✅ Seu Ticket Foi Fechado
@endsection

@section('header-subtitle')
    Ticket #{{ $ticket->numero_ticket }} foi concluído
@endsection

@section('content')
    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Olá <strong>{{ $ticket->usuario->username }}</strong>,
    </p>

    <p style="font-size: 15px; color: #374151; margin-bottom: 20px;">
        Seu ticket foi fechado. Esperamos ter resolvido seu problema!
    </p>

    <div class="ticket-info">
        <h3>📋 Resumo do Ticket</h3>
        
        <div class="info-row">
            <span class="info-label">Número:</span>
            <span class="info-value"><strong>{{ $ticket->numero_ticket }}</strong></span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Assunto:</span>
            <span class="info-value">{{ $ticket->assunto }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Criado em:</span>
            <span class="info-value">{{ $ticket->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Fechado em:</span>
            <span class="info-value">{{ $ticket->data_fechamento->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    @if($observacao)
    <div class="message-content">
        <p><strong>📝 Observação:</strong></p>
        <p>{{ $observacao }}</p>
    </div>
    @endif

    <div style="text-align: center;">
        <a href="{{ route('suporte.show', $ticket->id) }}" class="button">
            📜 Ver Histórico Completo
        </a>
    </div>

    <p style="font-size: 14px; color: #6b7280; margin-top: 20px;">
        Se você tiver alguma dúvida ou problema persistir, sinta-se à vontade para abrir um novo ticket.
    </p>
@endsection
