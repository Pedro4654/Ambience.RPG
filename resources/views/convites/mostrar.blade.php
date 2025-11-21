<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convite para {{ $sala->nome }} - Ambience RPG</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Banner com overlay */
        .invite-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
        }

        .invite-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(100, 100, 100, 0.7);
            backdrop-filter: blur(10px);
            z-index: 2;
        }

        /* Container do convite */
        .invite-container {
            position: relative;
            z-index: 10;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .invite-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .invite-header {
            text-align: center;
            padding: 40px 30px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .invite-header h5 {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.95;
            margin-bottom: 8px;
        }

        .invite-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            object-fit: cover;
            margin: 0 auto 16px;
        }

        .invite-logo-fallback {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
        }

        .invite-body {
            padding: 30px;
        }

        .sala-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
            text-align: center;
        }

        .sala-description {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.6;
            text-align: center;
            margin-bottom: 24px;
            max-height: 120px;
            overflow-y: auto;
        }

        .sala-info {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .info-item {
            text-align: center;
        }

        .info-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .invite-actions {
            display: flex;
            gap: 12px;
        }

        .btn-accept {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-accept:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
        }

        .btn-decline {
            flex: 1;
            background: #e9ecef;
            color: #495057;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .btn-decline:hover {
            background: #dee2e6;
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsivo */
        @media (max-width: 576px) {
            .invite-card {
                margin: 10px;
            }

            .invite-header {
                padding: 30px 20px 15px;
            }

            .invite-body {
                padding: 20px;
            }

            .sala-name {
                font-size: 1.5rem;
            }

            .invite-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Background com banner -->
    <div class="invite-background"
         @if($sala->banner_url)
            style="background-image: url('{{ $sala->banner_url }}');"
         @else
            style="background: linear-gradient(135deg, {{ $sala->banner_color ?? '#667eea' }} 0%, #764ba2 100%);"
         @endif
    ></div>

    <!-- Container do convite -->
    <div class="invite-container">
        <div class="invite-card">
            <div class="invite-header">
                <h5><i class="fas fa-envelope-open-text me-2"></i>Você foi convidado para entrar em</h5>
            </div>

            <div class="invite-body">
                <!-- Logo/Foto da sala -->
                @if($sala->profile_photo_url)
                    <img src="{{ $sala->profile_photo_url }}" alt="Logo da sala" class="invite-logo">
                @else
                    <div class="invite-logo-fallback" style="background: {{ $sala->profile_photo_color ?? '#667eea' }};">
                        {{ strtoupper(mb_substr($sala->nome, 0, 1)) }}
                    </div>
                @endif

                <!-- Nome da sala -->
                <h2 class="sala-name">{{ $sala->nome }}</h2>

                <!-- Descrição -->
                @if($sala->descricao)
                    <p class="sala-description">{{ $sala->descricao }}</p>
                @else
                    <p class="sala-description text-muted">Sem descrição disponível</p>
                @endif

                <!-- Informações da sala -->
                <div class="sala-info">
                    <div class="info-item">
                        <div class="info-label">Membros</div>
                        <div class="info-value">
                            <i class="fas fa-users text-primary"></i>
                            <span>{{ $num_membros }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Criada em</div>
                        <div class="info-value">
                            <i class="fas fa-calendar text-success"></i>
                            <span>{{ $data_criacao }}</span>
                        </div>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="invite-actions">
                    <button type="button" class="btn btn-decline" id="btnRecusar">
                        Recusar
                    </button>
                    <button type="button" class="btn btn-accept" id="btnAceitar">
                        Aceitar Convite
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnAceitar = document.getElementById('btnAceitar');
            const btnRecusar = document.getElementById('btnRecusar');
            const codigo = "{{ $codigo }}";

            function getCsrf() {
                const meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            }

            // Recusar convite
            btnRecusar.addEventListener('click', function() {
                window.location.href = "{{ route('salas.index') }}";
            });

            // Aceitar convite
            btnAceitar.addEventListener('click', async function() {
                btnAceitar.disabled = true;
                btnRecusar.disabled = true;
                
                const originalText = btnAceitar.innerHTML;
                btnAceitar.innerHTML = '<span class="spinner me-2"></span> Entrando...';

                try {
                    const response = await fetch(`/convite/${codigo}/aceitar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrf(),
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        btnAceitar.innerHTML = '<i class="fas fa-check me-2"></i> Sucesso!';
                        btnAceitar.classList.remove('btn-accept');
                        btnAceitar.classList.add('btn-success');
                        
                        setTimeout(() => {
                            window.location.href = data.redirect_to;
                        }, 500);
                    } else {
                        alert(data.message || 'Erro ao aceitar convite.');
                        btnAceitar.disabled = false;
                        btnRecusar.disabled = false;
                        btnAceitar.innerHTML = originalText;
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    alert('Erro de conexão. Tente novamente.');
                    btnAceitar.disabled = false;
                    btnRecusar.disabled = false;
                    btnAceitar.innerHTML = originalText;
                }
            });
        });
    </script>
</body>
</html>