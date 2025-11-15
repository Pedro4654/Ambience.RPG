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
            height: 110px;
            background-position: center center;
            background-size: cover;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            margin-bottom: 10px;
        }

        .input-warn {
            border: 2px solid #e0556b !important;
            background: #fff6f7;
        }

        .moderation-warning {
            display: none;
            color: #e0556b;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .moderation-warning.show {
            display: block;
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
            color: rgba(255, 255, 255, 0.95);
            font-weight: 700;
            font-size: 16px;
            text-align: center;
            padding: 0 8px;
        }

        .sala-profile-photo-mini {
            width: 65px;
            height: 65px;
            background-position: center center;
            background-size: cover;
            border-radius: 50%;
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: visible;
        }

        .sala-profile-photo-mini .photo-edit-btn {
            position: absolute;
            bottom: -2px;
            right: -2px;
            z-index: 10;
        }

        /* Seção de salas desativadas */
#secaoSalasDesativadas {
    border-top: 3px solid #dc3545;
    padding-top: 30px;
}

#secaoSalasDesativadas .section-title {
    color: #dc3545;
}

#secaoSalasDesativadas .section-title::after {
    background: linear-gradient(45deg, #dc3545, #c82333);
}

.btn-outline-danger.btn-ver-mais {
    background: transparent;
    border: 2px solid #dc3545;
    color: #dc3545;
}

.btn-outline-danger.btn-ver-mais:hover {
    background: #dc3545;
    color: white;
}

.status-inactive {
    opacity: 0.7;
    filter: grayscale(0.5);
    border: 2px solid #dc3545;
}

        .sala-profile-photo-mini .photo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 700;
            font-size: 24px;
            text-transform: uppercase;
            border-radius: 50%;
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

        /* Modal de motivo de desativação */
#modalMotivoDesativacao .modal-header {
    background: linear-gradient(45deg, #dc3545, #c82333);
}

/* Info de desativação nos cards */
.desativacao-info {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 10px;
    margin-top: 10px;
    font-size: 0.85rem;
}

.desativacao-info .badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.motivo-badge {
    background: #dc3545;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 8px;
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

        /* Estilos para busca */
        .search-box {
            position: relative;
            margin-bottom: 20px;
        }

        .search-box input {
            padding-right: 40px;
        }

        .search-box .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        /* Botão Ver Mais */
        .btn-ver-mais {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
        }

        .btn-ver-mais:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* Badge de Staff */
        .staff-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a6f);
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Alerta de salas desativadas */
#alertSalasDesativadas {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 2px solid #ffc107;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
}

#alertSalasDesativadas .alert-heading {
    color: #856404;
}

#alertSalasDesativadas i.fa-exclamation-triangle {
    color: #ffc107;
    animation: pulse 2s infinite;
}

/* Seção de minhas salas desativadas */
#secaoMinhasSalasDesativadas .alert {
    background: #fff3cd;
    border: 2px solid #ffc107;
}

#secaoMinhasSalasDesativadas .sala-card {
    border: 2px solid #dc3545;
}

/* Badge de sala desativada pelo criador */
.badge-desativada-creator {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #dc3545;
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

/* Badge de acesso staff */
.badge-staff-access {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(45deg, #ff6b6b, #ee5a6f);
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 11;
}

        /* Dropdown de ações staff */
        .staff-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .status-inactive {
            opacity: 0.6;
            filter: grayscale(0.3);
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
                            @if($isStaff)
                                <span class="staff-badge">
                                    <i class="fas fa-shield-alt me-1"></i>STAFF
                                </span>
                            @endif
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

         <!-- NOVO: Alerta de Salas Desativadas -->
        @if(isset($minhasSalasDesativadasCount) && $minhasSalasDesativadasCount > 0)
        <div class="alert alert-warning alert-dismissible fade show animate__animated animate__fadeIn" 
             role="alert" id="alertSalasDesativadas">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">
                        <i class="fas fa-power-off me-2"></i>Atenção: Salas Desativadas
                    </h5>
                    <p class="mb-2">
                        Você possui <strong>{{ $minhasSalasDesativadasCount }}</strong> 
                        {{ $minhasSalasDesativadasCount == 1 ? 'sala desativada' : 'salas desativadas' }} 
                        pela Moderação.
                    </p>
                    <button class="btn btn-sm btn-warning" onclick="sistema.mostrarMinhasSalasDesativadas()">
                        <i class="fas fa-eye me-1"></i>Ver Salas Desativadas
                    </button>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="section-title mb-0">
                        <i class="fas fa-home me-2"></i>Minhas Salas
                    </h2>
                    <div class="search-box" style="width: 300px;">
                        <input type="text" 
                               class="form-control" 
                               id="searchMinhasSalas" 
                               placeholder="Buscar minhas salas...">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
                <div id="minhasSalasContainer" class="row">
                    <!-- Conteúdo será carregado via AJAX -->
                </div>
                <div id="minhasSalasVerMais" style="display: none;">
                    <button class="btn btn-ver-mais" onclick="sistema.carregarMaisMinhasSalas()">
                        <i class="fas fa-chevron-down me-2"></i>Ver Mais
                    </button>
                </div>
            </div>
        </div>

        <!-- NOVO: Minhas Salas Desativadas -->
    <div class="row mb-5" id="secaoMinhasSalasDesativadas" style="display: none;">
        <div class="col-12">
            <div class="alert alert-warning">
                <h4 class="alert-heading">
                    <i class="fas fa-power-off me-2"></i>Suas Salas Desativadas
                </h4>
                <p class="mb-0">
                    Estas salas foram desativadas pela Moderação e não podem mais receber novos participantes. 
                    Entre em contato com a equipe se tiver dúvidas.
                </p>
            </div>
            <div id="minhasSalasDesativadasContainer" class="row">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
            <button class="btn btn-secondary mt-3" onclick="sistema.ocultarMinhasSalasDesativadas()">
                <i class="fas fa-times me-2"></i>Ocultar
            </button>
        </div>
    </div>

        <!-- Salas Públicas -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="section-title mb-0">
                        <i class="fas fa-globe me-2"></i>Salas Públicas Disponíveis
                    </h2>
                    <div class="search-box" style="width: 300px;">
                        <input type="text" 
                               class="form-control" 
                               id="searchSalasPublicas" 
                               placeholder="Buscar salas públicas...">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
                <div id="salasPublicasContainer" class="row">
                    <!-- Conteúdo será carregado via AJAX -->
                </div>
                <div id="salasPublicasVerMais" style="display: none;">
                    <button class="btn btn-ver-mais" onclick="sistema.carregarMaisSalasPublicas()">
                        <i class="fas fa-chevron-down me-2"></i>Ver Mais
                    </button>
                </div>
            </div>
        </div>

         @if($isStaff)
    <div class="row mt-5" id="secaoSalasDesativadas">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title mb-0 text-danger">
                    <i class="fas fa-power-off me-2"></i>Salas Desativadas (Staff)
                </h2>
                <div class="search-box" style="width: 300px;">
                    <input type="text" 
                           class="form-control border-danger" 
                           id="searchSalasDesativadas" 
                           placeholder="Buscar salas desativadas...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
            <div id="salasDesativadasContainer" class="row">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
            <div id="salasDesativadasVerMais" style="display: none;">
                <button class="btn btn-ver-mais btn-outline-danger" onclick="sistema.carregarMaisSalasDesativadas()">
                    <i class="fas fa-chevron-down me-2"></i>Ver Mais
                </button>
            </div>
        </div>
    </div>
    @endif
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
                                        <option value="publica">🌐 Pública</option>
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
                                        name="max_participantes" value="10" min="2" max="20">
                                </div>
                            </div>
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

    <!-- Modal Editar Sala (Staff) -->
    <div class="modal fade" id="modalEditarSala" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Editar Sala (Staff)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditarSala">
                    <input type="hidden" id="editSalaId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="editNomeSala" class="form-label">Nome da Sala *</label>
                                    <input type="text" class="form-control" id="editNomeSala" name="nome" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editTipoSala" class="form-label">Tipo *</label>
                                    <select class="form-select" id="editTipoSala" name="tipo" required>
                                        <option value="publica">🌐 Pública</option>
                                        <option value="privada">🔒 Privada</option>
                                        <option value="apenas_convite">📧 Apenas Convite</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editDescricaoSala" class="form-label">Descrição</label>
                            <textarea class="form-control" id="editDescricaoSala" name="descricao" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editMaxParticipantes" class="form-label">Máx. Participantes</label>
                                    <input type="number" class="form-control" id="editMaxParticipantes"
                                        name="max_participantes" min="2" max="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="editAtiva" name="ativa" checked>
                                        <label class="form-check-label" for="editAtiva">
                                            Sala Ativa
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Motivo de Desativação -->
<div class="modal fade" id="modalMotivoDesativacao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-power-off me-2"></i>Desativar Sala
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMotivoDesativacao">
                <input type="hidden" id="motivoSalaId">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atenção:</strong> Esta ação desativará a sala e impedirá novos acessos 
                        (exceto staff).
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Sala a ser desativada:</label>
                        <div class="alert alert-secondary">
                            <strong id="motivoSalaNome"></strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="motivoDesativacao" class="form-label">
                            Motivo da Desativação <span class="text-muted">(opcional)</span>
                        </label>
                        <textarea class="form-control" 
                                  id="motivoDesativacao" 
                                  name="motivo" 
                                  rows="4"
                                  placeholder="Ex: Violação das regras da comunidade, conteúdo inapropriado, spam, etc."></textarea>
                        <small class="text-muted">
                            O criador da sala poderá ver este motivo.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-power-off me-2"></i>Desativar Sala
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
        const IS_STAFF = {{ $isStaff ? 'true' : 'false' }};

        // Classe principal do Sistema de Salas
        class SistemaSalas {
            constructor() {
                this.pageMinhas = 1;
                this.pagePublicas = 1;
                this.pageDesativadas = 1; // NOVO
                this.searchMinhas = '';
                this.searchPublicas = '';
                this.searchDesativadas = ''; // NOVO
                this.hasMoreMinhas = false;
                this.hasMorePublicas = false;
                this.hasMoreDesativadas = false; // NOVO
                
                this.init();
                this.loadSalas();
                this.bindEvents();
                this.setupWebSocketIndicator();
                this.setupSearch();

                if (IS_STAFF) {
        this.loadSalasDesativadas();
    }
            }

            init() {
                console.log('🎮 Sistema de Salas inicializado');
                console.log('📊 Staff Mode:', IS_STAFF);
            }

            // Configurar busca com debounce
            setupSearch() {
    let timeoutMinhas, timeoutPublicas, timeoutDesativadas;

    $('#searchMinhasSalas').on('input', (e) => {
        clearTimeout(timeoutMinhas);
        timeoutMinhas = setTimeout(() => {
            this.searchMinhas = e.target.value.trim();
            this.pageMinhas = 1;
            this.loadSalas();
        }, 500);
    });

    $('#searchSalasPublicas').on('input', (e) => {
        clearTimeout(timeoutPublicas);
        timeoutPublicas = setTimeout(() => {
            this.searchPublicas = e.target.value.trim();
            this.pagePublicas = 1;
            this.loadSalas();
        }, 500);
    });

    // NOVO: Busca de salas desativadas
    $('#searchSalasDesativadas').on('input', (e) => {
        clearTimeout(timeoutDesativadas);
        timeoutDesativadas = setTimeout(() => {
            this.searchDesativadas = e.target.value.trim();
            this.pageDesativadas = 1;
            this.loadSalasDesativadas();
        }, 500);
    });
}

loadSalasDesativadas() {
    if (!IS_STAFF) return;

    $.ajax({
        url: '/api/salas/desativadas',
        type: 'GET',
        data: {
            page: this.pageDesativadas,
            search: this.searchDesativadas
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    })
    .done(data => {
        if (data.success) {
            if (this.pageDesativadas === 1) {
                this.renderSalasDesativadas(data.salas);
            } else {
                this.appendSalasDesativadas(data.salas);
            }

            this.hasMoreDesativadas = data.pagination.has_more;
            $('#salasDesativadasVerMais').toggle(this.hasMoreDesativadas);
        }
    })
    .fail(xhr => {
        console.error('❌ Erro ao carregar salas desativadas:', xhr);
    });
}

// Renderizar salas desativadas
renderSalasDesativadas(salas) {
    const container = $('#salasDesativadasContainer');

    if (salas.length === 0) {
        container.html(`
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-power-off text-success"></i>
                    <h4>Nenhuma sala desativada</h4>
                    <p>Todas as salas estão ativas! 🎉</p>
                </div>
            </div>
        `);
        return;
    }

    let html = '';
    salas.forEach(sala => {
        html += this.generateSalaCard(sala, false, true); // novo parâmetro: isDesativada
    });
    container.html(html);
}

// Adicionar mais salas desativadas
appendSalasDesativadas(salas) {
    const container = $('#salasDesativadasContainer');
    let html = '';
    salas.forEach(sala => {
        html += this.generateSalaCard(sala, false, true);
    });
    container.append(html);
}

// Carregar mais salas desativadas
carregarMaisSalasDesativadas() {
    this.pageDesativadas++;
    this.loadSalasDesativadas();
}

mostrarMinhasSalasDesativadas() {
    $('#secaoMinhasSalasDesativadas').slideDown(400);
    this.loadMinhasSalasDesativadas();
    
    // Scroll suave até a seção
    $('html, body').animate({
        scrollTop: $('#secaoMinhasSalasDesativadas').offset().top - 100
    }, 600);
}

// Ocultar minhas salas desativadas
ocultarMinhasSalasDesativadas() {
    $('#secaoMinhasSalasDesativadas').slideUp(400);
}

// Carregar minhas salas desativadas
loadMinhasSalasDesativadas() {
    $.ajax({
        url: '/api/salas/minhas-desativadas',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    })
    .done(data => {
        if (data.success) {
            this.renderMinhasSalasDesativadas(data.salas);
        }
    })
    .fail(xhr => {
        console.error('❌ Erro ao carregar suas salas desativadas:', xhr);
        this.showAlert('Erro ao carregar suas salas desativadas.', 'danger');
    });
}

// Renderizar minhas salas desativadas
renderMinhasSalasDesativadas(salas) {
    const container = $('#minhasSalasDesativadasContainer');

    if (salas.length === 0) {
        container.html(`
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-check-circle text-success"></i>
                    <h4>Nenhuma sala desativada</h4>
                    <p>Suas salas estão todas ativas!</p>
                </div>
            </div>
        `);
        return;
    }

    let html = '';
    salas.forEach(sala => {
        html += this.generateSalaCardDesativadaCreator(sala);
    });
    container.html(html);
}

// Gerar card especial para sala desativada (visão do criador)
generateSalaCardDesativadaCreator(sala) {
    const participantes = sala.participantes;
    const tipoClass = `tipo-${sala.tipo}`;
    const tipoText = sala.tipo === 'publica' ? 'Pública' : (sala.tipo === 'privada' ? 'Privada' : 'Convite');

    const bannerStyle = sala.banner_url
        ? `background-image:url('${sala.banner_url}');`
        : `background-color:${sala.banner_color || '#6c757d'};`;

    const bannerMini = `
        <div class="sala-banner-mini" style="${bannerStyle}">
            ${!sala.banner_url ? `<div class="banner-fallback">${sala.nome}</div>` : ``}
        </div>`;

    const photoStyle = sala.profile_photo_url
        ? `background-image:url('${sala.profile_photo_url}');`
        : `background-color:${sala.profile_photo_color || '#6c757d'};`;

    const profilePhotoMini = `
        <div class="sala-profile-photo-mini" style="${photoStyle}">
            ${!sala.profile_photo_url ? `<div class="photo-fallback">${sala.nome.charAt(0).toUpperCase()}</div>` : ``}
        </div>`;

    return `
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="sala-card animate__animated animate__fadeInUp status-inactive" style="position: relative;">
                <span class="badge-desativada-creator">
                    <i class="fas fa-ban me-1"></i>DESATIVADA
                </span>
                ${bannerMini}
                <div class="px-3 pt-1 pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            ${profilePhotoMini}
                            <h5 class="mb-0">${sala.nome}</h5>
                        </div>
                        <span class="tipo-badge ${tipoClass}">${tipoText}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Esta sala foi desativada pela Moderação. Entre em contato com a equipe para mais informações.</small>
                    </div>

                    ${sala.motivo_desativacao ? `
        <div class="desativacao-info mb-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-comment-dots text-warning me-2 mt-1"></i>
                <div>
                    <strong>Motivo da desativação:</strong>
                    <p class="mb-1 mt-1">${sala.motivo_desativacao}</p>
                    <small class="text-muted">
                        ${sala.desativada_por ? `
                            Desativada por: <strong>${sala.desativada_por.username || 'Staff'}</strong>
                        ` : ''}
                        ${sala.data_desativacao ? `
                            em ${this.formatDateTime(sala.data_desativacao)}
                        ` : ''}
                    </small>
                </div>
            </div>
        </div>
    ` : ''}

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
                            <small class="text-muted d-block">Status</small>
                            <strong class="text-danger">Inativa</strong>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i> Criada ${this.formatDate(sala.data_criacao)}
                        </small>
                        <button class="btn btn-secondary btn-sm" disabled>
                            <i class="fas fa-ban me-1"></i>Inacessível
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
}

            // Carregar dados das salas via AJAX
            loadSalas() {
                this.showLoading();

                $.ajax({
                    url: '/api/salas/data',
                    type: 'GET',
                    data: {
                        page_minhas: this.pageMinhas,
                        page_publicas: this.pagePublicas,
                        search_minhas: this.searchMinhas,
                        search_publicas: this.searchPublicas
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    }
                })
                .done(data => {
                    console.log('📊 Dados carregados:', data);
                    if (data.success) {
                        if (this.pageMinhas === 1) {
                            this.renderMinhasSalas(data.minhas_salas);
                        } else {
                            this.appendMinhasSalas(data.minhas_salas);
                        }

                        if (this.pagePublicas === 1) {
                            this.renderSalasPublicas(data.salas_publicas);
                        } else {
                            this.appendSalasPublicas(data.salas_publicas);
                        }

                        // Atualizar controle de paginação
                        this.hasMoreMinhas = data.pagination.minhas.has_more;
                        this.hasMorePublicas = data.pagination.publicas.has_more;

                        $('#minhasSalasVerMais').toggle(this.hasMoreMinhas);
                        $('#salasPublicasVerMais').toggle(this.hasMorePublicas);
                    }
                    this.hideLoading();
                })
                .fail(xhr => {
                    console.error('❌ Erro ao carregar salas:', xhr);
                    this.showAlert('Erro ao carregar salas. Tente novamente.', 'danger');
                    this.hideLoading();
                });
            }

            // Carregar mais minhas salas
            carregarMaisMinhasSalas() {
                this.pageMinhas++;
                this.loadSalas();
            }

            // Carregar mais salas públicas
            carregarMaisSalasPublicas() {
                this.pagePublicas++;
                this.loadSalas();
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

            // Adicionar mais minhas salas
            appendMinhasSalas(salas) {
                const container = $('#minhasSalasContainer');
                let html = '';
                salas.forEach(sala => {
                    html += this.generateSalaCard(sala, true);
                });
                container.append(html);
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

            // Adicionar mais salas públicas
            appendSalasPublicas(salas) {
                const container = $('#salasPublicasContainer');
                let html = '';
                salas.forEach(sala => {
                    html += this.generateSalaCard(sala, false);
                });
                container.append(html);
            }

            // Gerar HTML do card da sala
            generateSalaCard(sala, isMyRoom, isDesativada = false) {
                const participantes = sala.participantes;
                const criador = sala.criador;
                const tipoClass = `tipo-${sala.tipo}`;
                const tipoText = sala.tipo === 'publica' ? 'Pública' : (sala.tipo === 'privada' ? 'Privada' : 'Convite');

                const isCreator = criador && parseInt(criador.id) === parseInt(AUTH_ID);
                const bannerStyle = sala.banner_url
                    ? `background-image:url('${sala.banner_url}');`
                    : `background-color:${sala.banner_color || '#6c757d'};`;

                    // NOVO: Badge de acesso staff em salas desativadas
    const staffAccessBadge = (isDesativada && IS_STAFF) ? `
        <span class="badge-staff-access">
            <i class="fas fa-shield-alt me-1"></i>ACESSO STAFF
        </span>` : '';

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

                const photoStyle = sala.profile_photo_url
                    ? `background-image:url('${sala.profile_photo_url}');`
                    : `background-color:${sala.profile_photo_color || '#6c757d'};`;

                const profilePhotoMini = `
                    <div class="sala-profile-photo-mini" style="${photoStyle}" data-sala-id="${sala.id}">
                        ${isCreator ? `
                            <button class="btn btn-sm btn-light rounded-circle shadow-sm photo-edit-btn open-profile-photo-editor-btn"
                                    data-sala-id="${sala.id}"
                                    data-foto-url="${sala.profile_photo_url || ''}"
                                    title="Editar foto de perfil">
                                <i class="fa-solid fa-camera"></i>
                            </button>` : ``}
                        ${!sala.profile_photo_url ? `<div class="photo-fallback">${sala.nome.charAt(0).toUpperCase()}</div>` : ``}
                    </div>`;

                const actionButton = isMyRoom
                    ? `<a href="/salas/${sala.id}" class="btn btn-primary-custom btn-sm">
                         <i class="fas fa-play me-1"></i>Entrar
                       </a>`
                    : `<button class="btn btn-success-custom btn-sm" onclick="sistema.entrarSalaRapida(${sala.id})">
                         <i class="fas fa-sign-in-alt me-1"></i>Juntar-se
                       </button>`;

                       const desativadaBadge = isDesativada ? `
    <span class="badge bg-danger position-absolute top-0 start-0 m-2">
        <i class="fas fa-power-off me-1"></i>DESATIVADA
    </span>` : '';

                // Dropdown de ações staff
                const staffActions = IS_STAFF ? `
                    <div class="staff-actions">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-danger dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-shield-alt"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#" onclick="sistema.editarSalaStaff(${sala.id}); return false;">
                                        <i class="fas fa-edit me-2"></i>Editar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="sistema.toggleStatusSala(${sala.id}, ${sala.ativa}, '${sala.nome.replace(/'/g, "\\'")}'); return false;">
    <i class="fas fa-power-off me-2"></i>${sala.ativa ? 'Desativar' : 'Ativar'}
</a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="sistema.deletarSalaStaff(${sala.id}); return false;">
                                        <i class="fas fa-trash me-2"></i>Deletar
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>` : '';

                const statusClass = sala.ativa ? '' : 'status-inactive';

                return `
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="sala-card animate__animated animate__fadeInUp ${statusClass}">
                        ${staffAccessBadge}
                        ${desativadaBadge}
                            ${staffActions}
                            ${bannerMini}
                            <div class="px-3 pt-1 pb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        ${profilePhotoMini}
                                        <h5 class="mb-0">${sala.nome}</h5>
                                    </div>
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
                                ${sala.motivo_desativacao ? `
        <div class="desativacao-info mb-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-comment-dots text-warning me-2 mt-1"></i>
                <div>
                    <strong>Motivo da desativação:</strong>
                    <p class="mb-1 mt-1">${sala.motivo_desativacao}</p>
                    <small class="text-muted">
                        ${sala.desativada_por ? `
                            Desativada por: <strong>${sala.desativada_por.username || 'Staff'}</strong>
                        ` : ''}
                        ${sala.data_desativacao ? `
                            em ${this.formatDateTime(sala.data_desativacao)}
                        ` : ''}
                    </small>
                </div>
            </div>
        </div>
    ` : ''}
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

            // ========== MÉTODOS STAFF ==========

            // Editar sala (staff)
            editarSalaStaff(salaId) {
                $.get(`/salas/${salaId}/staff/edit`)
                    .done(response => {
                        if (response.success) {
                            const sala = response.sala;
                            $('#editSalaId').val(sala.id);
                            $('#editNomeSala').val(sala.nome);
                            $('#editDescricaoSala').val(sala.descricao || '');
                            $('#editTipoSala').val(sala.tipo);
                            $('#editMaxParticipantes').val(sala.max_participantes);
                            $('#editAtiva').prop('checked', sala.ativa);

                            $('#modalEditarSala').modal('show');
                        }
                    })
                    .fail(xhr => {
                        this.showAlert('Erro ao carregar dados da sala.', 'danger');
                    });
            }

            // Toggle status da sala
            toggleStatusSala(salaId, ativaAtual, salaNome = '') {
    // Se estiver ativando, não precisa de motivo
    if (!ativaAtual) {
        if (!confirm(`Deseja realmente reativar esta sala?`)) return;
        
        $.post(`/salas/${salaId}/staff/toggle-status`)
            .done(response => {
                if (response.success) {
                    this.showAlert(response.message, 'success');
                    this.recarregarTodasSalas();
                }
            })
            .fail(xhr => {
                const msg = xhr.responseJSON?.message || 'Erro ao reativar sala.';
                this.showAlert(msg, 'danger');
            });
        return;
    }
    
    // Se estiver desativando, abrir modal de motivo
    $('#motivoSalaId').val(salaId);
    $('#motivoSalaNome').text(salaNome || `Sala #${salaId}`);
    $('#motivoDesativacao').val('');
    $('#modalMotivoDesativacao').modal('show');
}

// Novo método para confirmar desativação com motivo
confirmarDesativacaoComMotivo() {
    const salaId = $('#motivoSalaId').val();
    const motivo = $('#motivoDesativacao').val().trim();
    
    const btnSubmit = $('#formMotivoDesativacao button[type="submit"]');
    const textoOriginal = btnSubmit.html();
    btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Desativando...');
    
    $.ajax({
        url: `/salas/${salaId}/staff/toggle-status`,
        type: 'POST',
        data: { motivo: motivo || null },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    })
    .done(response => {
        if (response.success) {
            this.showAlert(response.message, 'success');
            $('#modalMotivoDesativacao').modal('hide');
            this.recarregarTodasSalas();
        }
    })
    .fail(xhr => {
        const msg = xhr.responseJSON?.message || 'Erro ao desativar sala.';
        this.showAlert(msg, 'danger');
    })
    .always(() => {
        btnSubmit.prop('disabled', false).html(textoOriginal);
    });
}

// Novo método auxiliar para recarregar tudo
recarregarTodasSalas() {
    this.pageMinhas = 1;
    this.pagePublicas = 1;
    this.pageDesativadas = 1;
    this.loadSalas();
    
    if (IS_STAFF) {
        this.loadSalasDesativadas();
    }
}

            // Deletar sala (staff)
            deletarSalaStaff(salaId) {
                if (!confirm('⚠️ ATENÇÃO: Esta ação é IRREVERSÍVEL!\n\nDeseja realmente deletar esta sala permanentemente?')) return;

                $.ajax({
                    url: `/salas/${salaId}/staff/delete`,
                    type: 'DELETE'
                })
                .done(response => {
                    if (response.success) {
                        this.showAlert(response.message, 'success');
                        this.pageMinhas = 1;
                        this.pagePublicas = 1;
                        this.loadSalas();
                    }
                })
                .fail(xhr => {
                    const msg = xhr.responseJSON?.message || 'Erro ao deletar sala.';
                    this.showAlert(msg, 'danger');
                });
            }

            // ========== EVENTOS ==========

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

    // ⚠️ IMPORTANTE: Submit editar sala (PREVENIR SUBMIT PADRÃO)
    $('#formEditarSala').off('submit').on('submit', (e) => {
        e.preventDefault();
        e.stopPropagation();
        console.log('📋 Formulário de edição submetido');
        this.salvarEdicaoSala();
        return false;
    });

    // Submit entrar em sala
    $('#formEntrarSala').submit(e => {
        e.preventDefault();
        this.entrarSala();
    });

    $('#formMotivoDesativacao').submit(e => {
    e.preventDefault();
    this.confirmarDesativacaoComMotivo();
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

            // Salvar edição da sala (staff)
            salvarEdicaoSala() {
    const salaId = $('#editSalaId').val();
    
    if (!salaId) {
        this.showAlert('ID da sala não encontrado.', 'danger');
        return;
    }

    const data = {
        nome: $('#editNomeSala').val().trim(),
        descricao: $('#editDescricaoSala').val().trim(),
        tipo: $('#editTipoSala').val(),
        max_participantes: parseInt($('#editMaxParticipantes').val()) || 50,
        ativa: $('#editAtiva').is(':checked')
    };

    // Validação básica
    if (!data.nome || data.nome.length < 3) {
        this.showAlert('O nome da sala deve ter pelo menos 3 caracteres.', 'warning');
        return;
    }

    if (data.max_participantes < 2 || data.max_participantes > 100) {
        this.showAlert('O número de participantes deve estar entre 2 e 100.', 'warning');
        return;
    }

    console.log('📝 Salvando alterações da sala:', {
        salaId: salaId,
        data: data
    });

    // Desabilitar botão de salvar
    const btnSalvar = $('#formEditarSala button[type="submit"]');
    const textoOriginal = btnSalvar.html();
    btnSalvar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Salvando...');

    $.ajax({
        url: `/salas/${salaId}/staff/update`,
        type: 'PUT',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    })
    .done(response => {
        console.log('✅ Sala atualizada com sucesso:', response);
        
        if (response.success) {
            this.showAlert(response.message || 'Sala atualizada com sucesso!', 'success');
            $('#modalEditarSala').modal('hide');
            
            // Recarregar as salas após 1 segundo
            setTimeout(() => {
                this.pageMinhas = 1;
                this.pagePublicas = 1;
                this.loadSalas();
            }, 1000);
        } else {
            this.showAlert(response.message || 'Erro ao salvar alterações.', 'danger');
        }
    })
    .fail(xhr => {
        console.error('❌ Erro ao salvar alterações:', xhr);
        
        let errorMsg = 'Erro ao salvar alterações.';
        
        if (xhr.status === 422) {
            // Erros de validação
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                errorMsg = 'Erros de validação:<br>';
                Object.values(errors).forEach(error => {
                    errorMsg += `• ${error[0]}<br>`;
                });
            }
        } else if (xhr.status === 403) {
            errorMsg = 'Você não tem permissão para editar esta sala.';
        } else if (xhr.status === 404) {
            errorMsg = 'Sala não encontrada.';
        } else if (xhr.responseJSON?.message) {
            errorMsg = xhr.responseJSON.message;
        }
        
        this.showAlert(errorMsg, 'danger');
    })
    .always(() => {
        // Reabilitar botão
        btnSalvar.prop('disabled', false).html(textoOriginal);
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
                    'publica': '🌐 Pública',
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

            // Formatar data e hora
formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
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
                            this.pageMinhas = 1;
                            this.loadSalas();

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
                    dataType: 'json',
                    timeout: 10000
                })
                .done((response) => {
                    console.log('Resposta recebida:', response);

                    if (response && response.success) {
                        this.showAlert(response.message || 'Entrada realizada com sucesso!', 'success');

                        setTimeout(() => {
                            if (response.redirect_to) {
                                window.location.href = response.redirect_to;
                            } else {
                                location.reload();
                            }
                        }, 1500);
                    } else {
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

                    try {
                        if (xhr.responseJSON) {
                            errorMsg = xhr.responseJSON.message || errorMsg;
                        } else if (xhr.responseText) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        }
                    } catch (e) {
                        console.warn('Não foi possível interpretar resposta de erro:', e);
                    }

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
                }, 10000);
            }
        }

        // Event listeners para botões de edição
        document.body.addEventListener('click', function (e) {
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

        document.body.addEventListener('click', function (e) {
            const btn = e.target.closest('.open-profile-photo-editor-btn');
            if (!btn) return;

            const salaId = btn.getAttribute('data-sala-id');
            const fotoUrl = btn.getAttribute('data-foto-url') || null;

            if (window.openProfilePhotoEditor) {
                window.openProfilePhotoEditor(salaId, fotoUrl);
            } else {
                console.error('openProfilePhotoEditor indisponível');
            }
        });

        // Inicializar sistema quando document estiver pronto
        let sistema;
        $(document).ready(() => {
            sistema = new SistemaSalas();
        });
    </script>

    <script src="{{ asset('js/moderation.js') }}" defer></script>

    <script>
        // Inicialização da moderação de texto
        async function initModeration() {
            try {
                const state = await window.Moderation.init({
                    csrfToken: $('meta[name="csrf-token"]').attr('content'),
                    endpoint: '/moderate',
                    debounceMs: 120
                });

                console.log('🛡️ Sistema de moderação inicializado:', state);

                function applyWarning(selector, res) {
                    const el = document.querySelector(selector);
                    const warnId = selector.replace('#', '') + '-warning';
                    let warn = document.getElementById(warnId);
                    
                    if (!el) return;

                    if (!warn) {
                        warn = document.createElement('small');
                        warn.id = warnId;
                        warn.className = 'moderation-warning';
                        warn.textContent = 'Conteúdo inapropriado detectado';
                        el.parentNode.appendChild(warn);
                    }

                    if (res && res.inappropriate) {
                        el.classList.add('input-warn');
                        warn.classList.add('show');
                    } else {
                        el.classList.remove('input-warn');
                        warn.classList.remove('show');
                    }
                }

                // Conectar campos do modal de criar sala
                window.Moderation.attachInput('#nomeSala', 'nome', {
                    onLocal: (res) => {
                        applyWarning('#nomeSala', res);
                        if (res.inappropriate) {
                            console.warn('⚠️ Nome da sala com conteúdo inapropriado:', res.matches);
                        }
                    },
                    onServer: (srv) => {
                        if (srv && srv.data && srv.data.inappropriate) {
                            applyWarning('#nomeSala', { inappropriate: true });
                            console.warn('⚠️ Servidor detectou conteúdo inapropriado no nome');
                        }
                    }
                });

                window.Moderation.attachInput('#descricaoSala', 'descricao', {
                    onLocal: (res) => {
                        applyWarning('#descricaoSala', res);
                        if (res.inappropriate) {
                            console.warn('⚠️ Descrição com conteúdo inapropriado:', res.matches);
                        }
                    },
                    onServer: (srv) => {
                        if (srv && srv.data && srv.data.inappropriate) {
                            applyWarning('#descricaoSala', { inappropriate: true });
                            console.warn('⚠️ Servidor detectou conteúdo inapropriado na descrição');
                        }
                    }
                });

                // Conectar campos do modal de editar sala (staff)
                window.Moderation.attachInput('#editNomeSala', 'nome', {
                    onLocal: (res) => {
                        applyWarning('#editNomeSala', res);
                    }
                });

                window.Moderation.attachInput('#editDescricaoSala', 'descricao', {
                    onLocal: (res) => {
                        applyWarning('#editDescricaoSala', res);
                    }
                });

                // Interceptar submits
                window.Moderation.attachFormSubmit('#formCriarSala', [
                    { selector: '#nomeSala', fieldName: 'nome' },
                    { selector: '#descricaoSala', fieldName: 'descricao' }
                ]);

                window.Moderation.attachFormSubmit('#formEditarSala', [
                    { selector: '#editNomeSala', fieldName: 'nome' },
                    { selector: '#editDescricaoSala', fieldName: 'descricao' }
                ]);

                document.getElementById('formCriarSala').addEventListener('moderation:blocked', (e) => {
                    console.error('🚫 Formulário bloqueado por conteúdo inapropriado:', e.detail);
                    sistema.showAlert(
                        'Conteúdo inapropriado detectado. Por favor, revise os campos marcados antes de criar a sala.', 
                        'danger'
                    );
                });

                document.getElementById('formEditarSala').addEventListener('moderation:blocked', (e) => {
                    console.error('🚫 Formulário de edição bloqueado:', e.detail);
                    sistema.showAlert(
                        'Conteúdo inapropriado detectado. Por favor, revise os campos marcados.', 
                        'danger'
                    );
                });

            } catch (error) {
                console.error('❌ Erro ao inicializar moderação:', error);
            }
        }

        // Inicializar quando o documento estiver pronto
        $(document).ready(() => {
            setTimeout(() => {
                initModeration();
            }, 100);
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
    <script>
        // 🔧 FIX DEFINITIVO PARA EDIÇÃO DE SALAS (STAFF)
// Adicione este script no final do dashboard.blade.php, antes de </body>

$(document).ready(function() {
    console.log('🔧 Inicializando fix de edição de salas...');
    
    // Remove TODOS os event listeners anteriores do formulário
    $('#formEditarSala').off('submit');
    
    // Adiciona novo listener com máxima prioridade
    $('#formEditarSala').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        console.log('📝 Formulário de edição interceptado!');
        
        const salaId = $('#editSalaId').val();
        
        if (!salaId) {
            alert('Erro: ID da sala não encontrado.');
            return false;
        }
        
        // Coleta os dados do formulário
        const formData = {
            nome: $('#editNomeSala').val().trim(),
            descricao: $('#editDescricaoSala').val().trim(),
            tipo: $('#editTipoSala').val(),
            max_participantes: parseInt($('#editMaxParticipantes').val()) || 50,
            ativa: $('#editAtiva').is(':checked') ? 1 : 0
        };
        
        console.log('📊 Dados a enviar:', formData);
        
        // Validação básica
        if (!formData.nome || formData.nome.length < 3) {
            alert('O nome da sala deve ter pelo menos 3 caracteres.');
            return false;
        }
        
        if (formData.max_participantes < 2 || formData.max_participantes > 100) {
            alert('O número de participantes deve estar entre 2 e 100.');
            return false;
        }
        
        // Desabilita botão e mostra loading
        const btnSalvar = $('#formEditarSala button[type="submit"]');
        const textoOriginal = btnSalvar.html();
        btnSalvar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Salvando...');
        
        // Faz a requisição AJAX
        $.ajax({
            url: `/salas/${salaId}/staff/update`,
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            dataType: 'json'
        })
        .done(function(response) {
            console.log('✅ Resposta recebida:', response);
            
            if (response.success) {
                // Mostra mensagem de sucesso
                if (typeof sistema !== 'undefined' && sistema.showAlert) {
                    sistema.showAlert(response.message || 'Sala atualizada com sucesso!', 'success');
                } else {
                    alert(response.message || 'Sala atualizada com sucesso!');
                }
                
                // Fecha o modal
                $('#modalEditarSala').modal('hide');
                
                // Recarrega as salas após 1 segundo
                setTimeout(function() {
                    if (typeof sistema !== 'undefined' && sistema.loadSalas) {
                        sistema.pageMinhas = 1;
                        sistema.pagePublicas = 1;
                        sistema.loadSalas();
                    } else {
                        location.reload();
                    }
                }, 1000);
            } else {
                alert(response.message || 'Erro ao salvar alterações.');
            }
        })
        .fail(function(xhr, status, error) {
            console.error('❌ Erro na requisição:', {xhr, status, error});
            
            let errorMsg = 'Erro ao salvar alterações.';
            
            if (xhr.status === 422) {
                // Erros de validação
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    errorMsg = 'Erros de validação:\n';
                    Object.values(errors).forEach(function(err) {
                        errorMsg += '• ' + err[0] + '\n';
                    });
                }
            } else if (xhr.status === 403) {
                errorMsg = 'Você não tem permissão para editar esta sala.';
            } else if (xhr.status === 404) {
                errorMsg = 'Sala não encontrada.';
            } else if (xhr.responseJSON?.message) {
                errorMsg = xhr.responseJSON.message;
            }
            
            alert(errorMsg);
        })
        .always(function() {
            // Reabilita o botão
            btnSalvar.prop('disabled', false).html(textoOriginal);
        });
        
        return false;
    });
    
    // Previne qualquer outro submit
    $('#formEditarSala').attr('onsubmit', 'return false;');
    
    console.log('✅ Fix de edição aplicado com sucesso!');
});

// Função adicional para debug
window.debugFormEdit = function() {
    console.log('🔍 Debug do Formulário de Edição:');
    console.log('ID da Sala:', $('#editSalaId').val());
    console.log('Nome:', $('#editNomeSala').val());
    console.log('Descrição:', $('#editDescricaoSala').val());
    console.log('Tipo:', $('#editTipoSala').val());
    console.log('Max Participantes:', $('#editMaxParticipantes').val());
    console.log('Ativa:', $('#editAtiva').is(':checked'));
    console.log('Event Listeners:', $._data($('#formEditarSala')[0], 'events'));
};
    </script>
</body>
</html>