<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Moderação - Usuários</title>
<style>
    /* ------------------------------------------------------------------
       VARIÁVEIS & FUNDAMENTOS (Copiados do Dashboard Original)
       ------------------------------------------------------------------ */
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap');

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

    /* ------------------------------------------------------------------
       HEADER & BOTÕES
       ------------------------------------------------------------------ */
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
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .header-content h1 {
        font-family: Montserrat, sans-serif;
        font-size: 32px;
        color: #fff;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 900;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    /* Botões padronizados com o estilo do original */
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
        white-space: nowrap;
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
        background: rgba(34, 197, 94, 0.1);
        color: var(--accent);
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .btn-secondary:hover {
        background: rgba(34, 197, 94, 0.2);
        transform: translateY(-2px);
        border-color: var(--accent);
    }

    /* ------------------------------------------------------------------
       STATS GRID (Cards do Topo)
       ------------------------------------------------------------------ */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 22px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease;
        animation-fill-mode: both;
        border: 1px solid rgba(34, 197, 94, 0.1);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
        border-color: rgba(34, 197, 94, 0.3);
    }

    /* Cores das bordas laterais */
    .stat-card.border-blue { border-left: 4px solid #3b82f6; }
    .stat-card.border-yellow, .stat-card.border-orange { border-left: 4px solid #f97316; }
    .stat-card.border-red { border-left: 4px solid #ef4444; }
    .stat-card.border-purple { border-left: 4px solid #a855f7; }
    .stat-card.border-green { border-left: 4px solid #22c55e; }
    .stat-card.border-gray { border-left: 4px solid #6b7280; }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .stat-info h3 {
        font-size: 14px;
        font-weight: 600;
        color: var(--muted);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Cores dos Ícones */
    .stat-icon.blue { background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.1)); color: #3b82f6; }
    .stat-icon.yellow, .stat-icon.orange { background: linear-gradient(135deg, rgba(249, 115, 22, 0.2), rgba(234, 88, 12, 0.1)); color: #f97316; }
    .stat-icon.red { background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.1)); color: #ef4444; }
    .stat-icon.purple { background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(147, 51, 234, 0.1)); color: #a855f7; }
    .stat-icon.green { background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.1)); color: #22c55e; }
    .stat-icon.gray { background: linear-gradient(135deg, rgba(107, 114, 128, 0.2), rgba(75, 85, 99, 0.1)); color: #9ca3af; }

    .stat-icon svg { width: 28px; height: 28px; }

    /* ------------------------------------------------------------------
       LAYOUT DE CONTEÚDO & CARDS
       ------------------------------------------------------------------ */
    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }

    .card {
        background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.5s ease;
        animation-fill-mode: both;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .card:nth-child(1) { animation-delay: 0.2s; }
    .card:nth-child(2) { animation-delay: 0.3s; }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .card-header h2 {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-link {
        color: var(--accent);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .card-link:hover { color: var(--accent-light); }

    /* ------------------------------------------------------------------
       LISTAGENS (Punições, Usuários, Staff)
       ------------------------------------------------------------------ */
    /* Punições e Usuários (Estilo Activity Item) */
    .punicao-item, .usuario-item, .denuncia-item {
        padding: 20px;
        background: rgba(34, 197, 94, 0.05);
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        border: 1px solid rgba(34, 197, 94, 0.1);
        margin-bottom: 16px;
    }

    .punicao-item:hover, .usuario-item:hover, .denuncia-item:hover {
        background: rgba(34, 197, 94, 0.1);
        transform: translateX(5px);
        border-color: rgba(34, 197, 94, 0.2);
    }

    /* Denúncias Específicas */
    .denuncia-item {
        flex-direction: column;
        gap: 10px;
        border-left: 3px solid #ef4444; /* Borda vermelha para destaque */
        background: linear-gradient(90deg, rgba(239, 68, 68, 0.05) 0%, rgba(34, 197, 94, 0.02) 100%);
    }
    
    .denuncia-header {
        display: flex;
        justify-content: space-between;
        width: 100%;
        align-items: center;
    }

    /* Moderadores (Estilo Staff Item) */
    .moderador-item {
        padding: 20px;
        background: rgba(34, 197, 94, 0.05);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        border: 1px solid rgba(34, 197, 94, 0.1);
        margin-bottom: 12px;
    }

    /* Avatares */
    .punicao-avatar, .usuario-avatar, .moderador-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(34, 197, 94, 0.3);
        flex-shrink: 0;
    }

    .moderador-avatar {
        width: 48px;
        height: 48px;
        border: 3px solid var(--accent);
    }

    /* Informações de Texto */
    .punicao-info, .usuario-info, .moderador-info { flex: 1; }

    .punicao-username, .usuario-nome, .moderador-nome {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }

    .punicao-tipo, .usuario-data, .moderador-nivel {
        font-size: 12px;
        color: var(--muted);
    }
    
    .punicao-details {
        margin-top: 8px;
        font-size: 13px;
        color: #fff;
        opacity: 0.9;
    }

    .punicao-moderador {
        margin-top: 6px;
        font-size: 11px;
        color: var(--muted);
        font-style: italic;
    }

    /* Stats do Moderador */
    .moderador-stats { text-align: right; }
    .moderador-count { font-size: 24px; font-weight: 700; color: var(--accent); }
    .moderador-label { font-size: 11px; color: var(--muted); }

    /* Denúncias Textos */
    .denuncia-numero { font-weight: 700; color: var(--accent); }
    .denuncia-titulo { font-weight: 600; color: #fff; }
    .denuncia-envolvidos { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
    .denuncia-link { color: var(--accent); font-size: 13px; font-weight: 600; text-decoration: none; }

    /* Badges */
    .badge {
        padding: 4px 10px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge.yellow { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
    .badge.red { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .badge.gray { background: rgba(139, 155, 168, 0.2); color: var(--muted); }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--muted);
    }

    .empty-state svg {
        width: 60px;
        height: 60px;
        opacity: 0.4;
        margin-bottom: 16px;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsividade */
    @media (max-width: 1200px) {
        .content-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        body { padding: 20px 15px; }
        .page-header { padding: 20px; }
        .stats-grid { grid-template-columns: 1fr; }
        .header-content h1 { font-size: 24px; }
    }
</style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <h1>
                    Moderação de Usuários
                </h1>
                <div class="header-actions">
                    <a href="{{ route('moderacao.usuarios.index') }}" class="btn btn-primary">
                        Ver Todos os Usuários
                    </a>
                    <a href="{{ route('suporte.moderacao.dashboard') }}" class="btn btn-secondary">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg> Dashboard de Tickets
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card border-blue">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Total de Usuários</h3>
                        <div class="stat-value">{{ $stats['total_usuarios'] }}</div>
                    </div>
                    <div class="stat-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card border-yellow">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Warnings Ativos</h3>
                        <div class="stat-value">{{ $stats['warnings_ativos'] }}</div>
                    </div>
                    <div class="stat-icon yellow">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card border-red">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Usuários Banidos</h3>
                        <div class="stat-value">{{ $stats['bans_temporarios'] + $stats['perma_bans'] }}</div>
                    </div>
                    <div class="stat-icon red">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card border-purple">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>IP Bans</h3>
                        <div class="stat-value">{{ $stats['ip_bans'] }}</div>
                    </div>
                    <div class="stat-icon purple">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card border-gray">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Contas Deletadas</h3>
                        <div class="stat-value">{{ $stats['contas_deletadas'] }}</div>
                    </div>
                    <div class="stat-icon gray">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card border-green">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Novos Hoje</h3>
                        <div class="stat-value">{{ $stats['usuarios_cadastrados_hoje'] }}</div>
                    </div>
                    <div class="stat-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Punições Recentes -->
            <div class="card">
                <div class="card-header">
                    <h2> Punições Recentes</h2>
                    <a href="{{ route('moderacao.usuarios.index', ['filtro' => 'punidos']) }}" class="card-link">Ver todas →</a>
                </div>

                @forelse($punicoesRecentes as $punicao)
                    <div class="punicao-item">
                        <div class="punicao-header">
                            @if($punicao->avatar_url)
                                <img src="{{ $punicao->avatar_url }}" alt="{{ $punicao->username }}" class="punicao-avatar">
                            @else
                                <div class="punicao-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                    {{ strtoupper(substr($punicao->username, 0, 1)) }}
                                </div>
                            @endif
                            <div class="punicao-info">
                                <div class="punicao-username">{{ $punicao->username }}</div>
                                <div class="punicao-tipo">
                                    @if($punicao->warning_ativo)
                                        <span class="badge yellow">Warning</span>
                                    @elseif($punicao->ban_tipo === 'temporario')
                                        <span class="badge red">Ban Temporário</span>
                                    @elseif($punicao->ban_tipo === 'permanente')
                                        <span class="badge red">Ban Permanente</span>
                                    @elseif($punicao->ip_ban_ativo)
                                        <span class="badge red">IP Ban</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="punicao-details">
                            @if($punicao->warning_ativo && $punicao->warning_motivo)
                                <strong>Motivo:</strong> {{ Str::limit($punicao->warning_motivo, 100) }}
                            @elseif($punicao->ban_motivo)
                                <strong>Motivo:</strong> {{ Str::limit($punicao->ban_motivo, 100) }}
                            @elseif($punicao->ip_ban_motivo)
                                <strong>Motivo:</strong> {{ Str::limit($punicao->ip_ban_motivo, 100) }}
                            @endif
                        </div>

                        <div class="punicao-moderador">
                            Aplicado por: 
                            @if($punicao->warningAplicadoPor)
                                {{ $punicao->warningAplicadoPor->username }}
                            @elseif($punicao->banAplicadoPor)
                                {{ $punicao->banAplicadoPor->username }}
                            @elseif($punicao->ipBanAplicadoPor)
                                {{ $punicao->ipBanAplicadoPor->username }}
                            @endif
                            • {{ $punicao->warning_data ? $punicao->warning_data->diffForHumans() : ($punicao->ban_inicio ? $punicao->ban_inicio->diffForHumans() : '') }}
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Nenhuma punição recente</p>
                    </div>
                @endforelse
            </div>

            <!-- Denúncias Pendentes -->
            <div class="card">
                <div class="card-header">
                    <h2> Denúncias Pendentes</h2>
                    <a href="{{ route('suporte.moderacao.index', ['filtro' => 'denuncias']) }}" class="card-link">Ver todas →</a>
                </div>

                @forelse($denunciasPendentes as $denuncia)
                    <div class="denuncia-item">
                        <div class="denuncia-header">
                            <div class="denuncia-numero">#{{ $denuncia->numero_ticket }}</div>
                            <span class="badge red">{{ $denuncia->getStatusLabel() }}</span>
                        </div>

                        <div class="denuncia-titulo">{{ Str::limit($denuncia->assunto, 60) }}</div>

                        <div class="denuncia-envolvidos">
                            <strong>Denunciante:</strong> {{ $denuncia->usuario->username }}
                            @if($denuncia->usuarioDenunciado)
                                • <strong>Denunciado:</strong> {{ $denuncia->usuarioDenunciado->username }}
                            @endif
                        </div>

                        <a href="{{ route('suporte.show', $denuncia->id) }}" class="denuncia-link">
                            Ver detalhes →
                        </a>
                    </div>
                @empty
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Nenhuma denúncia pendente</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Segunda Linha de Cards -->
        <div class="content-grid">
            <!-- Moderadores Ativos -->
            <div class="card">
                <div class="card-header">
                    <h2> Moderadores Mais Ativos</h2>
                </div>

                @forelse($moderadoresAtivos as $moderador)
                    <div class="moderador-item">
                        @if($moderador->avatar_url)
                            <img src="{{ $moderador->avatar_url }}" alt="{{ $moderador->username }}" class="moderador-avatar">
                        @else
                            <div class="moderador-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                {{ strtoupper(substr($moderador->username, 0, 1)) }}
                            </div>
                        @endif
                        <div class="moderador-info">
                            <div class="moderador-nome">{{ $moderador->username }}</div>
                            <div class="moderador-nivel">{{ $moderador->nivel_usuario === 'admin' ? 'Administrador' : 'Moderador' }}</div>
                        </div>
                        <div class="moderador-stats">
                            <div class="moderador-count">{{ $moderador->total_warnings + $moderador->total_bans }}</div>
                            <div class="moderador-label">punições</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>Nenhum dado disponível</p>
                    </div>
                @endforelse
            </div>

            <!-- Usuários Recentes -->
            <div class="card">
                <div class="card-header">
                    <h2> Usuários Recentes</h2>
                    <a href="{{ route('moderacao.usuarios.index') }}" class="card-link">Ver todos →</a>
                </div>

                @forelse($usuariosRecentes as $usuario)
                    <a href="{{ route('moderacao.usuarios.show', $usuario->id) }}" class="usuario-item">
                        @if($usuario->avatar_url)
                            <img src="{{ $usuario->avatar_url }}" alt="{{ $usuario->username }}" class="usuario-avatar">
                        @else
                            <div class="usuario-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                {{ strtoupper(substr($usuario->username, 0, 1)) }}
                            </div>
                        @endif
                        <div class="usuario-info">
                            <div class="usuario-nome">{{ $usuario->username }}</div>
                            <div class="usuario-data">Cadastrado {{ $usuario->data_criacao->diffForHumans() }}</div>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <p>Nenhum usuário recente</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>