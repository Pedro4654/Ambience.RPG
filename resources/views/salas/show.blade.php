<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $sala->nome }} - Ambience RPG</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sala-header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            margin: 20px;
            padding: 30px;
        }
        
        .websocket-status {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            z-index: 1050;
            animation: pulse 2s infinite;
        }
        
        .websocket-status.connecting {
            background: #ffc107;
            color: #212529;
        }
        
        .websocket-status.disconnected {
            background: #dc3545;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .participante-card {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .participante-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        
        .papel-mestre { border-left: 4px solid #dc3545; }
        .papel-admin_sala { border-left: 4px solid #ffc107; }
        .papel-membro { border-left: 4px solid #6c757d; }
        
        .btn-primary-custom {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .websocket-test {
            background: rgba(40, 167, 69, 0.1);
            border: 2px solid #28a745;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .websocket-test.error {
            background: rgba(220, 53, 69, 0.1);
            border-color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- WebSocket Status Indicator -->
    <div id="websocketStatus" class="websocket-status">
        <i class="fas fa-wifi"></i> 
        <span id="wsStatusText">Conectado</span>
    </div>

    <div class="sala-header animate__animated animate__fadeIn">
        <!-- Header da Sala -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h1 class="display-6 fw-bold text-primary mb-2">
                            <i class="fas fa-dungeon me-2"></i>{{ $sala->nome }}
                        </h1>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="badge bg-primary fs-6">ID: #{{ $sala->id }}</span>
                            @if($sala->tipo === 'publica')
                                <span class="badge bg-success fs-6">🌍 Pública</span>
                            @elseif($sala->tipo === 'privada')  
                                <span class="badge bg-danger fs-6">🔒 Privada</span>
                            @else
                                <span class="badge bg-warning text-dark fs-6">📧 Apenas Convite</span>
                            @endif
                            <span class="badge bg-info fs-6">
                                👥 {{ $sala->participantes->count() }}/{{ $sala->max_participantes }}
                            </span>
                            <span class="badge bg-secondary fs-6">
                                ⚔️ {{ ucfirst($meu_papel) }}
                            </span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="/salas" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar às Salas
                        </a>
                        @if($meu_papel !== 'mestre' && $sala->criador_id !== auth()->id())
                        <button class="btn btn-outline-danger" onclick="sairSala()">
                            <i class="fas fa-sign-out-alt me-2"></i>Sair da Sala
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Descrição da Sala -->
        @if($sala->descricao)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-light border-0 shadow-sm">
                    <h5><i class="fas fa-info-circle me-2"></i>Descrição</h5>
                    <p class="mb-0">{{ $sala->descricao }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Teste WebSocket -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="websocket-test" id="websocketTest">
                    <h5><i class="fas fa-broadcast-tower me-2"></i>Status de Conexão em Tempo Real</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Status:</strong>
                            <span id="wsConnected" class="text-success">✅ Conectado</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Ping:</strong>
                            <span id="wsPing">{{ $websocket_status['ping'] ?? 'N/A' }}ms</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Servidor:</strong>
                            <span id="wsServer">{{ $websocket_status['server'] ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Última Verificação:</strong>
                            <span id="wsLastCheck">Agora</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            Sistema preparado para comunicação em tempo real via WebSocket
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participantes da Sala -->
        <div class="row">
            <div class="col-md-6">
                <h3><i class="fas fa-users me-2"></i>Participantes Ativos</h3>
                <div id="participantesContainer">
                    @foreach($sala->participantes as $participante)
                    <div class="participante-card papel-{{ $participante->papel }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">
                                    @if($participante->papel === 'mestre')
                                        <i class="fas fa-crown text-warning me-1"></i>
                                    @elseif($participante->papel === 'admin_sala')
                                        <i class="fas fa-star text-warning me-1"></i>
                                    @else
                                        <i class="fas fa-user text-muted me-1"></i>
                                    @endif
                                    {{ $participante->usuario->username }}
                                </h6>
                                <small class="text-muted">
                                    {{ ucfirst(str_replace('_', ' ', $participante->papel)) }}
                                    @if($participante->usuario_id === $sala->criador_id)
                                        • Criador
                                    @endif
                                </small>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">
                                    Online
                                </small>
                                <div class="badge bg-success">●</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-6">
                <h3><i class="fas fa-cog me-2"></i>Minhas Permissões</h3>
                @if($minhas_permissoes)
                <div class="alert alert-light">
                    <div class="row">
                        <div class="col-6">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" 
                                       {{ $minhas_permissoes->pode_criar_conteudo ? 'checked' : '' }} disabled>
                                Criar Conteúdo
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" 
                                       {{ $minhas_permissoes->pode_editar_grid ? 'checked' : '' }} disabled>
                                Editar Grid
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" 
                                       {{ $minhas_permissoes->pode_iniciar_sessao ? 'checked' : '' }} disabled>
                                Iniciar Sessão
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" 
                                       {{ $minhas_permissoes->pode_moderar_chat ? 'checked' : '' }} disabled>
                                Moderar Chat
                            </label>
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" 
                                       {{ $minhas_permissoes->pode_convidar_usuarios ? 'checked' : '' }} disabled>
                                Convidar Usuários
                            </label>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Informações da Sala -->
                <h3><i class="fas fa-info me-2"></i>Informações</h3>
                <div class="alert alert-light">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <strong>Criador:</strong> {{ $sala->criador->username ?? 'N/A' }}
                        </div>
                        <div class="col-12 mb-2">
                            <strong>Criada em:</strong> 
                            {{ \Carbon\Carbon::parse($sala->data_criacao)->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-12 mb-2">
                            <strong>Última atividade:</strong> 
                            {{ \Carbon\Carbon::parse($sala->data_criacao)->diffForHumans() }}
                        </div>
                        <div class="col-12">
                            <strong>Status:</strong> 
                            <span class="badge bg-success">{{ $sala->ativa ? 'Ativa' : 'Inativa' }}</span>
                        </div>
                    </div>
                </div>

                @if($minhas_permissoes && $minhas_permissoes->pode_convidar_usuarios)
                <button class="btn btn-primary-custom w-100" onclick="mostrarModalConvite()">
                    <i class="fas fa-user-plus me-2"></i>Convidar Usuário
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Convidar Usuário -->
    <div class="modal fade" id="modalConvidarUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>Convidar Usuário
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formConvidarUsuario">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="emailDestinatario" class="form-label">Email do Usuário</label>
                            <input type="email" class="form-control" id="emailDestinatario" 
                                   name="destinatario_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="expiracaoHoras" class="form-label">Expira em (horas)</label>
                            <select class="form-select" id="expiracaoHoras" name="expira_em_horas">
                                <option value="24">24 horas</option>
                                <option value="72" selected>3 dias</option>
                                <option value="168">1 semana</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Enviar Convite</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Configuração CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Classe para gerenciar a sala
        class GerenciadorSala {
            constructor() {
                this.salaId = {{ $sala->id }};
                this.init();
                this.startWebSocketSimulation();
            }

            init() {
                console.log('🎲 Sala #' + this.salaId + ' inicializada');
                this.bindEvents();
            }

            bindEvents() {
                $('#formConvidarUsuario').submit((e) => {
                    e.preventDefault();
                    this.enviarConvite();
                });
            }

            // Simular WebSocket para demonstração
            startWebSocketSimulation() {
                setInterval(() => {
                    this.updateWebSocketStatus();
                }, 5000);
            }

            updateWebSocketStatus() {
                const status = ['Conectado', 'Reconectando...', 'Conectado'];
                const classes = ['', 'connecting', ''];
                const icons = ['fas fa-wifi', 'fas fa-spinner fa-spin', 'fas fa-wifi'];
                
                const randomIndex = Math.floor(Math.random() * status.length);
                const ping = Math.floor(Math.random() * 50) + 10;
                
                const wsElement = $('#websocketStatus');
                const textElement = $('#wsStatusText');
                const iconElement = wsElement.find('i');
                
                // Remover classes antigas
                wsElement.removeClass('connecting disconnected');
                if (classes[randomIndex]) {
                    wsElement.addClass(classes[randomIndex]);
                }
                
                // Atualizar ícone e texto
                iconElement.attr('class', icons[randomIndex]);
                textElement.text(status[randomIndex]);
                
                // Atualizar ping
                $('#wsPing').text(ping + 'ms');
                $('#wsLastCheck').text('Agora');
                
                console.log('🌐 WebSocket Status:', status[randomIndex], ping + 'ms');
            }

            // Enviar convite
            enviarConvite() {
                const formData = $('#formConvidarUsuario').serialize();
                
                $.post(`/salas/${this.salaId}/convidar`, formData)
                    .done((response) => {
                        if (response.success) {
                            this.showAlert(response.message, 'success');
                            $('#modalConvidarUsuario').modal('hide');
                            $('#formConvidarUsuario')[0].reset();
                        } else {
                            this.showAlert(response.message, 'danger');
                        }
                    })
                    .fail((xhr) => {
                        console.error('❌ Erro ao enviar convite:', xhr);
                        this.showAlert('Erro ao enviar convite. Tente novamente.', 'danger');
                    });
            }

            // Mostrar alerta
            showAlert(message, type = 'info') {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                         style="top: 80px; right: 20px; z-index: 1051; max-width: 400px;" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('body').append(alertHtml);
                
                // Auto-dismiss após 5 segundos
                setTimeout(() => {
                    $('.alert').first().alert('close');
                }, 5000);
            }
        }

        // Funções globais
        function mostrarModalConvite() {
            $('#modalConvidarUsuario').modal('show');
        }

        function sairSala() {
            if (confirm('Tem certeza que deseja sair desta sala?')) {
                $.post(`/salas/{{ $sala->id }}/sair`)
                    .done((response) => {
                        if (response.success) {
                            alert(response.message);
                            window.location.href = response.redirect_to;
                        } else {
                            alert(response.message);
                        }
                    })
                    .fail(() => {
                        alert('Erro ao sair da sala. Tente novamente.');
                    });
            }
        }

        // Inicializar quando document estiver pronto
        let gerenciador;
        $(document).ready(() => {
            gerenciador = new GerenciadorSala();
        });
    </script>
</body>
</html>