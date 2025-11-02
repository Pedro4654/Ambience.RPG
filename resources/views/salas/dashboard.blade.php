<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Salas - Ambience RPG</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <style>
        /* Estilos personalizados para o sistema de salas */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sala-banner-mini {
    width: 100%;
    height: 110px; /* reduzido para manter o card compacto */
    background-position: center center;
    background-size: cover;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    margin-bottom: 10px;
  }
  .sala-banner-mini .banner-edit-btn {
    position: absolute;
    right: 8px;
    top: 8px;
    z-index: 10;
  }
  .sala-banner-mini .banner-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.95);
    font-weight: 700;
    font-size: 16px;
    text-align: center;
    padding: 0 8px;
  }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            margin: 20px;
            padding: 30px;
        }

        .sala-card {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .sala-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .sala-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px 20px;
            margin: -15px -20px 15px -20px;
        }

        .tipo-badge {
            font-size: 0.85em;
            padding: 5px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .tipo-publica {
            background-color: #28a745;
        }

        .tipo-privada {
            background-color: #dc3545;
        }

        .tipo-apenas_convite {
            background-color: #ffc107;
            color: #212529;
        }

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

        .btn-success-custom {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
        }

        .section-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .modal-header-custom {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .websocket-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            z-index: 1050;
            animation: pulse 2s infinite;
        }

        .websocket-indicator.disconnected {
            background: #dc3545;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .alert-custom {
            border: none;
            border-radius: 10px;
            padding: 15px 20px;
        }

        .senha-info {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .sala-info-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <!-- Indicador WebSocket -->
    <div id="websocketIndicator" class="websocket-indicator">
        <i class="fas fa-wifi"></i> WebSocket: Conectado
    </div>

    <div class="main-container animate__animated animate__fadeIn">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h1 class="display-6 fw-bold text-primary mb-2">
                            <i class="fas fa-dungeon me-2"></i>Sistema de Salas
                        </h1>
                        <p class="text-muted mb-0">Gerencie suas aventuras de RPG em tempo real</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-success-custom" data-bs-toggle="modal" data-bs-target="#modalEntrarSala">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar em Sala
                        </button>
                        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalCriarSala">
                            <i class="fas fa-plus me-2"></i>Criar Nova Sala
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        <div id="alertContainer"></div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
        </div>

        <!-- Minhas Salas -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="section-title">
                    <i class="fas fa-home me-2"></i>Minhas Salas
                </h2>
                <div id="minhasSalasContainer" class="row">
                    <!-- Conteúdo será carregado via AJAX -->
                </div>
            </div>
        </div>

        <!-- Salas Públicas -->
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">
                    <i class="fas fa-globe me-2"></i>Salas Públicas Disponíveis
                </h2>
                <div id="salasPublicasContainer" class="row">
                    <!-- Conteúdo será carregado via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Criar Sala -->
    <div class="modal fade" id="modalCriarSala" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Criar Nova Sala
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formCriarSala">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nomeSala" class="form-label">Nome da Sala *</label>
                                    <input type="text" class="form-control" id="nomeSala" name="nome" required
                                        placeholder="Ex: Aventura em Pedra Branca">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tipoSala" class="form-label">Tipo da Sala *</label>
                                    <select class="form-select" id="tipoSala" name="tipo" required>
                                        <option value="publica">🌍 Pública</option>
                                        <option value="privada">🔒 Privada</option>
                                        <option value="apenas_convite">📧 Apenas Convite</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricaoSala" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricaoSala" name="descricao" rows="3"
                                placeholder="Descreva sua aventura, sistema de jogo, nível dos jogadores..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3" id="senhaContainer" style="display: none;">
                                    <label for="senhaSala" class="form-label">Senha da Sala</label>
                                    <input type="password" class="form-control" id="senhaSala" name="senha"
                                        placeholder="Mínimo 4 caracteres">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="maxParticipantes" class="form-label">Máx. Participantes</label>
                                    <input type="number" class="form-control" id="maxParticipantes"
                                        name="max_participantes" value="50" min="2" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="imagemSala" class="form-label">URL da Imagem</label>
                            <input type="url" class="form-control" id="imagemSala" name="imagem_url"
                                placeholder="https://exemplo.com/imagem.jpg">
                            <div class="form-text">Deixe em branco para usar imagem padrão</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-plus me-2"></i>Criar Sala
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Entrar em Sala -->
    <div class="modal fade" id="modalEntrarSala" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">
                        <i class="fas fa-sign-in-alt me-2"></i>Entrar em Sala
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEntrarSala">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="idSalaEntrar" class="form-label">ID da Sala *</label>
                            <input type="number" class="form-control" id="idSalaEntrar" name="sala_id" required
                                placeholder="Digite o ID numérico da sala">
                        </div>

                        <!-- Container para informações da sala -->
                        <div id="infoSalaContainer" style="display: none;">
                            <div class="sala-info-card">
                                <h6><i class="fas fa-info-circle me-2"></i>Informações da Sala</h6>
                                <div id="infoSalaContent"></div>
                            </div>
                        </div>

                        <!-- Container para senha -->
                        <div id="senhaEntrarContainer" style="display: none;">
                            <div class="senha-info">
                                <h6><i class="fas fa-lock me-2 text-warning"></i>Sala Privada</h6>
                                <p class="mb-2">Esta sala é privada. Digite a senha para continuar:</p>
                                <input type="password" class="form-control" id="senhaEntrar" name="senha"
                                    placeholder="Digite a senha da sala">
                            </div>
                        </div>

                        <div class="alert alert-info alert-custom">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Dica:</strong> Digite o ID da sala e clique em "Verificar Sala" primeiro.
                            Se for uma sala privada, você poderá inserir a senha antes de entrar.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnVerificarSala" class="btn btn-info">
                            <i class="fas fa-search me-2"></i>Verificar Sala
                        </button>
                        <button type="submit" id="btnEntrarSala" class="btn btn-success-custom" style="display: none;">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar na Sala
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Configuração CSRF para AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const AUTH_ID = {{ (int) $userId }};

        // Classe principal do Sistema de Salas
        class SistemaSalas {
            constructor() {
                this.init();
                this.loadSalas();
                this.bindEvents();
                this.setupWebSocketIndicator();
            }

            init() {
                console.log('🎮 Sistema de Salas inicializado');
            }

            // Carregar dados das salas via AJAX
            loadSalas() {
                this.showLoading();

                $.ajax({
                        url: '/api/salas/data', // NOVA URL
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .done(data => {
                        console.log('📊 Dados carregados:', data);
                        if (data.success) {
                            this.renderMinhasSalas(data.minhas_salas);
                            this.renderSalasPublicas(data.salas_publicas);
                        }
                        this.hideLoading();
                    })
                    .fail(xhr => {
                        console.error('❌ Erro ao carregar salas:', xhr);
                        this.showAlert('Erro ao carregar salas. Tente novamente.', 'danger');
                        this.hideLoading();
                    });
            }

            // Renderizar minhas salas
            renderMinhasSalas(salas) {
                const container = $('#minhasSalasContainer');

                if (salas.length === 0) {
                    container.html(`
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="fas fa-home"></i>
                                <h4>Você ainda não participa de nenhuma sala</h4>
                                <p>Crie sua primeira sala ou entre em uma sala pública!</p>
                            </div>
                        </div>
                    `);
                    return;
                }

                let html = '';
                salas.forEach(sala => {
                    html += this.generateSalaCard(sala, true);
                });
                container.html(html);
            }

            // Renderizar salas públicas
            renderSalasPublicas(salas) {
                const container = $('#salasPublicasContainer');

                if (salas.length === 0) {
                    container.html(`
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="fas fa-globe"></i>
                                <h4>Nenhuma sala pública disponível</h4>
                                <p>Seja o primeiro a criar uma sala pública!</p>
                            </div>
                        </div>
                    `);
                    return;
                }

                let html = '';
                salas.forEach(sala => {
                    html += this.generateSalaCard(sala, false);
                });
                container.html(html);
            }

            // Gerar HTML do card da sala
            generateSalaCard(sala, isMyRoom) {
  const participantes = sala.participantes;
  const criador = sala.criador;
  const tipoClass = `tipo-${sala.tipo}`;
  const tipoText = sala.tipo === 'publica' ? 'Pública' : (sala.tipo === 'privada' ? 'Privada' : 'Convite');

  const isCreator = criador && parseInt(criador.id) === parseInt(AUTH_ID);
  const bannerStyle = sala.banner_url
    ? `background-image:url('${sala.banner_url}');`
    : `background-color:${sala.banner_color || '#6c757d'};`;

  const bannerMini = `
    <div class="sala-banner-mini" style="${bannerStyle}" data-sala-id="${sala.id}">
      ${isCreator ? `
        <div class="banner-edit-btn">
          <button class="btn btn-sm btn-light open-banner-editor-btn"
                  data-sala-id="${sala.id}"
                  data-banner-url="${sala.banner_url || ''}"
                  data-banner-color="${sala.banner_color || ''}">
            <i class="fa-solid fa-image me-1"></i>Editar
          </button>
        </div>` : ``}
      ${!sala.banner_url ? `<div class="banner-fallback">${sala.nome}</div>` : ``}
    </div>`;

  const actionButton = isMyRoom
    ? `<a href="/salas/${sala.id}" class="btn btn-primary-custom btn-sm">
         <i class="fas fa-play me-1"></i>Entrar
       </a>`
    : `<button class="btn btn-success-custom btn-sm" onclick="sistema.entrarSalaRapida(${sala.id})">
         <i class="fas fa-sign-in-alt me-1"></i>Juntar-se
       </button>`;

  return `
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="sala-card animate__animated animate__fadeInUp">
        ${bannerMini}
        <div class="px-3 pt-1 pb-2">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">${sala.nome}</h5>
            <span class="tipo-badge ${tipoClass}">${tipoText}</span>
          </div>
        </div>
        <div class="card-body">
          <p class="text-muted mb-3">${sala.descricao || 'Sem descrição'}</p>
          <div class="row text-center mb-3">
            <div class="col-4">
              <small class="text-muted d-block">ID</small>
              <strong>${sala.id}</strong>
            </div>
            <div class="col-4">
              <small class="text-muted d-block">Participantes</small>
              <strong>${participantes.length}/${sala.max_participantes}</strong>
            </div>
            <div class="col-4">
              <small class="text-muted d-block">Criador</small>
              <strong>${(criador && criador.username) || 'N/A'}</strong>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
              <i class="fas fa-clock me-1"></i> Criada ${this.formatDate(sala.data_criacao)}
            </small>
            ${actionButton}
          </div>
        </div>
      </div>
    </div>`;
}

            // Formatar data
            formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            // Eventos
            bindEvents() {
                // Mostrar/ocultar campo senha baseado no tipo
                $('#tipoSala').change(e => {
                    const senhaContainer = $('#senhaContainer');
                    if (e.target.value === 'privada') {
                        senhaContainer.show();
                        $('#senhaSala').attr('required', true);
                    } else {
                        senhaContainer.hide();
                        $('#senhaSala').attr('required', false);
                    }
                });

                // Submit criar sala
                $('#formCriarSala').submit(e => {
                    e.preventDefault();
                    this.criarSala();
                });

                // Submit entrar em sala
                $('#formEntrarSala').submit(e => {
                    e.preventDefault();
                    this.entrarSala();
                });

                // Verificar sala antes de entrar
                $('#btnVerificarSala').click(() => {
                    this.verificarSala();
                });

                // Limpar formulário quando modal fechar
                $('#modalEntrarSala').on('hidden.bs.modal', () => {
                    this.resetFormEntrarSala();
                });
            }

            // Verificar informações da sala
            verificarSala() {
                const salaId = $('#idSalaEntrar').val();

                if (!salaId) {
                    this.showAlert('Digite o ID da sala primeiro.', 'warning');
                    return;
                }

                const btnVerificar = $('#btnVerificarSala');
                const btnEntrar = $('#btnEntrarSala');
                const originalText = btnVerificar.html();

                btnVerificar.html('<i class="fas fa-spinner fa-spin me-2"></i>Verificando...');
                btnVerificar.prop('disabled', true);

                $.get(`/salas/${salaId}/info`)
                    .done(response => {
                        if (response.success) {
                            this.mostrarInfoSala(response.sala);
                            btnEntrar.show();
                            btnVerificar.html('<i class="fas fa-check me-2"></i>Verificado');
                        }
                    })
                    .fail(xhr => {
                        console.error('❌ Erro ao verificar sala:', xhr);
                        const errorMsg = xhr.responseJSON?.message || 'Erro ao verificar sala.';
                        this.showAlert(errorMsg, 'danger');

                        btnVerificar.html(originalText);
                        btnVerificar.prop('disabled', false);
                    });
            }

            // Mostrar informações da sala
            mostrarInfoSala(sala) {
                const infoContainer = $('#infoSalaContainer');
                const senhaContainer = $('#senhaEntrarContainer');

                let infoHtml = `
                    <div class="row">
                        <div class="col-6">
                            <strong>Nome:</strong><br>
                            <span class="text-muted">${sala.nome}</span>
                        </div>
                        <div class="col-6">
                            <strong>Tipo:</strong><br>
                            <span class="text-muted">${this.getTipoText(sala.tipo)}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <strong>Participantes:</strong><br>
                            <span class="text-muted">${sala.participantes_atuais}/${sala.max_participantes}</span>
                        </div>
                        <div class="col-6">
                            <strong>Status:</strong><br>
                            <span class="badge bg-success">Ativa</span>
                        </div>
                    </div>
                `;

                $('#infoSalaContent').html(infoHtml);
                infoContainer.show();

                // Mostrar campo senha se necessário
                if (sala.precisa_senha) {
                    senhaContainer.show();
                    $('#senhaEntrar').attr('required', true);
                } else {
                    senhaContainer.hide();
                    $('#senhaEntrar').attr('required', false);
                }

                // Verificar se é apenas convite
                if (sala.apenas_convite) {
                    this.showAlert('Esta sala é apenas por convite. Você precisa ser convidado.', 'warning');
                }
            }

            // Obter texto do tipo
            getTipoText(tipo) {
                const tipos = {
                    'publica': '🌍 Pública',
                    'privada': '🔒 Privada',
                    'apenas_convite': '📧 Apenas Convite'
                };
                return tipos[tipo] || tipo;
            }

            // Reset form entrar sala
            resetFormEntrarSala() {
                $('#formEntrarSala')[0].reset();
                $('#infoSalaContainer').hide();
                $('#senhaEntrarContainer').hide();
                $('#btnEntrarSala').hide();
                $('#btnVerificarSala').html('<i class="fas fa-search me-2"></i>Verificar Sala').prop('disabled', false);
                $('#senhaEntrar').attr('required', false);
            }

            // Criar nova sala
            criarSala() {
                const formData = new FormData($('#formCriarSala')[0]);
                const data = Object.fromEntries(formData);

                $.post('/salas', data)
                    .done(response => {
                        if (response.success) {
                            this.showAlert(response.message, 'success');
                            $('#modalCriarSala').modal('hide');
                            $('#formCriarSala')[0].reset();
                            this.loadSalas(); // Recarregar salas

                            // Redirecionar para a sala criada após 2 segundos
                            setTimeout(() => {
                                window.location.href = `/salas/${response.sala.id}`;
                            }, 2000);
                        } else {
                            this.showAlert(response.message, 'danger');
                        }
                    })
                    .fail(xhr => {
                        console.error('❌ Erro ao criar sala:', xhr);
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let errorMsg = 'Erros de validação:<br>';
                            Object.values(errors).forEach(error => {
                                errorMsg += `• ${error[0]}<br>`;
                            });
                            this.showAlert(errorMsg, 'danger');
                        } else {
                            this.showAlert('Erro interno. Tente novamente.', 'danger');
                        }
                    });
            }

            // Entrar em sala
            entrarSala() {
                const formData = new FormData($('#formEntrarSala')[0]);
                const data = Object.fromEntries(formData);

                $.post('/salas/entrar', data)
                    .done(response => {
                        if (response.success) {
                            this.showAlert(response.message, 'success');
                            $('#modalEntrarSala').modal('hide');
                            this.resetFormEntrarSala();

                            // Redirecionar para a sala após 1.5 segundos
                            setTimeout(() => {
                                window.location.href = response.redirect_to;
                            }, 1500);
                        } else {
                            this.showAlert(response.message, 'warning');
                        }
                    })
                    .fail(xhr => {
                        console.error('❌ Erro ao entrar na sala:', xhr);
                        const errorMsg = xhr.responseJSON?.message || 'Erro interno. Tente novamente.';
                        this.showAlert(errorMsg, 'danger');
                    });
            }

            // Entrar em sala rapidamente (para salas públicas)
            entrarSalaRapida(salaId) {
                console.log('Tentando entrar na sala:', salaId);

                $.ajax({
                        url: '/salas/entrar',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        data: {
                            sala_id: salaId
                        },
                        dataType: 'json', // Força interpretação como JSON
                        timeout: 10000 // 10 segundos de timeout
                    })
                    .done((response) => {
                        console.log('Resposta recebida:', response);

                        if (response && response.success) {
                            this.showAlert(response.message || 'Entrada realizada com sucesso!', 'success');

                            // Aguardar um pouco antes do redirecionamento
                            setTimeout(() => {
                                if (response.redirect_to) {
                                    window.location.href = response.redirect_to;
                                } else {
                                    // Fallback: recarregar página atual
                                    location.reload();
                                }
                            }, 1500);
                        } else {
                            // Sucesso HTTP mas falha lógica
                            console.warn('Resposta de sucesso mas com falha lógica:', response);
                            this.showAlert(response.message || 'Erro inesperado ao entrar na sala.', 'warning');
                        }
                    })
                    .fail((xhr, status, error) => {
                        console.error('Erro na requisição AJAX:', {
                            xhr: xhr,
                            status: status,
                            error: error,
                            responseText: xhr.responseText,
                            statusCode: xhr.status
                        });

                        let errorMsg = 'Erro ao entrar na sala. Tente novamente.';

                        // Tentar interpretar resposta de erro
                        try {
                            if (xhr.responseJSON) {
                                errorMsg = xhr.responseJSON.message || errorMsg;
                            } else if (xhr.responseText) {
                                // Tentar fazer parse manual do JSON
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMsg = response.message;
                                }
                            }
                        } catch (e) {
                            console.warn('Não foi possível interpretar resposta de erro:', e);
                        }

                        // Tratar códigos de status específicos
                        if (xhr.status === 401) {
                            errorMsg = 'Sessão expirada. Faça login novamente.';
                            setTimeout(() => {
                                window.location.href = '/login';
                            }, 2000);
                        } else if (xhr.status === 422) {
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                            }
                        } else if (xhr.status === 419) {
                            errorMsg = 'Token CSRF inválido. Recarregando página...';
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else if (xhr.status === 0) {
                            errorMsg = 'Problema de conexão. Verifique sua internet.';
                        }

                        this.showAlert(errorMsg, 'danger');
                    })
                    .always(() => {
                        console.log('Requisição finalizada');
                    });
            }

            // Mostrar alerta
            showAlert(message, type = 'info') {
                const alertHtml = `
                    <div class="alert alert-${type} alert-custom alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('#alertContainer').prepend(alertHtml);

                // Auto-dismiss após 5 segundos
                setTimeout(() => {
                    $('.alert').first().alert('close');
                }, 5000);
            }

            // Loading
            showLoading() {
                $('#loadingSpinner').show();
            }

            hideLoading() {
                $('#loadingSpinner').hide();
            }

            // Simular WebSocket (preparação para implementação real)
            setupWebSocketIndicator() {
                const indicator = $('#websocketIndicator');

                // Simular status de conexão
                let connected = true;
                setInterval(() => {
                    connected = !connected;
                    if (connected) {
                        indicator.removeClass('disconnected')
                            .html('<i class="fas fa-wifi"></i> WebSocket: Conectado');
                    } else {
                        indicator.addClass('disconnected')
                            .html('<i class="fas fa-wifi"></i> WebSocket: Reconectando...');
                    }
                }, 10000); // Alterna a cada 10 segundos para demo
            }
        }

        document.body.addEventListener('click', function(e) {
  const btn = e.target.closest('.open-banner-editor-btn');
  if (!btn) return;

  const salaId = btn.getAttribute('data-sala-id');
  const bannerUrl = btn.getAttribute('data-banner-url') || null;
  const bannerColor = btn.getAttribute('data-banner-color') || null;

  if (window.openBannerEditor) {
    window.openBannerEditor(salaId, bannerUrl, bannerColor);
  } else {
    console.error('openBannerEditor indisponível');
  }
});

        // Inicializar sistema quando document estiver pronto
        let sistema;
        $(document).ready(() => {
            sistema = new SistemaSalas();
        });
    </script>
    @include('partials.banner-editor')
</body>

</html>