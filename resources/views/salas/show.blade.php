<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sala->nome }} - Ambience RPG</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS e Font Awesome (opcional, mantenha se já usa no projeto) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <!-- Vite: carrega app.tsx que importa bootstrap.ts (define window.Echo) -->
    @vite('resources/js/app.tsx')

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .sala-header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,.1);
            backdrop-filter: blur(10px);
            margin: 20px 0;
            padding: 24px 28px;
        }

        .websocket-status {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: #fff;
            padding: 10px 16px;
            border-radius: 25px;
            font-size: 0.9rem;
            z-index: 1050;
        }
        .websocket-status.offline {
            background: #6c757d;
        }

        .user-card {
            transition: transform .15s ease;
        }
        .user-card:hover {
            transform: translateY(-2px);
        }

        .user-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
        }

        .badge-role {
            font-weight: 500;
            letter-spacing: .2px;
        }

        .section-title {
            font-weight: 700;
            color: #343a40;
        }
    </style>
</head>
<body>
<div class="container py-4">

    <!-- Status WebSocket flutuante -->
    <div id="ws-status" class="websocket-status">
        <span id="ws-status-text">Conectado</span>
    </div>

    <!-- Cabeçalho da sala -->
    <div class="sala-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-1">{{ $sala->nome }}</h2>
                <div class="text-muted">
                    <span class="me-3">ID: #{{ $sala->id }}</span>
                    <span class="me-3">
                        @if($sala->tipo === 'publica')
                            🌍 Pública
                        @elseif($sala->tipo === 'privada')
                            🔒 Privada
                        @else
                            🔧 Apenas Convite
                        @endif
                    </span>
                    <span class="me-3">👥 {{ $sala->participantes->count() }}/{{ $sala->max_participantes }}</span>
                    <span class="me-1">⚔️ {{ ucfirst($meu_papel) }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('salas.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar às Salas
                </a>

                @if($meu_papel !== 'mestre' && $sala->criador_id !== auth()->id())
                    <form method="POST" action="{{ route('salas.sair', ['id' => $sala->id]) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fa-solid fa-door-open me-1"></i> Sair da Sala
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if($sala->descricao)
            <hr>
            <div>
                <h6 class="mb-2 section-title">Descrição</h6>
                <p class="mb-0">{{ $sala->descricao }}</p>
            </div>
        @endif
    </div>

    <!-- Status de Conexão em Tempo Real -->
    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3 section-title">Status de Conexão</h5>
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2">Status:</span>
                        <span id="ws-badge" class="badge rounded-pill bg-success">Conectado</span>
                    </div>
                    <div class="mb-2">
                        <span class="me-2">Ping:</span>
                        <span id="ws-ping">{{ $websocket_status['ping'] ?? 'N/A' }}ms</span>
                    </div>
                    <div class="mb-2">
                        <span class="me-2">Servidor:</span>
                        <span id="ws-server">{{ $websocket_status['server'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="me-2">Última Verificação:</span>
                        <span id="ws-last-check">Agora</span>
                    </div>
                    <div class="mt-3 text-muted small">
                        Sistema preparado para comunicação em tempo real via WebSocket.
                    </div>
                </div>
            </div>
        </div>

        <!-- Participantes -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="card-title mb-0 section-title">Participantes Ativos</h5>
                        @if($minhas_permissoes && $minhas_permissoes->pode_convidar_usuarios)
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalConvite">
                                <i class="fa-solid fa-user-plus me-1"></i> Convidar Usuário
                            </button>
                        @endif
                    </div>
                    <hr>

                    <div class="row g-3">
                        @foreach($sala->participantes as $participante)
                            @php
                                $uid = $participante->usuario->id;
                                $isCriador = (int)$participante->usuario_id === (int)$sala->criador_id;
                                $papel = ucfirst(str_replace('_',' ', $participante->papel));
                            @endphp

                            <div class="col-12 col-md-6">
                                <div class="card user-card" id="user-{{ $uid }}">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            @if(!empty($participante->usuario->avatar))
                                                <img src="{{ $participante->usuario->avatar }}" alt="Avatar"
                                                     class="rounded-circle me-3" width="42" height="42">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3"
                                                     style="width: 42px; height: 42px;">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                            @endif

                                            <div>
                                                <h6 class="mb-1">
                                                    @if($participante->papel === 'mestre')
                                                        <i class="fa-solid fa-hat-wizard text-warning me-1"></i>
                                                    @elseif($participante->papel === 'admin_sala')
                                                        <i class="fa-solid fa-shield-halved text-primary me-1"></i>
                                                    @else
                                                        <i class="fa-solid fa-user text-muted me-1"></i>
                                                    @endif
                                                    {{ $participante->usuario->username }}
                                                </h6>
                                                <small class="text-muted">
                                                    <span class="badge rounded-pill bg-light text-dark badge-role me-1">{{ $papel }}</span>
                                                    @if($isCriador)
                                                        • Criador
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <small class="user-status text-muted d-block"
                                                   data-user-status
                                                   data-user-id="{{ $uid }}">
                                                Offline
                                            </small>
                                            <span class="badge rounded-pill user-dot bg-secondary"
                                                  data-user-dot
                                                  data-user-id="{{ $uid }}"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($sala->participantes->isEmpty())
                            <div class="col-12">
                                <div class="alert alert-light border text-muted mb-0">
                                    Nenhum participante.
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Minhas Permissões -->
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3 section-title">Minhas Permissões</h5>
                    @if($minhas_permissoes)
                        <div class="row g-2">
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled
                                           {{ $minhas_permissoes->pode_criar_conteudo ? 'checked' : '' }}>
                                    <label class="form-check-label">Criar Conteúdo</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled
                                           {{ $minhas_permissoes->pode_editar_grid ? 'checked' : '' }}>
                                    <label class="form-check-label">Editar Grid</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled
                                           {{ $minhas_permissoes->pode_iniciar_sessao ? 'checked' : '' }}>
                                    <label class="form-check-label">Iniciar Sessão</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled
                                           {{ $minhas_permissoes->pode_moderar_chat ? 'checked' : '' }}>
                                    <label class="form-check-label">Moderar Chat</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled
                                           {{ $minhas_permissoes->pode_convidar_usuarios ? 'checked' : '' }}>
                                    <label class="form-check-label">Convidar Usuários</label>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-muted">Permissões não atribuídas para este usuário nesta sala.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informações da Sala -->
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3 section-title">Informações</h5>
                    <div class="row">
                        <div class="col-12 col-md-4 mb-2">
                            <div class="text-muted">Criador:</div>
                            <div>{{ $sala->criador->username ?? 'N/A' }}</div>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <div class="text-muted">Criada em:</div>
                            <div>{{ \Carbon\Carbon::parse($sala->data_criacao)->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <div class="text-muted">Última atividade:</div>
                            <div>{{ \Carbon\Carbon::parse($sala->data_criacao)->diffForHumans() }}</div>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <div class="text-muted">Status:</div>
                            <div>{{ $sala->ativa ? 'Ativa' : 'Inativa' }}</div>
                        </div>
                    </div>

                    @if($minhas_permissoes && $minhas_permissoes->pode_convidar_usuarios)
                        <button class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalConvite">
                            <i class="fa-solid fa-envelope me-1"></i> Convidar Usuário
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Convidar Usuário -->
    <div class="modal fade" id="modalConvite" tabindex="-1" aria-labelledby="modalConviteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('salas.convidar', ['id' => $sala->id]) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConviteLabel">Convidar Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email do Usuário</label>
                        <input type="email" name="email" class="form-control" placeholder="usuario@exemplo.com" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Expira em (horas)</label>
                        <select class="form-select" name="expira_em_horas" required>
                            <option value="24">24 horas</option>
                            <option value="72">3 dias</option>
                            <option value="168">1 semana</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Convite</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Bootstrap JS (se usa os componentes como modal/toast/etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script: Presence channel para status Online/Offline por sala -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const salaId = {{ $sala->id }};
    const channelName = `sala.${salaId}`;

    const wsBadge = document.getElementById('ws-badge');
    const wsStatus = document.getElementById('ws-status');
    const wsStatusText = document.getElementById('ws-status-text');

    function setWsOnline(online) {
        if (!wsBadge || !wsStatus || !wsStatusText) return;
        wsBadge.classList.toggle('bg-success', online);
        wsBadge.classList.toggle('bg-secondary', !online);
        wsBadge.textContent = online ? 'Conectado' : 'Desconectado';
        wsStatus.classList.toggle('offline', !online);
        wsStatusText.textContent = online ? 'Conectado' : 'Desconectado';
    }

    function setOnline(userId, online) {
        const card = document.getElementById(`user-${userId}`);
        if (!card) return;

        const statusEl = card.querySelector(`[data-user-status][data-user-id="${userId}"]`);
        const dotEl = card.querySelector(`[data-user-dot][data-user-id="${userId}"]`);

        if (statusEl) {
            statusEl.textContent = online ? 'Online' : 'Offline';
            statusEl.classList.toggle('text-success', online);
            statusEl.classList.toggle('text-muted', !online);
        }
        if (dotEl) {
            dotEl.classList.toggle('bg-success', online);
            dotEl.classList.toggle('bg-secondary', !online);
        }
    }

    // Marca todos como offline inicialmente
    document.querySelectorAll('[data-user-status]').forEach(function(el) {
        el.textContent = 'Offline';
        el.classList.remove('text-success');
        el.classList.add('text-muted');
    });
    document.querySelectorAll('[data-user-dot]').forEach(function(el) {
        el.classList.remove('bg-success');
        el.classList.add('bg-secondary');
    });

    // Confere se Echo foi injetado pelo bootstrap.ts importado em app.tsx
    if (!window.Echo) {
        setWsOnline(false);
        console.warn('Echo não encontrado. Verifique Vite/Bootstrap.ts e variáveis do Reverb/Pusher.');
        return;
    }

    setWsOnline(true);

    // Entra no presence channel da sala
    const presence = window.Echo.join(channelName)
        .here((users) => {
            users.forEach((u) => setOnline(u.id, true));
        })
        .joining((u) => {
            setOnline(u.id, true);
        })
        .leaving((u) => {
            setOnline(u.id, false);
        })
        .error((e) => {
            console.error('Erro no presence channel:', e);
            setWsOnline(false);
        });

    // Ao fechar/navegar, sair do canal para refletir offline imediato
    window.addEventListener('beforeunload', () => {
        try { window.Echo.leave(channelName); } catch (_) {}
    });
});
</script>
</body>
</html>
