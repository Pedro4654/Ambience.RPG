<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Painel de Moderação - Ambience RPG</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Header */
        .page-header {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .page-header h1 {
            font-size: 32px;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #718096;
            font-size: 16px;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        /* Search Section */
        .search-section {
            margin-top: 20px;
        }

        .search-wrapper {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 14px 48px 14px 48px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #9ca3af;
        }

        .search-hint {
            margin-top: 8px;
            font-size: 12px;
            color: #6b7280;
        }

        .quick-filters {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .filter-chip {
            padding: 6px 14px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #4b5563;
        }

        .filter-chip:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
        }

        .filter-chip.blue:hover { background: #dbeafe; border-color: #3b82f6; color: #1e40af; }
        .filter-chip.orange:hover { background: #ffedd5; border-color: #f97316; color: #9a3412; }
        .filter-chip.red:hover { background: #fee2e2; border-color: #ef4444; color: #991b1b; }
        .filter-chip.green:hover { background: #d1fae5; border-color: #10b981; color: #065f46; }

        /* Alerts */
        .alert {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideIn 0.3s ease-out;
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
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.12);
        }

        .stat-card.blue { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); }
        .stat-card.purple { background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); }
        .stat-card.red { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); }
        .stat-card.yellow { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); }
        .stat-card.gray { background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); }

        .stat-label {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-card.blue .stat-label { color: #1e40af; }
        .stat-card.purple .stat-label { color: #7c3aed; }
        .stat-card.red .stat-label { color: #dc2626; }
        .stat-card.yellow .stat-label { color: #d97706; }
        .stat-card.gray .stat-label { color: #374151; }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
        }

        .stat-card.blue .stat-value { color: #1e3a8a; }
        .stat-card.purple .stat-value { color: #6b21a8; }
        .stat-card.red .stat-value { color: #991b1b; }
        .stat-card.yellow .stat-value { color: #92400e; }
        .stat-card.gray .stat-value { color: #1f2937; }

        /* Filters Card */
        .filters-card {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .filters-form {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group.search {
            min-width: 300px;
        }

        .filter-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .filter-input, .filter-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 14px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
        }

        .btn-filter {
            padding: 12px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        }

        th {
            padding: 16px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        td {
            padding: 20px;
            font-size: 14px;
            color: #374151;
        }

        .ticket-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .ticket-number {
            font-size: 15px;
            font-weight: 700;
            color: #667eea;
            text-decoration: none;
            transition: color 0.2s;
        }

        .ticket-number:hover {
            color: #764ba2;
        }

        .ticket-subject {
            font-size: 13px;
            color: #6b7280;
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
            color: #1a202c;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Category Badges */
        .badge.cat-denuncia { background: #fee2e2; color: #991b1b; }
        .badge.cat-problema { background: #ffedd5; color: #9a3412; }
        .badge.cat-duvida { background: #dbeafe; color: #1e40af; }
        .badge.cat-default { background: #e5e7eb; color: #374151; }

        /* Status Badges */
        .badge.status-novo { background: #dbeafe; color: #1e40af; }
        .badge.status-analise { background: #fef3c7; color: #92400e; }
        .badge.status-resolvido, .badge.status-fechado { background: #d1fae5; color: #065f46; }
        .badge.status-spam { background: #fee2e2; color: #991b1b; }
        .badge.status-default { background: #e5e7eb; color: #374151; }

        /* Priority Badges */
        .badge.priority-urgente { background: #fee2e2; color: #991b1b; }
        .badge.priority-alta { background: #ffedd5; color: #9a3412; }
        .badge.priority-normal { background: #dbeafe; color: #1e40af; }
        .badge.priority-baixa { background: #e5e7eb; color: #374151; }

        .assigned-to {
            font-size: 13px;
            color: #374151;
        }

        .not-assigned {
            font-size: 13px;
            color: #9ca3af;
            font-style: italic;
        }

        .date-text {
            font-size: 13px;
            color: #6b7280;
        }

        .btn-view {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }

        .btn-view:hover {
            color: #764ba2;
        }

        .empty-row {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-row td {
            color: #9ca3af;
            font-size: 15px;
        }

        /* Pagination */
        .pagination-container {
            padding: 25px 30px;
            border-top: 2px solid #f3f4f6;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .page-header {
                padding: 20px;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .filters-card {
                padding: 20px;
            }

            .filter-group {
                min-width: 100%;
            }

            .filters-form {
                flex-direction: column;
            }

            .btn-filter {
                width: 100%;
            }

            th, td {
                padding: 12px;
                font-size: 12px;
            }

            .header-actions {
                width: 100%;
            }

            .btn {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <div class="header-top">
                <div>
                    <h1>🛡️ Painel de Moderação</h1>
                    <p>Gerenciamento de tickets de suporte</p>
                </div>

                <div class="header-actions">
                    <a href="{{ route('suporte.moderacao.dashboard') }}" class="btn btn-secondary">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>

            <!-- Search Section -->
            <div class="search-section">
                <form action="{{ route('suporte.moderacao.index') }}" method="GET">
                    <div class="search-wrapper">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" 
                            name="busca" 
                            value="{{ request('busca') }}"
                            placeholder="Buscar por #número, assunto, usuário ou use filtros como categoria:duvida"
                            class="search-input">
                    </div>
                    <div class="search-hint">
                        💡 Dicas: Use <strong>categoria:duvida</strong>, <strong>status:novo</strong>, <strong>prioridade:urgente</strong> ou <strong>usuario:nome</strong>
                    </div>
                    
                    <div class="quick-filters">
                        <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:duvida'; this.form.submit();" class="filter-chip blue">
                            💭 Dúvidas
                        </button>
                        <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:problema_tecnico'; this.form.submit();" class="filter-chip orange">
                            ⚙️ Problemas
                        </button>
                        <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:denuncia'; this.form.submit();" class="filter-chip red">
                            ⚠️ Denúncias
                        </button>
                        <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:sugestao'; this.form.submit();" class="filter-chip green">
                            💡 Sugestões
                        </button>
                    </div>
                </form>
            </div>
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

        <!-- Stats -->
        <div class="stats-grid">
            <a href="{{ route('suporte.moderacao.index', ['filtro' => 'todos']) }}" class="stat-card">
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </a>

            <a href="{{ route('suporte.moderacao.index', ['filtro' => 'novos']) }}" class="stat-card blue">
                <div class="stat-label">Novos</div>
                <div class="stat-value">{{ $stats['novos'] }}</div>
            </a>

            <a href="{{ route('suporte.moderacao.index', ['filtro' => 'meus']) }}" class="stat-card purple">
                <div class="stat-label">Meus</div>
                <div class="stat-value">{{ $stats['meus'] }}</div>
            </a>

            <a href="{{ route('suporte.moderacao.index', ['filtro' => 'denuncias']) }}" class="stat-card red">
                <div class="stat-label">Denúncias</div>
                <div class="stat-value">{{ $stats['denuncias'] }}</div>
            </a>

            <a href="{{ route('suporte.moderacao.index', ['filtro' => 'pendentes']) }}" class="stat-card yellow">
                <div class="stat-label">Pendentes</div>
                <div class="stat-value">{{ $stats['pendentes'] }}</div>
            </a>

            <a href="{{ route('suporte.moderacao.index', ['filtro' => 'spam']) }}" class="stat-card gray">
                <div class="stat-label">Spam</div>
                <div class="stat-value">{{ $stats['spam'] }}</div>
            </a>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <form method="GET" action="{{ route('suporte.moderacao.index') }}" class="filters-form">
                <div class="filter-group">
                    <label for="filtro" class="filter-label">Filtro</label>
                    <select name="filtro" id="filtro" class="filter-select">
                        <option value="todos" {{ $filtro == 'todos' ? 'selected' : '' }}>Todos</option>
                        <option value="novos" {{ $filtro == 'novos' ? 'selected' : '' }}>Novos</option>
                        <option value="meus" {{ $filtro == 'meus' ? 'selected' : '' }}>Meus Tickets</option>
                        <option value="denuncias" {{ $filtro == 'denuncias' ? 'selected' : '' }}>Denúncias</option>
                        <option value="pendentes" {{ $filtro == 'pendentes' ? 'selected' : '' }}>Pendentes</option>
                        <option value="resolvidos" {{ $filtro == 'resolvidos' ? 'selected' : '' }}>Resolvidos</option>
                        <option value="fechados" {{ $filtro == 'fechados' ? 'selected' : '' }}>Fechados</option>
                        <option value="spam" {{ $filtro == 'spam' ? 'selected' : '' }}>Spam</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="prioridade" class="filter-label">Prioridade</label>
                    <select name="prioridade" id="prioridade" class="filter-select">
                        <option value="">Todas</option>
                        <option value="baixa" {{ request('prioridade') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                        <option value="normal" {{ request('prioridade') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="alta" {{ request('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="urgente" {{ request('prioridade') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>

                <div class="filter-group" style="min-width: auto;">
                    <label class="filter-label" style="opacity: 0;">Filtrar</label>
                    <button type="submit" class="btn-filter">🔍 Filtrar</button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Usuário</th>
                            <th>Categoria</th>
                            <th>Status</th>
                            <th>Prioridade</th>
                            <th>Atribuído</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <!-- Ticket -->
                                <td>
                                    <div class="ticket-info">
                                        <a href="{{ route('suporte.show', $ticket->id) }}" class="ticket-number">
                                            #{{ $ticket->numero_ticket }}
                                        </a>
                                        <div class="ticket-subject">{{ $ticket->assunto }}</div>
                                    </div>
                                </td>

                                <!-- Usuário -->
                                <td>
    <div class="user-info">
        @if($ticket->usuario->avatar_url)
            <img src="{{ str_starts_with($ticket->usuario->avatar_url, 'http') ? $ticket->usuario->avatar_url : Storage::url($ticket->usuario->avatar_url) }}" 
                alt="{{ $ticket->usuario->username }}" 
                class="user-avatar"
                onerror="this.src='{{ asset('images/default-avatar.png') }}'">
        @else
            <img src="{{ asset('images/default-avatar.png') }}" 
                alt="{{ $ticket->usuario->username }}" 
                class="user-avatar">
        @endif
        <span class="user-name">{{ $ticket->usuario->username }}</span>
    </div>
</td>

                                <!-- Categoria -->
                                <td>
                                    <span class="badge 
                                        @if($ticket->categoria === 'denuncia') cat-denuncia
                                        @elseif($ticket->categoria === 'problema_tecnico') cat-problema
                                        @elseif($ticket->categoria === 'duvida') cat-duvida
                                        @else cat-default
                                        @endif">
                                        {{ $ticket->getCategoriaLabel() }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td>
                                    <span class="badge 
                                        @if($ticket->status === 'novo') status-novo
                                        @elseif($ticket->status === 'em_analise') status-analise
                                        @elseif($ticket->status === 'resolvido' || $ticket->status === 'fechado') status-resolvido
                                        @elseif($ticket->status === 'spam') status-spam
                                        @else status-default
                                        @endif">
                                        {{ $ticket->getStatusLabel() }}
                                    </span>
                                </td>

                                <!-- Prioridade -->
                                <td>
                                    <span class="badge 
                                        @if($ticket->prioridade === 'urgente') priority-urgente
                                        @elseif($ticket->prioridade === 'alta') priority-alta
                                        @elseif($ticket->prioridade === 'normal') priority-normal
                                        @else priority-baixa
                                        @endif">
                                        {{ $ticket->getPrioridadeLabel() }}
                                    </span>
                                </td>

                                <!-- Atribuído -->
                                <td>
                                    @if($ticket->atribuidoA)
                                        <span class="assigned-to">{{ $ticket->atribuidoA->username }}</span>
                                    @else
                                        <span class="not-assigned">Não atribuído</span>
                                    @endif
                                </td>

                                <!-- Data -->
                                <td>
                                    <span class="date-text">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                                </td>

                                <!-- Ações -->
                                <td>
                                    <a href="{{ route('suporte.show', $ticket->id) }}" class="btn-view">Ver →</a>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="8">
                                    <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 16px; color: #cbd5e0;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div>Nenhum ticket encontrado</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tickets->hasPages())
                <div class="pagination-container">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Atalho de teclado: Ctrl+K para focar na busca
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="busca"]');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });
    </script>
</body>
</html>