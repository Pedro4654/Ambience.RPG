<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gerenciar Usuários - Moderação</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-top h1 {
            font-size: 32px;
            color: #1a202c;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
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
            padding: 14px 48px;
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
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.12);
        }

        .stat-card.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
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

        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #1a202c;
        }

        .user-email {
            font-size: 12px;
            color: #6b7280;
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

        .badge.status-ativo { background: #d1fae5; color: #065f46; }
        .badge.status-inativo { background: #fee2e2; color: #991b1b; }
        .badge.nivel-admin { background: #fef3c7; color: #92400e; }
        .badge.nivel-moderador { background: #dbeafe; color: #1e40af; }
        .badge.nivel-usuario { background: #e5e7eb; color: #374151; }
        .badge.warning { background: #fef3c7; color: #92400e; }
        .badge.ban { background: #fee2e2; color: #991b1b; }
        .badge.ip-ban { background: #fecaca; color: #7f1d1d; }

        .punishments-cell {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .actions-cell {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-view {
            background: #667eea;
            color: white;
        }

        .btn-view:hover {
            background: #764ba2;
        }

        .btn-edit {
            background: #f59e0b;
            color: white;
        }

        .btn-edit:hover {
            background: #d97706;
        }

        .empty-row {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-row td {
            color: #9ca3af;
            font-size: 15px;
        }

        .pagination-container {
            padding: 25px 30px;
            border-top: 2px solid #f3f4f6;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .page-header {
                padding: 20px;
            }

            .header-top h1 {
                font-size: 24px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <div class="header-top">
                <h1>👥 Gerenciar Usuários</h1>
                <div class="header-actions">
                    <a href="{{ route('moderacao.usuarios.dashboard') }}" class="btn btn-secondary">
                        Dashboard
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