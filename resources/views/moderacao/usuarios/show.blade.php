<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $usuario->username }} - Moderação</title>
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
        --btn-gradient-start: #22c55e;
        --btn-gradient-end: #16a34a;
        --border-color: rgba(34, 197, 94, 0.2);
        
        /* Cores de Status/Ação */
        --warning: #f59e0b;
        --danger: #ef4444;
        --danger-dark: #991b1b;
        --success: #10b981;
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
        min-height: 100vh;
        padding: 40px 20px;
    }

    .container {
        max-width: 1600px;
        margin: 0 auto;
    }

    /* ------------------------------------------------------------------
       NAVEGAÇÃO & LAYOUT
       ------------------------------------------------------------------ */
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

    .grid-layout {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    .main-content {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* ------------------------------------------------------------------
       CARDS GERAIS
       ------------------------------------------------------------------ */
    .card, .sidebar-card {
        background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        padding: 32px;
        border: 1px solid var(--border-color);
        animation: fadeInUp 0.5s ease both;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ------------------------------------------------------------------
       PERFIL HEADER
       ------------------------------------------------------------------ */
    .profile-header {
        display: flex;
        gap: 24px;
        align-items: flex-start;
        margin-bottom: 32px;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--accent);
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.2);
    }

    .profile-info { flex: 1; }

    .profile-name {
        font-family: Montserrat, sans-serif;
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .profile-email {
        font-size: 16px;
        color: var(--muted);
        margin-bottom: 16px;
    }

    .profile-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* Badges unificados */
    .badge {
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: 1px solid transparent;
    }

    .badge.nivel-admin { background: rgba(234, 179, 8, 0.15); color: #facc15; border-color: rgba(234, 179, 8, 0.2); }
    .badge.nivel-moderador { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border-color: rgba(59, 130, 246, 0.2); }
    .badge.nivel-usuario { background: rgba(148, 163, 184, 0.15); color: #cbd5e1; border-color: rgba(148, 163, 184, 0.2); }
    
    .badge.status-ativo { background: rgba(34, 197, 94, 0.15); color: #4ade80; border-color: rgba(34, 197, 94, 0.2); }
    .badge.status-inativo { background: rgba(239, 68, 68, 0.15); color: #f87171; border-color: rgba(239, 68, 68, 0.2); }
    
    .badge.warning { background: rgba(249, 115, 22, 0.15); color: #fb923c; border-color: rgba(249, 115, 22, 0.2); }
    .badge.ban { background: rgba(239, 68, 68, 0.15); color: #f87171; border-color: rgba(239, 68, 68, 0.2); }
    .badge.ip-ban { background: rgba(153, 27, 27, 0.3); color: #fca5a5; border-color: rgba(153, 27, 27, 0.4); }

    /* Bio */
    .profile-bio {
        margin-top: 20px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        font-size: 15px;
        line-height: 1.6;
        color: var(--text-on-primary);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Stats Grid Mini */
    .stats-mini-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-top: 24px;
    }

    .stat-mini {
        padding: 16px;
        background: rgba(34, 197, 94, 0.05);
        border-radius: 12px;
        text-align: center;
        border: 1px solid rgba(34, 197, 94, 0.1);
        transition: all 0.3s;
    }

    .stat-mini:hover {
        background: rgba(34, 197, 94, 0.1);
        transform: translateY(-2px);
    }

    .stat-mini-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 4px;
    }

    .stat-mini-label {
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
        text-transform: uppercase;
    }

    /* ------------------------------------------------------------------
       HISTÓRICO & LISTAS
       ------------------------------------------------------------------ */
    .section-title {
        font-family: Montserrat, sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Histórico */
    .historico-item {
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 16px;
        border-left: 4px solid;
    }

    .historico-item.yellow { 
        border-color: var(--warning); 
        background: linear-gradient(90deg, rgba(245, 158, 11, 0.05) 0%, transparent 100%); 
    }
    .historico-item.red { 
        border-color: var(--danger); 
        background: linear-gradient(90deg, rgba(239, 68, 68, 0.05) 0%, transparent 100%); 
    }
    .historico-item.gray { 
        border-color: var(--muted); 
        background: linear-gradient(90deg, rgba(139, 155, 168, 0.05) 0%, transparent 100%); 
    }

    .historico-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .historico-tipo {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
    }

    .historico-motivo {
        font-size: 14px;
        color: #ccc;
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .historico-meta {
        font-size: 13px;
        color: var(--muted);
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    /* Tickets */
    .ticket-item {
        padding: 20px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        margin-bottom: 16px;
        text-decoration: none;
        color: inherit;
        display: block;
        transition: all 0.3s;
    }

    .ticket-item:hover {
        background: rgba(34, 197, 94, 0.05);
        border-color: var(--accent);
        transform: translateX(5px);
    }

    .ticket-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .ticket-numero {
        font-size: 16px;
        font-weight: 700;
        color: var(--accent);
    }

    .ticket-titulo {
        font-size: 15px;
        color: #fff;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .ticket-meta { font-size: 13px; color: var(--muted); }

    /* Badges de Tickets */
    .badge.ticket-novo { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
    .badge.ticket-analise { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
    .badge.ticket-resolvido { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
    .badge.ticket-fechado { background: rgba(148, 163, 184, 0.15); color: #cbd5e1; }
    .badge.ticket-denuncia { background: rgba(239, 68, 68, 0.15); color: #f87171; }

    /* Contas Associadas */
    .conta-associada {
        padding: 16px;
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 12px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s;
    }

    .conta-associada:hover {
        background: rgba(245, 158, 11, 0.15);
        border-color: rgba(245, 158, 11, 0.5);
    }

    .conta-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .conta-info { flex: 1; }
    .conta-username { font-size: 14px; font-weight: 600; color: #fff; }
    .conta-email { font-size: 12px; color: var(--muted); }

    /* ------------------------------------------------------------------
       SIDEBAR & ACTIONS
       ------------------------------------------------------------------ */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .sidebar-card h3 {
        font-family: Montserrat, sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 20px;
    }

    .info-list { display: flex; flex-direction: column; gap: 16px; }

    .info-item dt {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item dd { font-size: 15px; color: #fff; font-weight: 500; }

    /* Action Buttons */
    .btn-action {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-warning {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .btn-warning:hover { background: rgba(245, 158, 11, 0.25); border-color: #fbbf24; transform: translateY(-2px); }

    .btn-ban {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .btn-ban:hover { background: rgba(239, 68, 68, 0.25); border-color: #f87171; transform: translateY(-2px); }

    .btn-ip-ban {
        background: rgba(153, 27, 27, 0.2);
        color: #fca5a5;
        border: 1px solid rgba(153, 27, 27, 0.4);
    }
    .btn-ip-ban:hover { background: rgba(153, 27, 27, 0.3); border-color: #fca5a5; transform: translateY(-2px); }

    .btn-reactivate {
        background: rgba(34, 197, 94, 0.15);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    .btn-reactivate:hover { background: rgba(34, 197, 94, 0.25); border-color: #4ade80; transform: translateY(-2px); }

    .empty-state { text-align: center; padding: 40px; color: var(--muted); }

    /* ------------------------------------------------------------------
       MODAL DE PUNIÇÃO
       ------------------------------------------------------------------ */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(5px);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .modal.active { display: flex; animation: fadeIn 0.3s ease; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .modal-content {
        background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 32px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
    }

    .modal-title {
        font-family: Montserrat, sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 24px;
    }

    .form-group { margin-bottom: 20px; }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-on-primary);
        margin-bottom: 8px;
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 14px 16px;
        background: rgba(10, 15, 20, 0.6);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 15px;
        font-family: Inter, sans-serif;
        color: #fff;
        transition: all 0.3s ease;
    }

    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    .form-textarea { resize: vertical; min-height: 120px; }

    .modal-actions { display: flex; gap: 12px; margin-top: 30px; }

    .btn-cancel {
        flex: 1;
        padding: 14px;
        background: rgba(255, 255, 255, 0.05);
        color: var(--muted);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-cancel:hover { background: rgba(255, 255, 255, 0.1); color: #fff; }

    .btn-submit {
        flex: 1;
        padding: 14px;
        background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
        color: #052e16;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4); }

    /* Responsividade */
    @media (max-width: 1200px) {
        .grid-layout { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        body { padding: 20px 15px; }
        .card, .sidebar-card { padding: 24px 20px; }
        .profile-header { flex-direction: column; align-items: center; text-align: center; }
        .stats-mini-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('moderacao.usuarios.index') }}" class="back-link">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Voltar
        </a>

        <!-- Grid Layout -->
        <div class="grid-layout">
            <!-- Main Content -->
            <div class="main-content">
                <!-- Profile Card -->
                <div class="card">
                    <div class="profile-header">
                        @if($usuario->avatar_url)
                            <img src="{{ $usuario->avatar_url }}" alt="{{ $usuario->username }}" class="profile-avatar">
                        @else
                            <div class="profile-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 40px;">
                                {{ strtoupper(substr($usuario->username, 0, 1)) }}
                            </div>
                        @endif

                        <div class="profile-info">
                            <div class="profile-name">
                                {{ $usuario->username }}
                            </div>
                            <div class="profile-email">{{ $usuario->email }}</div>

                            <div class="profile-badges">
                                <span class="badge nivel-{{ $usuario->nivel_usuario }}">
                                    {{ $usuario->nivel_usuario === 'admin' ? 'Administrador' : ($usuario->nivel_usuario === 'moderador' ? 'Moderador' : 'Usuário') }}
                                </span>
                                <span class="badge status-{{ $usuario->status }}">
                                    {{ ucfirst($usuario->status) }}
                                </span>
                                @if($usuario->warning_ativo)
                                    <span class="badge warning">Warning Ativo</span>
                                @endif
                                @if($usuario->ban_tipo)
                                    <span class="badge ban">{{ $usuario->ban_tipo === 'temporario' ? 'Ban Temporário' : 'Ban Permanente' }}</span>
                                @endif
                                @if($usuario->ip_ban_ativo)
                                    <span class="badge ip-ban">IP Ban</span>
                                @endif
                            </div>

                            @if($usuario->bio)
                                <div class="profile-bio">{{ $usuario->bio }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="stats-mini-grid">
                        <div class="stat-mini">
                            <div class="stat-mini-value">{{ $usuario->tickets_count }}</div>
                            <div class="stat-mini-label">Tickets</div>
                        </div>
                        <div class="stat-mini">
                            <div class="stat-mini-value">{{ $usuario->denuncias_recebidas_count }}</div>
                            <div class="stat-mini-label">Denúncias</div>
                        </div>
                        <div class="stat-mini">
                            <div class="stat-mini-value">{{ $usuario->posts_count ?? 0 }}</div>
                            <div class="stat-mini-label">Posts</div>
                        </div>
                        <div class="stat-mini">
                            <div class="stat-mini-value">{{ $usuario->comentarios_count ?? 0 }}</div>
                            <div class="stat-mini-label">Comentários</div>
                        </div>
                    </div>
                </div>

                <!-- Histórico de Punições -->
                @if($historicoPunicoes->count() > 0)
                    <div class="card">
                        <h2 class="section-title">⚠️ Histórico de Punições</h2>

                        @foreach($historicoPunicoes as $punicao)
                            <div class="historico-item {{ $punicao['cor'] }}">
                                <div class="historico-header">
                                    <div class="historico-tipo">{{ $punicao['tipo'] }}</div>
                                    @if($punicao['ativo'])
                                        <span class="badge {{ $punicao['cor'] }}">Ativo</span>
                                    @endif
                                </div>

                                <div class="historico-motivo">
                                    <strong>Motivo:</strong> {{ $punicao['motivo'] ?? 'Não especificado' }}
                                </div>

                                <div class="historico-meta">
                                    <div><strong>Data:</strong> {{ $punicao['data']->format('d/m/Y H:i') }}</div>
                                    @if(isset($punicao['data_fim']) && $punicao['data_fim'])
                                        <div><strong>Expira em:</strong> {{ $punicao['data_fim']->format('d/m/Y H:i') }}</div>
                                    @endif
                                    @if($punicao['aplicado_por'])
                                        <div><strong>Aplicado por:</strong> {{ $punicao['aplicado_por']->username }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Tickets do Usuário -->
                <div class="card">
                    <h2 class="section-title"> Tickets Relacionados</h2>

                    @forelse($tickets as $ticket)
                        <a href="{{ route('suporte.show', $ticket->id) }}" class="ticket-item">
                            <div class="ticket-header">
                                <div class="ticket-numero">#{{ $ticket->numero_ticket }}</div>
                                <span class="badge ticket-{{ $ticket->categoria === 'denuncia' ? 'denuncia' : ($ticket->status === 'novo' ? 'novo' : ($ticket->status === 'em_analise' ? 'analise' : ($ticket->status === 'resolvido' ? 'resolvido' : 'fechado'))) }}">
                                    {{ $ticket->getStatusLabel() }}
                                </span>
                            </div>

                            <div class="ticket-titulo">{{ Str::limit($ticket->assunto, 60) }}</div>

                            <div class="ticket-meta">
                                Categoria: {{ $ticket->getCategoriaLabel() }} • {{ $ticket->created_at->format('d/m/Y') }}
                            </div>
                        </a>
                    @empty
                        <div class="empty-state">
                            <p>Nenhum ticket encontrado</p>
                        </div>
                    @endforelse
                </div>

                <!-- Contas Associadas -->
                @if($contasAssociadas->count() > 0)
                    <div class="card">
                        <h2 class="section-title">🔗 Contas Associadas (Mesmo Dispositivo)</h2>

                        @foreach($contasAssociadas as $conta)
                            <div class="conta-associada">
                                @if($conta->avatar_url)
                                    <img src="{{ $conta->avatar_url }}" alt="{{ $conta->username }}" class="conta-avatar">
                                @else
                                    <div class="conta-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                        {{ strtoupper(substr($conta->username, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="conta-info">
                                    <div class="conta-username">{{ $conta->username }}</div>
                                    <div class="conta-email">{{ $conta->email }}</div>
                                </div>
                                <a href="{{ route('moderacao.usuarios.show', $conta->id) }}" class="btn-action" style="width: auto; padding: 8px 16px; font-size: 13px;">Ver</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Informações -->
                <div class="sidebar-card">
                    <h3><img src="/images/ICONS/info.png" alt="Informações" style="width:24px; height:24px; margin-right:6px;"> Informações</h3>
                    
                    <dl class="info-list">
                        <div class="info-item">
                            <dt>ID do Usuário</dt>
                            <dd>{{ $usuario->id }}</dd>
                        </div>

                        <div class="info-item">
                            <dt>Data de Nascimento</dt>
                            <dd>{{ $usuario->data_de_nascimento ? $usuario->data_de_nascimento->format('d/m/Y') : 'Não informado' }}</dd>
                        </div>

                        <div class="info-item">
                            <dt>Cadastrado em</dt>
                            <dd>{{ $usuario->data_criacao->format('d/m/Y H:i') }}</dd>
                        </div>

                        <div class="info-item">
                            <dt>Dispositivos Cadastrados</dt>
                            <dd>{{ $usuario->deviceFingerprints->count() }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Ações de Moderação -->
                <!-- Ações de Moderação -->
                <div class="sidebar-card">
    <h3><img src="/images/ICONS/badge.png" alt="Adicionar comentário" style="width:28px; height:28x; margin-right:6px;"> Ações de Moderação</h3>

    @if(!$usuario->warning_ativo && !$usuario->ban_tipo && !$usuario->ip_ban_ativo)
        <!-- Sem punições - mostrar botões de punir -->
        
        <!-- Warning -->
        <button type="button" 
                onclick="openPunishmentModal('warning', {{ $usuario->id }})" 
                class="btn-action btn-warning">
             Aplicar Warning
        </button>

        <!-- Ban Temporário -->
        <button type="button" 
                onclick="openPunishmentModal('ban-temp', {{ $usuario->id }})" 
                class="btn-action btn-ban" 
                style="margin-top: 12px;">
             Ban Temporário
        </button>

        <!-- Ban Permanente -->
        <button type="button" 
                onclick="openPunishmentModal('perma-ban', {{ $usuario->id }})" 
                class="btn-action btn-ban" 
                style="margin-top: 12px;">
             Ban Permanente
        </button>

        @if(auth()->user()->isAdmin())
            <!-- IP Ban (Apenas Admins) -->
            <button type="button" 
                    onclick="openPunishmentModal('ip-ban', {{ $usuario->id }})" 
                    class="btn-action btn-ip-ban" 
                    style="margin-top: 12px;">
                 IP Ban
            </button>
        @endif
    @else
        <!-- Usuário tem punições ativas -->
        
        @if(auth()->user()->isAdmin())
            <!-- Mostrar ações específicas baseadas na punição -->
            
            @if($usuario->ip_ban_ativo)
                <!-- Remover IP Ban Especificamente -->
                <form action="{{ route('moderacao.usuarios.remover-ip-ban', $usuario->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Tem certeza que deseja remover o IP ban deste usuário?')"
                      style="margin-bottom: 12px;">
                    @csrf
                    <div style="background: #fef2f2; padding: 12px; border-radius: 8px; margin-bottom: 8px; border-left: 4px solid #dc2626;">
                        <strong style="color: #991b1b; font-size: 13px;">⚠️ IP BAN ATIVO</strong>
                    </div>
                    <button type="submit" class="btn-action" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                         Remover IP Ban
                    </button>
                </form>
            @endif
            
            @if($usuario->warning_ativo || $usuario->ban_tipo)
                <div style="background: #fef3c7; padding: 12px; border-radius: 8px; margin-bottom: 12px; border-left: 4px solid #f59e0b;">
                    <strong style="color: #92400e; font-size: 13px;">
                        @if($usuario->warning_ativo)
                            ⚠️ WARNING ATIVO
                        @elseif($usuario->ban_tipo === 'temporario')
                            ⏰ BAN TEMPORÁRIO
                        @elseif($usuario->ban_tipo === 'permanente')
                            ⛔ BAN PERMANENTE
                        @endif
                    </strong>
                </div>
            @endif
            
            <!-- Reativar Usuário (Remove TODAS as punições) -->
            <form action="{{ route('moderacao.usuarios.reativar', $usuario->id) }}" 
                  method="POST" 
                  onsubmit="return confirm('Tem certeza que deseja reativar este usuário e remover TODAS as punições?')">
                @csrf
                <button type="submit" class="btn-action btn-reactivate">
                    ✅ Reativar Usuário Completamente
                </button>
            </form>
            
            <small style="display: block; margin-top: 8px; color: #6b7280; font-size: 12px; text-align: center;">
                Remove todas as punições ativas
            </small>
        @endif
    @endif
</div>
            </div>
        </div>
    </div>

    <!-- Modal de Punição -->
    <div id="punishmentModal" class="modal">
    <div class="modal-content" onclick="event.stopPropagation()">
        <h2 class="modal-title" id="modalTitle">Aplicar Punição</h2>
        
        <form id="punishmentForm" method="POST">
            @csrf
            
            <!-- Campo de Motivo -->
            <div class="form-group">
                <label class="form-label">Motivo *</label>
                <textarea name="motivo" 
                          id="motivo-input"
                          class="form-textarea" 
                          required 
                          minlength="10" 
                          maxlength="1000" 
                          placeholder="Descreva o motivo da punição (mínimo 10 caracteres)..."></textarea>
                <small style="color: #6b7280; font-size: 12px; display: block; margin-top: 4px;">
                    Mínimo: 10 caracteres | Máximo: 1000 caracteres
                </small>
            </div>

            <!-- Campo de Duração (apenas para ban temporário) -->
            <div class="form-group" id="diasGroup" style="display: none;">
                <label class="form-label">Duração (dias) *</label>
                <input type="number" 
                       name="dias" 
                       id="dias-input"
                       class="form-input" 
                       min="1" 
                       max="365" 
                       placeholder="Ex: 7">
                <small style="color: #6b7280; font-size: 12px; display: block; margin-top: 4px;">
                    Mínimo: 1 dia | Máximo: 365 dias
                </small>
            </div>

            <!-- Botões -->
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">
                    Cancelar
                </button>
                <button type="submit" class="btn-submit" id="submitModalBtn">
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>


    <script>
    // Variáveis globais
    const modal = document.getElementById('punishmentModal');
    const form = document.getElementById('punishmentForm');
    const title = document.getElementById('modalTitle');
    const diasGroup = document.getElementById('diasGroup');
    const diasInput = document.getElementById('dias-input');
    const motivoInput = document.getElementById('motivo-input');
    const submitBtn = document.getElementById('submitModalBtn');

    /**
     * Abrir modal de punição
     */
    function openPunishmentModal(type, userId) {
        // Resetar formulário
        form.reset();
        diasGroup.style.display = 'none';
        diasInput.required = false;

        // Configurar action do formulário e título
        switch(type) {
            case 'warning':
                form.action = `/moderacao/usuarios/${userId}/warning`;
                title.textContent = '⚠️ Aplicar Warning';
                submitBtn.textContent = 'Aplicar Warning';
                submitBtn.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
                break;

            case 'ban-temp':
                form.action = `/moderacao/usuarios/${userId}/ban-temporario`;
                title.textContent = '🚫 Aplicar Ban Temporário';
                submitBtn.textContent = 'Aplicar Ban Temporário';
                submitBtn.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                diasGroup.style.display = 'block';
                diasInput.required = true;
                break;

            case 'perma-ban':
                form.action = `/moderacao/usuarios/${userId}/perma-ban`;
                title.textContent = '⛔ Aplicar Ban Permanente';
                submitBtn.textContent = 'Aplicar Ban Permanente';
                submitBtn.style.background = 'linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%)';
                break;

            case 'ip-ban':
                form.action = `/moderacao/usuarios/${userId}/ip-ban`;
                title.textContent = '📡 Aplicar IP Ban';
                submitBtn.textContent = 'Aplicar IP Ban';
                submitBtn.style.background = 'linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%)';
                break;
        }

        // Exibir modal
        modal.classList.add('active');
    }

    /**
     * Fechar modal
     */
    function closeModal() {
        modal.classList.remove('active');
        form.reset();
    }

    // Fechar modal ao clicar fora do conteúdo
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Validação do formulário antes de enviar
    form.addEventListener('submit', function(e) {
        const motivo = motivoInput.value.trim();
        
        // Validar motivo
        if (motivo.length < 10) {
            e.preventDefault();
            alert('O motivo deve ter no mínimo 10 caracteres.');
            motivoInput.focus();
            return false;
        }

        if (motivo.length > 1000) {
            e.preventDefault();
            alert('O motivo não pode ter mais de 1000 caracteres.');
            motivoInput.focus();
            return false;
        }

        // Validar dias (se for ban temporário)
        if (diasGroup.style.display !== 'none') {
            const dias = parseInt(diasInput.value);
            
            if (!dias || dias < 1 || dias > 365) {
                e.preventDefault();
                alert('A duração deve ser entre 1 e 365 dias.');
                diasInput.focus();
                return false;
            }
        }

        // Confirmação final
        const confirmMsg = `Tem certeza que deseja aplicar esta punição?\n\nMotivo: ${motivo.substring(0, 100)}${motivo.length > 100 ? '...' : ''}`;
        
        if (!confirm(confirmMsg)) {
            e.preventDefault();
            return false;
        }

        // Desabilitar botão para evitar duplo envio
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';
    });

    // Contador de caracteres para o motivo (opcional)
    motivoInput.addEventListener('input', function() {
        const length = this.value.length;
        const max = 1000;
        const parent = this.parentElement;
        
        let counter = parent.querySelector('.char-counter');
        if (!counter) {
            counter = document.createElement('small');
            counter.className = 'char-counter';
            counter.style.cssText = 'display: block; text-align: right; margin-top: 4px; font-size: 11px;';
            parent.appendChild(counter);
        }
        
        counter.textContent = `${length}/${max} caracteres`;
        counter.style.color = length < 10 ? '#ef4444' : (length > 900 ? '#f59e0b' : '#6b7280');
    });

    // Log para debug
    console.log('✅ Sistema de punição carregado');
</script>
</body>
</html>