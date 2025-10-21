<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sessao->nome_sessao }} – Sessão de RPG</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Variáveis JS para WebSocket -->
    <script>
        window.SessionId = {{ $sessao->id }};
        window.SalaId = {{ $sessao->sala_id }};
        window.UserId = {{ auth()->id() }};
        window.ParticipantesSessao = @json($idsParticipantes);
    </script>

    <style>
        body { background: #1a1a2e; color: #eee; margin: 0; font-family: 'Segoe UI', sans-serif; }
        .sessao-container { display: flex; flex-direction: column; height: 100vh; }
        .sessao-header { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); padding: 1rem; color: #fff; }
        .sessao-content { flex: 1; display: flex; overflow: hidden; }
        .sessao-main { flex: 1; background: #16213e; border: 2px dashed #667eea; margin: 1rem; border-radius: .5rem; display: flex; align-items: center; justify-content: center; }
        .sessao-sidebar { width: 300px; background: #0f1624; border-left: 1px solid #667eea; display: flex; flex-direction: column; }
        .participante-item { padding: .5rem; border-bottom: 1px solid #667eea22; display: flex; align-items: center; gap: .5rem; }
        .participante-avatar { width: 36px; height: 36px; border-radius: 50%; background: #667eea; display: flex; align-items: center; justify-content: center; }
        .online-indicator { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-left: .5rem; }
        .online-indicator.online  { background: #28a745; }
        .online-indicator.offline { background: #6c757d; }
        .btn-action-group { display: flex; gap: 0.5rem; }
    </style>
</head>
<body>
    <div class="sessao-container">
        <!-- Header -->
        <div class="sessao-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><i class="fa-solid fa-dice-d20 me-2"></i>{{ $sessao->nome_sessao }}</h4>
                <small>
                    ID: {{ $sessao->id }} | 
                    Mestre: <strong>{{ $sessao->mestre->username }}</strong> |
                    Iniciada {{ $sessao->data_inicio->diffForHumans() }}
                </small>
            </div>
            <div class="btn-action-group">
                {{-- TODOS podem voltar para a sala --}}
                <a href="{{ route('salas.show', ['id' => $sessao->sala_id]) }}" class="btn btn-light btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i>Voltar à Sala
                </a>

                {{-- Somente quem tem permissão de INICIAR SESSÃO pode FINALIZAR --}}
                @if($pode_finalizar_sessao)
                <button id="btnFinalizarSessao" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-stop me-1"></i>Finalizar Sessão
                </button>
                @endif
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="sessao-content">
            <!-- Área de Jogo -->
            <div class="sessao-main">
                <div class="text-center text-muted">
                    <i class="fa-solid fa-map-location-dot fa-3x mb-3"></i>
                    <h3>Área de Jogo</h3>
                    <p>Espaço reservado para grid, chat, rolagem de dados e outras funcionalidades.</p>
                    <small class="text-info">
                        <i class="fa-solid fa-info-circle me-1"></i>
                        Componentes de jogo serão implementados aqui
                    </small>
                </div>
            </div>

            <!-- Sidebar de Participantes -->
            <div class="sessao-sidebar">
                <div class="p-3 border-bottom border-secondary">
                    <h6 class="text-white mb-0">
                        <i class="fa-solid fa-users me-2"></i>
                        Participantes ({{ $sessao->participantes->count() }})
                    </h6>
                </div>
                <div class="flex-grow-1 overflow-auto">
                    @foreach($sessao->participantes as $participante)
                        <div class="participante-item" data-usuario-id="{{ $participante->usuario->id }}">
                            <div class="participante-avatar">
                                @if($participante->usuario->avatar)
                                    <img src="{{ $participante->usuario->avatar }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                                @else
                                    <i class="fa-solid fa-user text-white"></i>
                                @endif
                            </div>
                            <div class="d-flex flex-grow-1 justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $participante->usuario->username }}</strong>
                                    @if($participante->usuario_id == $sessao->mestre_id)
                                        <i class="fa-solid fa-crown text-warning ms-1" title="Mestre"></i>
                                    @endif
                                </div>
                                <span class="online-indicator {{ in_array($participante->usuario->id, $idsParticipantes) ? 'online' : 'offline' }}" 
                                      id="status-{{ $participante->usuario->id }}">
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite('resources/js/bootstrap.ts')
    
    <script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('[Sessão] Configurando listeners de WebSocket...');
    
    if (typeof window.Echo === 'undefined') {
        console.error('[Sessão] Echo não disponível!');
        return;
    }

    const salaId = {{ $sessao->sala_id }};
    const sessaoId = {{ $sessao->id }};
    
    console.log(`[Sessão] Conectando ao canal sala.${salaId}`);
    
    // ==================== CONECTAR AO CANAL DA SALA ====================
    const salaChannel = window.Echo.join(`sala.${salaId}`);
    
    // ==================== ESCUTAR EVENTO DE SESSÃO FINALIZADA ====================
    salaChannel.listen('.session.end', function(data) {
        console.log('[Sessão] 🛑 SESSÃO FINALIZADA! Evento recebido:', data);
        
        if (!data || !data.redirect_to) {
            console.error('[Sessão] Dados do evento inválidos:', data);
            return;
        }
        
        // Verificar se é a sessão atual
        if (data.sessao_id === sessaoId) {
            console.log('[Sessão] Redirecionando para:', data.redirect_to);
            window.location.href = data.redirect_to;
        }
    });
    
    salaChannel.error((error) => {
        console.error('[Sessão] Erro no canal:', error);
    });
    
    console.log('[Sessão] ✅ Listener de finalização configurado!');
});

    // ==================== WEBSOCKET - ESCUTAR EVENTOS ====================
    
    // Escutar evento de sessão finalizada
    if (window.Echo) {
        const salaChannel = window.Echo.channel(`sala.${window.SalaId}`);
        
        // Quando a sessão for finalizada, redirecionar todos para a sala
        salaChannel.listen('.session.end', (data) => {
            console.log('Sessão finalizada via WebSocket:', data);
            
            // Mostrar notificação
            alert('A sessão foi finalizada pelo mestre.');
            
            // Redirecionar para a sala
            if (data.redirect_to) {
                window.location.href = data.redirect_to;
            }
        });

        // Escutar novos participantes entrando
        salaChannel.listen('.user.joined.session', (data) => {
            console.log('Usuário entrou na sessão:', data);
            
            // Atualizar indicador de status se o elemento existir
            const statusElement = document.getElementById(`status-${data.usuario_id}`);
            if (statusElement) {
                statusElement.classList.remove('offline');
                statusElement.classList.add('online');
            }
            
            // Opcional: adicionar notificação visual
            const notif = document.createElement('div');
            notif.className = 'alert alert-info alert-dismissible fade show position-fixed top-0 end-0 m-3';
            notif.style.zIndex = '9999';
            notif.innerHTML = `
                <i class="fa-solid fa-user-plus me-2"></i>
                <strong>${data.username || 'Um jogador'}</strong> entrou na sessão!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(notif);
            
            // Auto-remover após 5 segundos
            setTimeout(() => {
                notif.remove();
            }, 5000);
        });
    }

    // ==================== FINALIZAR SESSÃO ====================
    
    const btnFinalizar = document.getElementById('btnFinalizarSessao');
    
    if (btnFinalizar) {
        btnFinalizar.addEventListener('click', async () => {
            // Confirmação dupla
            const confirmacao = confirm('⚠️ Tem certeza que deseja FINALIZAR esta sessão?\n\nTodos os participantes serão redirecionados para a sala.\n\nEsta ação não pode ser desfeita.');
            
            if (!confirmacao) return;

            // Desabilitar botão e mostrar loading
            btnFinalizar.disabled = true;
            btnFinalizar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Finalizando...';

            try {
                const response = await fetch(`/sessoes/{{ $sessao->id }}/finalizar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Sucesso - Redirecionar
                    window.location.href = data.redirect_to;
                } else {
                    // Erro retornado pelo servidor
                    alert(`❌ Erro: ${data.message || 'Não foi possível finalizar a sessão.'}`);
                    
                    // Restaurar botão
                    btnFinalizar.disabled = false;
                    btnFinalizar.innerHTML = '<i class="fa-solid fa-stop me-1"></i>Finalizar Sessão';
                }
            } catch (error) {
                console.error('Erro ao finalizar sessão:', error);
                alert('❌ Erro de conexão. Verifique sua internet e tente novamente.');
                
                // Restaurar botão
                btnFinalizar.disabled = false;
                btnFinalizar.innerHTML = '<i class="fa-solid fa-stop me-1"></i>Finalizar Sessão';
            }
        });
    }
    </script>
</body>
</html>
