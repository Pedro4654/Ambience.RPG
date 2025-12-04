<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Tickets - Ambience RPG</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0a0f14;
            --card: #1f2a33;
            --muted: #8b9ba8;
            --accent: #22c55e;
            --accent-light: #16a34a;
            --accent-dark: #15803d;
            --hero-green: #052e16;
            --text-on-primary: #e6eef6;
            --transition-speed: 600ms;
            --header-bg: rgba(10, 15, 20, 0.75);
    --gradient-start: #022c22;  
    --gradient-mid:   #034935ff;  
    --gradient-end:   #22553dff; 
            --btn-gradient-start: #22c55e;
            --btn-gradient-end: #16a34a;
            --accent-border: rgba(34, 197, 94, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Inter, system-ui, -apple-system, sans-serif;
            background: linear-gradient(145deg, #0a0f14f4, #141c23f2);
            color: var(--text-on-primary);
            -webkit-font-smoothing: antialiased;
            line-height: 1.5;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

       .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--muted);
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 24px;
        transition: all 0.2s;
    }

    .back-link:hover {
        color: var(--accent);
        transform: translateX(-5px);
    }

        /* Page Header */
        .page-header {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .header-content h1 {
            font-family: Montserrat, sans-serif;
            font-size: 36px;
            color: #fff;
            margin-bottom: 8px;
            font-weight: 900;
        }

        .header-content p {
            color: var(--muted);
            font-size: 16px;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: Inter, sans-serif;
        }

        .btn-primary {
            background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
            color: var(--hero-green);
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        .btn svg {
            width: 20px;
            height: 20px;
        }

        /* Alerts */
        .alert {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideIn 0.3s ease-out;
            background: linear-gradient(145deg, rgba(31, 42, 51, 0.6), rgba(20, 28, 35, 0.4));
            border-left: 4px solid;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            border-left-color: var(--accent);
            background: linear-gradient(145deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.05));
        }

        .alert-error {
            border-left-color: #ef4444;
            background: linear-gradient(145deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.05));
        }

        .alert svg {
            width: 24px;
            height: 24px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            border: 1px solid rgba(34, 197, 94, 0.1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.05), transparent 70%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
            border-color: rgba(34, 197, 94, 0.3);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.1));
        }

        .stat-content h3 {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-content p {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
        }

        /* Tickets List */
        .tickets-container {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            overflow: hidden;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .ticket-item {
            padding: 25px 30px;
            border-bottom: 2px solid rgba(34, 197, 94, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .ticket-item:last-child {
            border-bottom: none;
        }

        .ticket-item:hover {
            background: rgba(34, 197, 94, 0.05);
        }

        .ticket-header {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .ticket-id {
            font-size: 18px;
            font-weight: 700;
            color: var(--accent);
            text-decoration: none;
            transition: color 0.2s;
        }

        .ticket-id:hover {
            color: var(--accent-light);
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge.status-novo { background: #3bf66a33; color: #00ff33ff; }
        .badge.status-em_analise { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
        .badge.status-aguardando_resposta { background: rgba(168, 85, 247, 0.2); color: #c084fc; }
        .badge.status-resolvido { background: rgba(34, 197, 94, 0.2); color: var(--accent); }
        .badge.status-fechado { background: rgba(156, 163, 175, 0.2); color: #9ca3af; }

        .badge.priority-urgente { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        .badge.priority-alta { background: rgba(251, 146, 60, 0.2); color: #fb923c; }
        .badge.priority-normal { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
        .badge.priority-baixa { background: rgba(156, 163, 175, 0.2); color: #9ca3af; }

        .badge.category { background: rgba(34, 197, 94, 0.1); color: var(--muted); }

        .ticket-title {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .ticket-description {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .ticket-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
            font-size: 14px;
            color: var(--muted);
        }

        .ticket-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .ticket-meta-item svg {
            width: 16px;
            height: 16px;
        }

        .ticket-meta-item.denuncia {
            color: #ef4444;
            font-weight: 600;
        }

        .ticket-actions {
            margin-top: 15px;
        }

        .btn-view {
            padding: 10px 24px;
            background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
            color: var(--hero-green);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 30px;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            color: var(--muted);
            opacity: 0.4;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            color: #fff;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 16px;
            margin-bottom: 25px;
        }

        /* Pagination */
        .pagination-container {
            padding: 25px 30px;
            border-top: 2px solid rgba(34, 197, 94, 0.1);
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .page-header {
                padding: 25px 20px;
            }

            .header-content h1 {
                font-size: 28px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .ticket-item {
                padding: 20px;
            }

            .ticket-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .badge {
                font-size: 11px;
                padding: 5px 12px;
            }
        }

        .stat-icon img {
    width: 44px;
    height: 44px;
}

    </style>
</head>
<body>
    <div class="container">

    <!-- Back Button -->
        <!-- Botão de voltar para HOME (/) -->
<a href="{{ auth()->user()->isStaff() ? route('suporte.moderacao.index') : route('home') }}" class="back-link">
    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Voltar
</a>
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <h1> Meus Tickets</h1>
                <p>Gerencie suas solicitações de suporte</p>
            </div>
            <a href="{{ route('suporte.create') }}" class="btn btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Ticket
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="/images/ICONS/grafico.png" alt="Resolvido">
                </div>
                <div class="stat-content">
                    <h3>Total de Tickets</h3>
                    <p>{{ $tickets->total() }}</p>
                </div>
            </div>

            <div class="stat-card">
    <div class="stat-icon">
        <img src="/images/ICONS/ampulheta.png" alt="Resolvido">
    </div>
    <div class="stat-content">
        <h3>Abertos</h3>
                    <p>{{ $totalAbertos }}</p>
    </div>
</div>


            <div class="stat-card">
    <div class="stat-icon">
        <img src="/images/ICONS/confirm.png" alt="Resolvido">
    </div>
    <div class="stat-content">
        <h3>Resolvidos</h3>
        <p>{{ $totalFechados }}</p>
    </div>
</div>

        </div>

        <!-- Tickets List -->
        <div class="tickets-container">
            @if($tickets->count() > 0)
                @foreach($tickets as $ticket)
                    <div class="ticket-item">
                        <div class="ticket-header">
                            <a href="{{ route('suporte.show', $ticket->id) }}" class="ticket-id">
                                #{{ $ticket->numero_ticket }}
                            </a>
                            
                            <span class="badge status-{{ $ticket->status }}">
                                {{ $ticket->getStatusLabel() }}
                            </span>

                            <span class="badge priority-{{ $ticket->prioridade }}">
                                {{ $ticket->getPrioridadeLabel() }}
                            </span>

                            <span class="badge category">
                                {{ $ticket->getCategoriaLabel() }}
                            </span>
                        </div>

                        <h3 class="ticket-title">{{ $ticket->assunto }}</h3>

                        <p class="ticket-description">
                            {{ Str::limit($ticket->descricao, 200) }}
                        </p>

                        <div class="ticket-meta">
                            <div class="ticket-meta-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $ticket->created_at->diffForHumans() }}
                            </div>

                            @if($ticket->respostas_count > 0)
                                <div class="ticket-meta-item">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    {{ $ticket->respostas_count }} {{ $ticket->respostas_count == 1 ? 'resposta' : 'respostas' }}
                                </div>
                            @endif

                            @if($ticket->anexos_count > 0)
                                <div class="ticket-meta-item">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    {{ $ticket->anexos_count }} {{ $ticket->anexos_count == 1 ? 'anexo' : 'anexos' }}
                                </div>
                            @endif

                            @if($ticket->ehDenuncia() && $ticket->usuarioDenunciado)
                                <div class="ticket-meta-item denuncia">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Denúncia: {{ '@' . $ticket->usuarioDenunciado->username }}
                                </div>
                            @endif
                        </div>

                        <div class="ticket-actions">
                            <a href="{{ route('suporte.show', $ticket->id) }}" class="btn-view">
                                Ver Detalhes →
                            </a>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                @if($tickets->hasPages())
                    <div class="pagination-container">
                        {{ $tickets->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3>Nenhum ticket ainda</h3>
                    <p>Você ainda não criou nenhum ticket de suporte.</p>
                    <a href="{{ route('suporte.create') }}" class="btn btn-primary">
                        <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Criar Primeiro Ticket
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>