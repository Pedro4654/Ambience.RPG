<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Painel de Moderação - Ambience RPG</title>
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
            --gradient-mid: #034935ff;
            --gradient-end: #22553dff;
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
            max-width: 1600px;
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

        /* Header */
        .page-header {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            margin-bottom: 30px;
            animation: slideDown 0.5s ease;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            font-family: Montserrat, sans-serif;
            font-size: 32px;
            color: #fff;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
        }

        .page-header p {
            color: var(--muted);
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
            border: 2px solid rgba(34, 197, 94, 0.2);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: rgba(10, 15, 20, 0.4);
            color: var(--text-on-primary);
            font-family: Inter, sans-serif;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--muted);
        }

        .search-hint {
            margin-top: 8px;
            font-size: 12px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .search-hint strong {
            color: var(--accent);
        }

        .quick-filters {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .filter-chip {
            padding: 6px 14px;
            background: rgba(34, 197, 94, 0.1);
            border: 2px solid rgba(34, 197, 94, 0.2);
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--muted);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .filter-chip:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-2px);
            background: rgba(34, 197, 94, 0.2);
        }

        .filter-chip.blue:hover {
            border-color: #3b82f6;
            color: #60a5fa;
            background: rgba(59, 130, 246, 0.2);
        }

        .filter-chip.orange:hover {
            border-color: #f97316;
            color: #fb923c;
            background: rgba(249, 115, 22, 0.2);
        }

        .filter-chip.red:hover {
            border-color: #ef4444;
            color: #f87171;
            background: rgba(239, 68, 68, 0.2);
        }

        .filter-chip.green:hover {
            border-color: #10b981;
            color: #34d399;
            background: rgba(16, 185, 129, 0.2);
        }

        /* Alerts */
        .alert {
            padding: 20px 24px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideIn 0.3s ease-out;
            border: 1px solid;
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
            background: rgba(34, 197, 94, 0.15);
            border-color: var(--accent);
            color: #fff;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border-color: #ef4444;
            color: #fff;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            padding: 20px 24px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
            border: 1px solid rgba(34, 197, 94, 0.1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
            animation-fill-mode: both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stat-card:nth-child(4) { animation-delay: 0.2s; }
        .stat-card:nth-child(5) { animation-delay: 0.25s; }
        .stat-card:nth-child(6) { animation-delay: 0.3s; }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.05), transparent 70%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4);
            border-color: rgba(34, 197, 94, 0.3);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card.blue {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(37, 99, 235, 0.1));
            border-left: 4px solid #3b82f6;
        }

        .stat-card.purple {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.15), rgba(147, 51, 234, 0.1));
            border-left: 4px solid #a855f7;
        }

        .stat-card.red {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.1));
            border-left: 4px solid #ef4444;
        }

        .stat-card.yellow {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(217, 119, 6, 0.1));
            border-left: 4px solid #f59e0b;
        }

        .stat-card.gray {
            background: linear-gradient(135deg, rgba(139, 155, 168, 0.15), rgba(107, 114, 128, 0.1));
            border-left: 4px solid #6b7280;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
        }

        /* Filters Card */
        .filters-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            border: 1px solid rgba(34, 197, 94, 0.2);
            animation: fadeInUp 0.5s ease 0.35s both;
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
            color: var(--text-on-primary);
            margin-bottom: 8px;
        }

        .filter-input, .filter-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(34, 197, 94, 0.2);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
            background: rgba(10, 15, 20, 0.4);
            color: var(--text-on-primary);
        }

        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        .filter-select option {
            background: var(--card);
            color: var(--text-on-primary);
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
            background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
            color: var(--hero-green);
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
            color: var(--hero-green);
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        .btn-filter {
            padding: 12px 32px;
            background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
            color: var(--hero-green);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        /* Table Card */
        .table-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            border: 1px solid rgba(34, 197, 94, 0.2);
            animation: fadeInUp 0.5s ease 0.4s both;
        }

        .table-container {
            overflow-x: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: rgba(34, 197, 94, 0.05);
            border-bottom: 2px solid rgba(34, 197, 94, 0.1);
        }

        th {
            padding: 18px 24px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid rgba(34, 197, 94, 0.05);
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: rgba(34, 197, 94, 0.05);
            transform: translateX(3px);
        }

        td {
            padding: 24px;
            font-size: 14px;
            color: var(--text-on-primary);
        }

        .ticket-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .ticket-number {
            font-size: 15px;
            font-weight: 700;
            color: var(--accent);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .ticket-number:hover {
            color: var(--accent-light);
        }

        .ticket-subject {
            font-size: 13px;
            color: var(--muted);
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid rgba(34, 197, 94, 0.3);
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
            color: #fff;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Category Badges */
        .badge.cat-denuncia { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .badge.cat-problema { background: rgba(249, 115, 22, 0.2); color: #f97316; }
        .badge.cat-duvida { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
        .badge.cat-default { background: rgba(139, 155, 168, 0.2); color: var(--muted); }

        /* Status Badges */
        .badge.status-novo { background: #3bf66a33; color: #00ff33ff; }
        .badge.status-analise { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
        .badge.status-resolvido, .badge.status-fechado { background: rgba(34, 197, 94, 0.2); color: var(--accent); }
        .badge.status-spam { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .badge.status-default { background: rgba(139, 155, 168, 0.2); color: var(--muted); }

        /* Priority Badges */
        .badge.priority-urgente { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        .badge.priority-alta { background: rgba(251, 146, 60, 0.2); color: #fb923c; }
        .badge.priority-normal { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
        .badge.priority-baixa { background: rgba(156, 163, 175, 0.2); color: #9ca3af; }

        .assigned-to {
            font-size: 13px;
            color: var(--text-on-primary);
            font-weight: 500;
        }

        .not-assigned {
            font-size: 13px;
            color: var(--muted);
            font-style: italic;
        }

        .date-text {
            font-size: 13px;
            color: var(--muted);
        }

        .btn-view {
            color: var(--accent);
            font-weight: 600;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-view:hover {
            color: var(--accent-light);
            gap: 8px;
        }

        .empty-row {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-row td {
            color: var(--muted);
            font-size: 15px;
        }

        .empty-row svg {
            opacity: 0.4;
            margin-bottom: 16px;
        }

        /* Pagination */
        .pagination-container {
            padding: 28px 32px;
            border-top: 1px solid rgba(34, 197, 94, 0.1);
            background: rgba(34, 197, 94, 0.02);
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

            .quick-filters {
                gap: 6px;
            }

            .filter-chip {
                font-size: 12px;
                padding: 5px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">

    <a href="{{ auth()->user()->isStaff() ? '/' : '/' }}" class="back-link">


    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Voltar
</a>

        <!-- Header -->
        <div class="page-header">
            <div class="header-top">
                <div>
                    <h1> <img src="/images/ICONS/badge.png" alt="Adicionar comentário" style="width:34px; height:34px; margin-right:6px;">Painel de Moderação</h1>
                    <p>Gerenciamento de tickets de suporte</p>
                </div>

                <div class="header-actions">
                    <a href="{{ route('suporte.moderacao.dashboard') }}" class="btn btn-secondary">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Dashboard T
                    </a>
                    <a href="{{ route('moderacao.usuarios.dashboard') }}" class="btn btn-secondary">
                       <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg> Dashboard U
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
                     Dicas: Use <strong>categoria:duvida</strong>, <strong>status:novo</strong>, <strong>prioridade:urgente</strong> ou <strong>usuario:nome</strong>
                    </div>
                    
                    <div class="quick-filters">
                        <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:duvida'; this.form.submit();" class="filter-chip blue">
                             Dúvidas
                        </button>
                        <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:problema_tecnico'; this.form.submit();" class="filter-chip orange">
                             Problemas
                        </button>
                        <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:denuncia'; this.form.submit();" class="filter-chip red">
                             Denúncias
                        </button>
                        <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:sugestao'; this.form.submit();" class="filter-chip green">
                             Sugestões
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
                    <button type="submit" class="btn-filter"> Filtrar</button>
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