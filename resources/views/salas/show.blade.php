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
<style>
    .sala-banner {
        width: 100%;
        height: 320px;
        background-position: center center;
        background-size: cover;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        margin-bottom: 12px;
    }
    .sala-banner .banner-edit-btn {
        position: absolute;
        right: 12px;
        top: 12px;
        z-index: 10;
    }
    .sala-banner .banner-fallback {
        width: 100%;
        height: 100%;
        display:flex;
        align-items:center;
        justify-content:center;
        color: rgba(255,255,255,0.95);
        font-weight:700;
        font-size:22px;
    }
</style>

<div class="sala-banner"
     @if($sala->banner_url)
        style="background-image: url('{{ $sala->banner_url }}');"
     @else
        style="background-color: {{ $sala->banner_color ?? '#6c757d' }};"
     @endif
     data-sala-id="{{ $sala->id }}"
>
    @if((int)auth()->id() === (int)$sala->criador_id)
        <div class="banner-edit-btn">
            <button class="btn btn-sm btn-light" id="openBannerEditorBtn" data-sala-id="{{ $sala->id }}">
                <i class="fa-solid fa-image me-1"></i> Editar Banner
            </button>
        </div>
    @endif

    @if(!$sala->banner_url)
        <div class="banner-fallback">
            {{ $sala->nome }}
        </div>
    @endif
</div>
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="position-relative me-3">
                    @if(!empty($sala->profile_photo_url))
                        <img src="{{ $sala->profile_photo_url }}" alt="Foto da sala" class="rounded-circle border" width="65" height="65">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white border"
                             style="width: 65px; height: 65px; background-color: {{ $sala->profile_photo_color ?? '#6c757d' }};">
                            {{ strtoupper(mb_substr($sala->nome, 0, 1)) }}
                        </div>
                    @endif

                    @if((int)auth()->id() === (int)$sala->criador_id)
                        <button id="openProfilePhotoEditorBtn"
        type="button"
        class="btn btn-sm btn-light position-absolute bottom-0 end-0 rounded-circle shadow-sm"
        data-sala-id="{{ $sala->id }}"
        data-foto-url="{{ $sala->profile_photo_url ?? '' }}"
        title="Editar foto de perfil">
    <i class="fa-solid fa-camera"></i>
</button>
                    @endif
                </div>
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
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('salas.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar às Salas
                </a>


                @if($meu_papel !== 'mestre' && $sala->criador_id !== auth()->id())
    <!-- Botão que abre o modal de confirmação -->
    <button id="btnSairSala" type="button" class="btn btn-outline-danger">
        <i class="fa-solid fa-door-open me-1"></i> Sair da Sala
    </button>
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

@if($minhas_permissoes->pode_iniciar_sessao)
    <div id="iniciar-sessao-container-js" class="mt-3 text-end">
        <button id="btnIniciarSessao" class="btn btn-success btn-lg">
            <i class="fa-solid fa-play me-2"></i> Iniciar Sessão
        </button>
    </div>
@endif
    
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

                                        <div class="text-end d-flex align-items-start">
    <div class="me-2 text-end">
        <small class="user-status text-muted d-block"
               data-user-status
               data-user-id="{{ $uid }}">
            Offline
        </small>
        <span class="badge rounded-pill user-dot bg-secondary"
              data-user-dot
              data-user-id="{{ $uid }}"></span>
    </div>

    @if((int)auth()->id() === (int)$sala->criador_id && (int)$uid !== (int)$sala->criador_id)
        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                    id="dropdownMenuButton-{{ $uid }}" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-{{ $uid }}">
                <button type="button" class="dropdown-item manage-permissions-btn" data-user-id="{{ $uid }}">
  <i class="fa-solid fa-user-gear me-1"></i> Gerenciar Permissões
</button>
                {{-- outras ações aqui se quiser (ex: promover, remover) --}}
            </ul>
        </div>
    @endif
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
    {{-- BOTÕES DE CONTROLE DE SESSÃO --}}
@if($sessao_ativa)
    {{-- Há uma sessão ativa --}}
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-green-800">
                    🎮 Sessão em Andamento
                </h3>
                <p class="text-sm text-green-600">
                    {{ $sessao_ativa->nome_sessao }}
                </p>
                <p class="text-xs text-green-500">
                    Status: {{ ucfirst($sessao_ativa->status) }}
                </p>
            </div>
            <div class="flex gap-2">
                @if($participa_na_sessao)
                    {{-- Usuário já está na sessão --}}
                    <a href="{{ route('sessoes.show', ['id' => $sessao_ativa->id]) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                        🎲 Ir para Sessão
                    </a>
                @else
                    {{-- Usuário não está na sessão, mostrar botão para entrar --}}
                    <form action="{{ route('sessoes.entrar', ['id' => $sessao_ativa->id]) }}" 
                          method="POST" 
                          class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            🚪 Entrar na Sessão
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@else
    {{-- Não há sessão ativa --}}
    @if($minhas_permissoes && $minhas_permissoes->pode_iniciar_sessao)
        <div id="iniciar-sessao-container" 
             data-sala-id="{{ $sala->id }}" 
             data-tem-permissao="true" 
             data-user-id="{{ auth()->id() }}">
        </div>
    @endif
@endif


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

<script>
document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('btnSairSala');
  const confirmBtn = document.getElementById('confirmSairBtn');

  if (!btn || !confirmBtn) return;

  function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) return meta.getAttribute('content');
    const inputToken = document.querySelector('input[name="_token"]');
    if (inputToken) return inputToken.value;
    return '';
  }

  btn.addEventListener('click', function () {
    const modalEl = document.getElementById('confirmSairModal');
    if (window.bootstrap && modalEl) {
      const modal = new bootstrap.Modal(modalEl);
      modal.show();
    } else {
      if (confirm('Você deseja mesmo sair dessa sala?')) {
        submitSair();
      }
    }
  });

  confirmBtn.addEventListener('click', function () {
    confirmBtn.disabled = true;
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = 'Saindo...';
    submitSair().finally(() => {
      confirmBtn.disabled = false;
      confirmBtn.innerHTML = originalText;
    });
  });

  async function submitSair() {
    try {
      const csrfToken = getCsrfToken();
      const url = "{{ route('salas.sair', ['id' => $sala->id]) }}";

      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
      });

      const json = await res.json();

      if (res.ok && json.success) {
        const modalEl = document.getElementById('confirmSairModal');
        if (window.bootstrap && modalEl) {
          const modal = bootstrap.Modal.getInstance(modalEl);
          if (modal) modal.hide();
        }
        try {
  if (window.Echo) {
    Echo.leave('presence-sala.{{ $sala->id }}'); // ajuste o nome do channel conforme usa
  } else if (window.Pusher) {
    // se usa Pusher direto: unsubscribe do channel
    const chName = 'presence-sala.{{ $sala->id }}';
    try {
      const pusher = Echo && Echo.connector && Echo.connector.pusher ? Echo.connector.pusher : window.Pusher && window.Pusher.instances && window.Pusher.instances[0];
      if (pusher && pusher.channels && pusher.channels.channels[chName]) {
        pusher.unsubscribe(chName);
      }
    } catch(e) { /* ignorar */ }
  }
} catch(e){ console.warn('Erro ao tentar deixar channel:', e); }

window.location.replace(json.redirect_to || "{{ route('salas.index') }}");
      } else {
        alert(json.message || 'Erro ao sair da sala.');
      }
    } catch (err) {
      console.error('Erro ao tentar sair da sala:', err);
      alert('Erro de rede ao tentar sair. Tente novamente.');
    }
  }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('managePermissionsModal');
  const mpForm = document.getElementById('managePermissionsForm');
  const mpUserId = document.getElementById('mp_user_id');
  const mpSaveBtn = document.getElementById('mp_save_btn');
  const mpAlert = document.getElementById('mp_alert');

  // Helpers para checkboxes
  const fields = {
    pode_criar_conteudo: document.getElementById('mp_pode_criar_conteudo'),
    pode_editar_grid: document.getElementById('mp_pode_editar_grid'),
    pode_iniciar_sessao: document.getElementById('mp_pode_iniciar_sessao'),
    pode_moderar_chat: document.getElementById('mp_pode_moderar_chat'),
    pode_convidar_usuarios: document.getElementById('mp_pode_convidar_usuarios'),
  };

  function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  }

  function getShowUrl(salaId, userId) {
    return `/salas/${salaId}/participantes/${userId}/permissoes`;
  }
  function getUpdateUrl(salaId, userId) {
    return `/salas/${salaId}/participantes/${userId}/permissoes`;
  }

  const SALA_ID = "{{ $sala->id }}";

  // Delegation: quando clicar em Gerenciar Permissões
  document.body.addEventListener('click', function (e) {
    const el = e.target.closest('.manage-permissions-btn');
    if (!el) return;

    e.preventDefault();
    const userId = el.getAttribute('data-user-id');
    if (!userId) return;

    // resetar modal
    mpUserId.value = userId;
    mpAlert.classList.add('d-none');
    mpAlert.innerText = '';

    // garantir que os checkboxes estejam em estado definido enquanto carrega
    Object.values(fields).forEach(f => { if (f) f.checked = false; });

    // fetch permissões (rota retorna { success, participante, permissoes })
    const url = getShowUrl(SALA_ID, userId);
    fetch(url, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrf()
      },
      credentials: 'same-origin'
    })
    .then(async res => {
      if (!res.ok) {
        // tenta mostrar mensagem se vier JSON
        let json = {};
        try { json = await res.json(); } catch (e) {}
        throw (json && json.message) ? json : { message: 'Erro ao buscar permissões.', status: res.status };
      }
      return res.json();
    })
    .then(data => {
      // Atenção: o backend retorna { success: true, participante: {...}, permissoes: {...} }
      // usar data.permissoes se existir
      const permissoes = (data && data.permissoes) ? data.permissoes : (data || {});

      // segurança: zerar antes e só marcar os que vierem como truthy
      Object.values(fields).forEach(f => { if (f) f.checked = false; });

      fields.pode_criar_conteudo.checked = !!permissoes.pode_criar_conteudo;
      fields.pode_editar_grid.checked = !!permissoes.pode_editar_grid;
      fields.pode_iniciar_sessao.checked = !!permissoes.pode_iniciar_sessao;
      fields.pode_moderar_chat.checked = !!permissoes.pode_moderar_chat;
      fields.pode_convidar_usuarios.checked = !!permissoes.pode_convidar_usuarios;

      // opcional: atualizar título do modal com username (se retornado)
      if (data.participante && data.participante.usuario && data.participante.usuario.username) {
        const label = document.getElementById('managePermissionsModalLabel');
        label.innerText = `Gerenciar Permissões — ${data.participante.usuario.username}`;
      }

      const modal = new bootstrap.Modal(modalEl);
      modal.show();
    })
    .catch(err => {
      mpAlert.className = 'alert alert-danger';
      mpAlert.innerText = err.message || 'Erro ao buscar permissões.';
      mpAlert.classList.remove('d-none');
    });
  });

  // Submissão do form (não alterei a lógica original além de garantir CSRF e payload corretos)
  mpForm.addEventListener('submit', function (e) {
    e.preventDefault();
    mpSaveBtn.disabled = true;
    mpSaveBtn.innerText = 'Salvando...';

    const userId = mpUserId.value;
    const payload = {
      pode_criar_conteudo: !!fields.pode_criar_conteudo.checked,
      pode_editar_grid: !!fields.pode_editar_grid.checked,
      pode_iniciar_sessao: !!fields.pode_iniciar_sessao.checked,
      pode_moderar_chat: !!fields.pode_moderar_chat.checked,
      pode_convidar_usuarios: !!fields.pode_convidar_usuarios.checked
    };

    const url = getUpdateUrl(SALA_ID, userId);

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrf()
      },
      credentials: 'same-origin',
      body: JSON.stringify(payload)
    })
    .then(async res => {
      mpSaveBtn.disabled = false;
      mpSaveBtn.innerText = 'Salvar';
      if (!res.ok) {
        const json = await res.json().catch(() => ({}));
        throw json;
      }
      return res.json();
    })
    .then(json => {
      mpAlert.className = 'alert alert-success';
      mpAlert.innerText = json.message || 'Permissões atualizadas com sucesso.';
      mpAlert.classList.remove('d-none');

      setTimeout(() => {
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
      }, 800);
    })
    .catch(err => {
      mpAlert.className = 'alert alert-danger';
      mpAlert.innerText = err.message || 'Erro ao salvar permissões.';
      mpAlert.classList.remove('d-none');
    });
  });

});
</script>

<!-- Modal de confirmação (Bootstrap 5) -->
    <div class="modal fade" id="confirmSairModal" tabindex="-1" aria-labelledby="confirmSairModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmSairModalLabel">Sair da sala</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            Você deseja mesmo sair dessa sala?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button id="confirmSairBtn" type="button" class="btn btn-danger">Sair</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Gerenciar Permissões -->
<div class="modal fade" id="managePermissionsModal" tabindex="-1" aria-labelledby="managePermissionsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <form id="managePermissionsForm">
        <div class="modal-header">
          <h5 class="modal-title" id="managePermissionsModalLabel">Gerenciar Permissões</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="mp_user_id" value="">
          <div class="mb-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="mp_pode_criar_conteudo">
              <label class="form-check-label" for="mp_pode_criar_conteudo">Criar Conteúdo</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="mp_pode_editar_grid">
              <label class="form-check-label" for="mp_pode_editar_grid">Editar Grid</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="mp_pode_iniciar_sessao">
              <label class="form-check-label" for="mp_pode_iniciar_sessao">Iniciar Sessão</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="mp_pode_moderar_chat">
              <label class="form-check-label" for="mp_pode_moderar_chat">Moderar Chat</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="mp_pode_convidar_usuarios">
              <label class="form-check-label" for="mp_pode_convidar_usuarios">Convidar Usuários</label>
            </div>
          </div>
          <div id="mp_alert" class="d-none alert" role="alert"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary" id="mp_save_btn">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    console.log('[Sala] Página carregada, configurando WebSocket...');
    
    // Verificar se Echo está disponível
    if (typeof window.Echo === 'undefined') {
        console.error('[Sala] Echo não está disponível!');
        return;
    }

    // IDs necessários
    const salaId = {{ $sala->id }};
    const userId = {{ auth()->id() }};
    
    console.log(`[Sala] Conectando ao canal de presença: sala.${salaId}`);
    
    // ==================== CONECTAR AO CANAL DE PRESENÇA DA SALA ====================
    const salaChannel = window.Echo.join(`sala.${salaId}`);
    
    // ==================== ESCUTAR EVENTO DE SESSÃO INICIADA ====================
    salaChannel.listen('.session.started', function(data) {
        console.log('[Sala] 🎮 SESSÃO INICIADA! Evento recebido:', data);
        
        if (!data || !data.redirect_to) {
            console.error('[Sala] Dados do evento inválidos:', data);
            return;
        }
        
        // Redirecionar IMEDIATAMENTE
        console.log('[Sala] Redirecionando para:', data.redirect_to);
        window.location.href = data.redirect_to;
    });
    
    // ==================== DETECTAR PRESENÇA ====================
    salaChannel.here((users) => {
        console.log('[Sala] 👥 Usuários online:', users);
    });
    
    salaChannel.joining((user) => {
        console.log('[Sala] ✅ Entrou:', user.name || user.username);
    });
    
    salaChannel.leaving((user) => {
        console.log('[Sala] ❌ Saiu:', user.name || user.username);
    });
    
    salaChannel.error((error) => {
        console.error('[Sala] ❌ Erro no canal:', error);
    });
    
    console.log('[Sala] ✅ Listener de sessão configurado!');
});



document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btnIniciarSessao');
    if (!btn) return;

    btn.addEventListener('click', async () => {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Iniciando...';

        try {
            const res = await fetch(`/salas/{{ $sala->id }}/iniciar-sessao`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ nome_sessao: null, configuracoes: {} })
            });
            const data = await res.json();
            if (data.success) {
                window.location.href = data.redirect_to;
            } else {
                alert(data.message || 'Erro ao iniciar sessão.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-play me-2"></i> Iniciar Sessão';
            }
        } catch {
            alert('Erro ao iniciar sessão. Tente novamente.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-play me-2"></i> Iniciar Sessão';
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('openBannerEditorBtn');
  if (!btn) return;

  btn.addEventListener('click', () => {
    const salaId = btn.getAttribute('data-sala-id');
    const bannerUrl = "{{ $sala->banner_url }}";
    const bannerColor = "{{ $sala->banner_color }}";

    if (window.openBannerEditor) {
      window.openBannerEditor(salaId, bannerUrl || null, bannerColor || null);
    } else {
      console.error('openBannerEditor indisponível');
    }
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('openProfilePhotoEditorBtn');
  if (!btn) return;

  btn.addEventListener('click', () => {
    const salaId = btn.getAttribute('data-sala-id');
    const fotoUrl = btn.getAttribute('data-foto-url') || "{{ $sala->foto_perfil_url ?? '' }}";

    if (window.openProfilePhotoEditor) {
      window.openProfilePhotoEditor(salaId, fotoUrl || null);
    } else {
      console.error('openProfilePhotoEditor indisponível');
    }
  });
});
</script>

@include('partials.banner-editor')
@include('partials.profile-photo-editor')
<!-- nsfwjs já traz o TF necessário (minified UMD) -->
<script src="https://unpkg.com/nsfwjs@2.4.2/dist/nsfwjs.min.js"></script>

<!-- depois seus scripts que usam nsfw (nsfw-detector.js, nsfw-alert.js) -->
<script src="{{ asset('js/nsfw-detector.js') }}"></script>
<script src="{{ asset('js/nsfw-alert.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  try {
    // Proteção: não pre-carrega se a conexão for péssima ou o usuário pediu economia de dados
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (conn && (conn.saveData || /2g/.test(conn.effectiveType || ''))) {
      console.log('Pulando pre-load do modelo (conexão lenta / save-data).');
      return;
    }
    // Informa visualmente (opcional)
    if (window.NSFWAlert) window.NSFWAlert.showLoading('profileNsfwAlert', 'Pré-carregando modelo NSFW...');
    // Inicia load (usa o modelPath já configurado no seu nsfw-detector.js)
    window.NSFWDetector.loadModel()
      .then(() => {
        console.log('Modelo NSFW pré-carregado.');
        if (window.NSFWAlert) window.NSFWAlert.clear('profileNsfwAlert');
      })
      .catch(err => {
        console.warn('Falha ao pré-carregar modelo NSFW:', err);
        if (window.NSFWAlert) window.NSFWAlert.showError('profileNsfwAlert', 'Falha ao pré-carregar modelo.');
      });
  } catch (e) { console.warn('Erro no preloader NSFW:', e); }
});
</script>
</body>
</html>
