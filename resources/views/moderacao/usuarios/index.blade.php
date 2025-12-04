<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gerenciar Usuários - Moderação</title>
<style>
    /* ------------------------------------------------------------------
       VARIÁVEIS & FUNDAMENTOS (Tema Ambience RPG)
       ------------------------------------------------------------------ */
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap');

    :root {
        --bg-dark: #0a0f14;
        --card: #1f2a33;
        --muted: #8b9ba8;
        --accent: #22c55e;
        --accent-light: #16a34a;
        --text-on-primary: #e6eef6;
        --header-bg: rgba(10, 15, 20, 0.75);
        --btn-gradient-start: #22c55e;
        --btn-gradient-end: #16a34a;
        --border-color: rgba(34, 197, 94, 0.2);
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

    /* ------------------------------------------------------------------
       HEADER & SEARCH
       ------------------------------------------------------------------ */
    .page-header {
        background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(12px);
        margin-bottom: 30px;
        border: 1px solid var(--border-color);
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-top h1 {
        font-family: Montserrat, sans-serif;
        font-size: 32px;
        color: #fff;
        font-weight: 900;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    /* Botões Gerais */
    .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-secondary {
        background: rgba(34, 197, 94, 0.1);
        color: var(--accent);
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .btn-secondary:hover {
        background: rgba(34, 197, 94, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
        border-color: var(--accent);
    }

    /* Search Input */
    .search-section { margin-top: 20px; }
    .search-wrapper { position: relative; }

    .search-input {
        width: 100%;
        padding: 14px 48px;
        border: 2px solid var(--border-color);
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

    /* ------------------------------------------------------------------
       STATS GRID (Filtros)
       ------------------------------------------------------------------ */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        border-color: var(--accent);
        background: rgba(34, 197, 94, 0.05);
    }

    /* Card Ativo (Filtro selecionado) */
    .stat-card.active {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(22, 163, 74, 0.1) 100%);
        border-color: var(--accent);
        box-shadow: 0 0 15px rgba(34, 197, 94, 0.15);
    }

    .stat-card.active .stat-label { color: var(--accent); }
    .stat-card.active .stat-value { color: #fff; }

    .stat-label {
        font-size: 12px;
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
        line-height: 1;
    }

    /* ------------------------------------------------------------------
       TABLE CARD
       ------------------------------------------------------------------ */
    .table-card {
        background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        border: 1px solid var(--border-color);
        animation: fadeInUp 0.5s ease 0.2s both;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .table-container { overflow-x: auto; }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: rgba(34, 197, 94, 0.08);
        border-bottom: 1px solid var(--border-color);
    }

    th {
        padding: 18px 24px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    tbody tr {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transition: background 0.2s ease;
    }

    tbody tr:last-child { border-bottom: none; }

    tbody tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    td {
        padding: 20px 24px;
        font-size: 14px;
        color: var(--text-on-primary);
        vertical-align: middle;
    }

    /* Célula de Usuário */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid rgba(34, 197, 94, 0.3);
    }

    .user-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .user-name {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
    }

    .user-email {
        font-size: 12px;
        color: var(--muted);
    }

    /* ------------------------------------------------------------------
       BADGES & ACTIONS
       ------------------------------------------------------------------ */
    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: 1px solid transparent;
    }

    /* Status Cores */
    .badge.status-ativo { background: rgba(34, 197, 94, 0.15); color: #4ade80; border-color: rgba(34, 197, 94, 0.2); }
    .badge.status-inativo { background: rgba(239, 68, 68, 0.15); color: #f87171; border-color: rgba(239, 68, 68, 0.2); }
    
    /* Níveis Cores */
    .badge.nivel-admin { background: rgba(234, 179, 8, 0.15); color: #facc15; border-color: rgba(234, 179, 8, 0.2); }
    .badge.nivel-moderador { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border-color: rgba(59, 130, 246, 0.2); }
    .badge.nivel-usuario { background: rgba(148, 163, 184, 0.15); color: #cbd5e1; border-color: rgba(148, 163, 184, 0.2); }

    /* Punições Cores */
    .badge.warning { background: rgba(249, 115, 22, 0.15); color: #fb923c; border-color: rgba(249, 115, 22, 0.2); }
    .badge.ban { background: rgba(239, 68, 68, 0.15); color: #f87171; border-color: rgba(239, 68, 68, 0.2); }
    .badge.ip-ban { background: rgba(153, 27, 27, 0.3); color: #fca5a5; border-color: rgba(153, 27, 27, 0.4); }

    .punishments-cell {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: flex-start;
    }

    /* Ações na Tabela */
    .actions-cell {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-view {
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .btn-view:hover {
        background: rgba(59, 130, 246, 0.3);
        transform: translateY(-2px);
    }

    .btn-edit {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .btn-edit:hover {
        background: rgba(245, 158, 11, 0.3);
        transform: translateY(-2px);
    }

    /* Empty State & Pagination */
    .empty-row {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-row td {
        color: var(--muted);
        font-size: 15px;
    }

    .empty-row svg { opacity: 0.5; color: var(--muted) !important; }

    .pagination-container {
        padding: 20px 24px;
        border-top: 1px solid var(--border-color);
        background: rgba(0,0,0,0.1);
    }
    
    /* Estilização básica para paginação padrão do Laravel (assumindo Tailwind/Bootstrap) */
    .pagination-container nav {
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        body { padding: 20px 15px; }
        .page-header { padding: 20px; }
        .header-top h1 { font-size: 24px; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        th, td { padding: 12px 16px; }
        .user-cell { gap: 10px; }
        .user-avatar { width: 32px; height: 32px; }
    }
</style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <div class="header-top">
                <h1> Gerenciar Usuários</h1>
                <div class="header-actions">
                    <a href="{{ route('moderacao.usuarios.dashboard') }}" class="btn btn-secondary">
                       <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg> Dashboard U
                    </a>
                </div>
            </div>

            <!-- Search -->
            <div class="search-section">
                <form action="{{ route('moderacao.usuarios.index') }}" method="GET">
                    <div class="search-wrapper">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" 
                            name="busca" 
                            value="{{ request('busca') }}"
                            placeholder="Buscar por username, email ou nickname..."
                            class="search-input">
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <a href="{{ route('moderacao.usuarios.index', ['filtro' => 'todos']) }}" 
               class="stat-card {{ $filtro == 'todos' ? 'active' : '' }}">
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </a>

            <a href="{{ route('moderacao.usuarios.index', ['filtro' => 'ativos']) }}" 
               class="stat-card {{ $filtro == 'ativos' ? 'active' : '' }}">
                <div class="stat-label">Ativos</div>
                <div class="stat-value">{{ $stats['total'] - $stats['punidos'] }}</div>
            </a>

            <a href="{{ route('moderacao.usuarios.index', ['filtro' => 'punidos']) }}" 
               class="stat-card {{ $filtro == 'punidos' ? 'active' : '' }}">
                <div class="stat-label">Punidos</div>
                <div class="stat-value">{{ $stats['punidos'] }}</div>
            </a>

            <a href="{{ route('moderacao.usuarios.index', ['filtro' => 'warning']) }}" 
               class="stat-card {{ $filtro == 'warning' ? 'active' : '' }}">
                <div class="stat-label">Warnings</div>
                <div class="stat-value">{{ $stats['warning'] }}</div>
            </a>

            <a href="{{ route('moderacao.usuarios.index', ['filtro' => 'ban']) }}" 
               class="stat-card {{ $filtro == 'ban' ? 'active' : '' }}">
                <div class="stat-label">Bans</div>
                <div class="stat-value">{{ $stats['ban'] }}</div>
            </a>

            <a href="{{ route('moderacao.usuarios.index', ['filtro' => 'staff']) }}" 
               class="stat-card {{ $filtro == 'staff' ? 'active' : '' }}">
                <div class="stat-label">Staff</div>
                <div class="stat-value">{{ $stats['staff'] }}</div>
            </a>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Nível</th>
                            <th>Status</th>
                            <th>Punições</th>
                            <th>Tickets</th>
                            <th>Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <!-- Usuário -->
                                <td>
                                    <div class="user-cell">
                                        @if($usuario->avatar_url)
                                            <img src="{{ $usuario->avatar_url }}" alt="{{ $usuario->username }}" class="user-avatar">
                                        @else
                                            <div class="user-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                                {{ strtoupper(substr($usuario->username, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="user-info">
                                            <div class="user-name">{{ $usuario->username }}</div>
                                            <div class="user-email">{{ $usuario->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Nível -->
                                <td>
                                    <span class="badge nivel-{{ $usuario->nivel_usuario }}">
                                        {{ $usuario->nivel_usuario === 'admin' ? 'Admin' : ($usuario->nivel_usuario === 'moderador' ? 'Mod' : 'Usuário') }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td>
                                    <span class="badge status-{{ $usuario->status }}">
                                        {{ ucfirst($usuario->status) }}
                                    </span>
                                </td>

                                <!-- Punições -->
                                <td>
                                    <div class="punishments-cell">
                                        @if($usuario->warning_ativo)
                                            <span class="badge warning">Warning</span>
                                        @endif
                                        @if($usuario->ban_tipo)
                                            <span class="badge ban">{{ $usuario->ban_tipo === 'temporario' ? 'Ban Temp' : 'Perma-Ban' }}</span>
                                        @endif
                                        @if($usuario->ip_ban_ativo)
                                            <span class="badge ip-ban">IP Ban</span>
                                        @endif
                                        @if(!$usuario->warning_ativo && !$usuario->ban_tipo && !$usuario->ip_ban_ativo)
                                            <span style="color: #9ca3af; font-size: 12px;">Nenhuma</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Tickets -->
                                <td>
                                    <strong>{{ $usuario->tickets_count }}</strong> criados
                                    @if($usuario->denuncias_recebidas_count > 0)
                                        <br><span style="color: #ef4444; font-size: 12px;">{{ $usuario->denuncias_recebidas_count }} denúncias</span>
                                    @endif
                                </td>

                                <!-- Cadastro -->
                                <td>
                                    {{ $usuario->data_criacao->format('d/m/Y') }}
                                </td>

                                <!-- Ações -->
                                <td>
                                    <div class="actions-cell">
                                        <a href="{{ route('moderacao.usuarios.show', $usuario->id) }}" class="btn-action btn-view">
                                            Ver
                                        </a>
                                        <a href="{{ route('moderacao.usuarios.edit', $usuario->id) }}" class="btn-action btn-edit">
                                            Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="7">
                                    <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 16px; color: #cbd5e0;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <div>Nenhum usuário encontrado</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($usuarios->hasPages())
                <div class="pagination-container">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>