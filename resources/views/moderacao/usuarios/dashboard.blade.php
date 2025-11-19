<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Moderação - Usuários</title>
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

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-content h1 {
            font-size: 32px;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 12px;
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

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .stat-card.border-blue { border-left: 4px solid #3b82f6; }
        .stat-card.border-yellow { border-left: 4px solid #f59e0b; }
        .stat-card.border-red { border-left: 4px solid #ef4444; }
        .stat-card.border-purple { border-left: 4px solid #a855f7; }
        .stat-card.border-gray { border-left: 4px solid #6b7280; }
        .stat-card.border-green { border-left: 4px solid #10b981; }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-info h3 {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #1a202c;
            line-height: 1;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); }
        .stat-icon.yellow { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); }
        .stat-icon.red { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); }
        .stat-icon.purple { background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); }
        .stat-icon.gray { background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); }
        .stat-icon.green { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); }

        .stat-icon svg {
            width: 28px;
            height: 28px;
        }

        .stat-icon.blue svg { color: #2563eb; }
        .stat-icon.yellow svg { color: #d97706; }
        .stat-icon.red svg { color: #dc2626; }
        .stat-icon.purple svg { color: #9333ea; }
        .stat-icon.gray svg { color: #4b5563; }
        .stat-icon.green svg { color: #059669; }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 32px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .card-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-link {
            color: #667eea;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .card-link:hover {
            color: #764ba2;
        }

        /* Punições Recentes */
        .punicao-item {
            padding: 20px;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 16px;
            transition: background 0.2s;
        }

        .punicao-item:hover {
            background: #f3f4f6;
        }

        .punicao-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .punicao-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .punicao-info {
            flex: 1;
        }

        .punicao-username {
            font-size: 15px;
            font-weight: 600;
            color: #1a202c;
        }

        .punicao-tipo {
            font-size: 13px;
            color: #6b7280;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge.yellow { background: #fef3c7; color: #92400e; }
        .badge.red { background: #fee2e2; color: #991b1b; }
        .badge.gray { background: #f3f4f6; color: #4b5563; }

        .punicao-details {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
            margin-top: 8px;
        }

        .punicao-moderador {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 8px;
            font-style: italic;
        }

        /* Moderadores Ativos */
        .moderador-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            border-radius: 12px;
            margin-bottom: 12px;
        }

        .moderador-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
        }

        .moderador-info {
            flex: 1;
        }

        .moderador-nome {
            font-size: 15px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .moderador-nivel {
            font-size: 12px;
            color: #6b7280;
        }

        .moderador-stats {
            text-align: right;
        }

        .moderador-count {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }

        .moderador-label {
            font-size: 11px;
            color: #6b7280;
        }

        /* Usuários Recentes */
        .usuario-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 12px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .usuario-item:hover {
            background: #f3f4f6;
            transform: translateX(5px);
        }

        .usuario-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .usuario-info {
            flex: 1;
        }

        .usuario-nome {
            font-size: 15px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .usuario-data {
            font-size: 13px;
            color: #6b7280;
        }

        /* Denúncias Pendentes */
        .denuncia-item {
            padding: 20px;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 4px solid #ef4444;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .denuncia-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .denuncia-numero {
            font-size: 15px;
            font-weight: 700;
            color: #667eea;
        }

        .denuncia-titulo {
            font-size: 14px;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .denuncia-envolvidos {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
        }

        .denuncia-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #667eea;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            margin-top: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            color: #cbd5e0;
        }

        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                padding: 20px;
            }

            .header-content h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <h1>
                    🛡️ Moderação de Usuários
                </h1>
                <div class="header-actions">
                    <a href="{{ route('moderacao.usuarios.index') }}" class="btn btn-primary">
                        Ver Todos os Usuários
                    </a>
                    <a href="{{ route('suporte.moderacao.dashboard') }}" class="btn btn-secondary">
                        Dashboard de Tickets
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
                    <h2>⚠️ Punições Recentes</h2>
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
                    <h2>🚨 Denúncias Pendentes</h2>
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
                    <h2>👮 Moderadores Mais Ativos</h2>
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
                    <h2>✨ Usuários Recentes</h2>
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