<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convite para {{ $sala->nome }} - Ambience RPG</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #0a0f14;
            --bg-card: #1f2937;
            --bg-elevated: #111827;
            --border-subtle: rgba(34, 197, 94, 0.1);
            --border-hover: rgba(34, 197, 94, 0.3);
            --text-primary: #e6eef6;
            --text-secondary: #8b9ba8;
            --text-muted: #6b7280;
            --accent: #22c55e;
            --accent-light: #16a34a;
            --accent-dark: #15803d;
            --accent-glow: rgba(34, 197, 94, 0.2);
            --gradient-start: #052e16;
            --gradient-end: #065f46;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            height: 100%;
            overflow: hidden;
            font-family: Inter, system-ui, -apple-system, sans-serif;
        }

        /* Background com banner da sala */
        .invite-background {
            position: fixed;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
        }

        .invite-background::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, 
                rgba(10, 15, 20, 0.75) 0%, 
                rgba(10, 15, 20, 0.82) 50%, 
                rgba(10, 15, 20, 0.88) 100%
            );
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            z-index: 2;
        }

        /* Container principal */
        .invite-container {
            position: relative;
            z-index: 10;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Card do convite */
        .invite-card {
            background: linear-gradient(145deg, rgba(31, 42, 51, 0.95), rgba(20, 28, 35, 0.9));
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            max-width: 540px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(20px);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Header do convite */
        .invite-header {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            padding: 32px 32px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .invite-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at top, rgba(34, 197, 94, 0.2), transparent 60%);
            pointer-events: none;
        }

        .invite-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 24px;
            position: relative;
            z-index: 2;
            /* Centralização absoluta */
            position: absolute;
            top: 32px;
            left: 50%;
            transform: translateX(-50%);
        }

        .invite-badge svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        /* Logo/Avatar da sala */
        .sala-avatar-wrapper {
            position: relative;
            display: inline-block;
            margin-top: 48px;
            margin-bottom: 20px;
            z-index: 2;
        }

        .sala-avatar {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            border: 4px solid rgba(34, 197, 94, 0.3);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            object-fit: cover;
            transition: var(--transition);
        }

        .sala-avatar-fallback {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            border: 4px solid rgba(34, 197, 94, 0.3);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            transition: var(--transition);
        }

        .invite-card:hover .sala-avatar,
        .invite-card:hover .sala-avatar-fallback {
            transform: scale(1.05);
            border-color: var(--accent);
            box-shadow: 0 12px 32px rgba(34, 197, 94, 0.3);
        }

        .sala-avatar-glow {
            position: absolute;
            inset: -20px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.2), transparent 70%);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: -1;
        }

        .invite-card:hover .sala-avatar-glow {
            opacity: 1;
        }

        /* Body do card */
        .invite-body {
            padding: 32px;
        }

        .sala-name {
            font-family: Montserrat, sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 12px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .sala-description {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.7;
            text-align: center;
            margin-bottom: 28px;
            max-height: 100px;
            overflow-y: auto;
            padding-right: 8px;
        }

        /* Scrollbar customizada */
        .sala-description::-webkit-scrollbar {
            width: 6px;
        }

        .sala-description::-webkit-scrollbar-track {
            background: rgba(17, 24, 39, 0.4);
            border-radius: 3px;
        }

        .sala-description::-webkit-scrollbar-thumb {
            background: rgba(34, 197, 94, 0.3);
            border-radius: 3px;
        }

        .sala-description::-webkit-scrollbar-thumb:hover {
            background: rgba(34, 197, 94, 0.5);
        }

        /* Grid de informações */
        .sala-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .info-card {
            background: rgba(17, 24, 39, 0.6);
            border: 1px solid rgba(55, 65, 81, 0.5);
            border-radius: 14px;
            padding: 18px;
            text-align: center;
            transition: var(--transition);
        }

        .info-card:hover {
            background: rgba(17, 24, 39, 0.8);
            border-color: var(--border-hover);
            transform: translateY(-2px);
        }

        .info-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 12px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.05));
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .info-card:hover .info-icon {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.25), rgba(34, 197, 94, 0.1));
        }

        .info-icon svg {
            width: 20px;
            height: 20px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
        }

        .info-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Ações */
        .invite-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: Inter, sans-serif;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-accept {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #052e16;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4);
        }

        .btn-accept:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.5);
        }

        .btn-decline {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-decline:hover:not(:disabled) {
            background: rgba(239, 68, 68, 0.25);
            border-color: #ef4444;
            transform: translateY(-2px);
        }

        .btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        /* Spinner de loading */
        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(5, 46, 22, 0.3);
            border-top-color: #052e16;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Disclaimer */
        .invite-disclaimer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid var(--border-subtle);
        }

        .invite-disclaimer p {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .invite-disclaimer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .invite-disclaimer a:hover {
            color: var(--accent-light);
        }

        /* Estados de sucesso */
        .btn-success {
            background: linear-gradient(135deg, #22c55e, #16a34a) !important;
            color: #052e16 !important;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .invite-card {
                margin: 10px;
                border-radius: 20px;
            }

            .invite-header {
                padding: 24px 20px 20px;
            }

            .invite-body {
                padding: 24px 20px;
            }

            .sala-name {
                font-size: 1.5rem;
            }

            .sala-avatar,
            .sala-avatar-fallback {
                width: 100px;
                height: 100px;
            }

            .invite-actions {
                grid-template-columns: 1fr;
            }

            .sala-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Background com banner da sala -->
    <div class="invite-background"
         @if($sala->banner_url)
            style="background-image: url('{{ $sala->banner_url }}');"
         @else
            style="background: linear-gradient(135deg, {{ $sala->banner_color ?? '#052e16' }} 0%, #065f46 100%);"
         @endif
    ></div>

    <!-- Container do convite -->
    <div class="invite-container">
        <div class="invite-card">
            <!-- Header -->
            <div class="invite-header">
                <div class="invite-badge">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    Você foi convidado
                </div>

                <!-- Avatar/Logo da sala -->
                <div class="sala-avatar-wrapper">
                    @if($sala->profile_photo_url)
                        <img src="{{ $sala->profile_photo_url }}" alt="{{ $sala->nome }}" class="sala-avatar">
                    @else
                        <div class="sala-avatar-fallback">
                            {{ strtoupper(mb_substr($sala->nome, 0, 1)) }}
                        </div>
                    @endif
                    <div class="sala-avatar-glow"></div>
                </div>
            </div>

            <!-- Body -->
            <div class="invite-body">
                <!-- Nome da sala -->
                <h1 class="sala-name">{{ $sala->nome }}</h1>

                <!-- Descrição -->
                @if($sala->descricao)
                    <p class="sala-description">{{ $sala->descricao }}</p>
                @else
                    <p class="sala-description" style="color: var(--text-muted); font-style: italic;">
                        Esta sala ainda não possui uma descrição
                    </p>
                @endif

                <!-- Grid de informações -->
                <div class="sala-info-grid">
                    <div class="info-card">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div class="info-label">Membros</div>
                        <div class="info-value">{{ $num_membros }}</div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div class="info-label">Criada em</div>
                        <div class="info-value">{{ $data_criacao }}</div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="invite-actions">
                    <button type="button" class="btn btn-decline" id="btnRecusar">
                        <svg viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Recusar
                    </button>

                    <button type="button" class="btn btn-accept" id="btnAceitar">
                        <svg viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Aceitar
                    </button>
                </div>

                <!-- Disclaimer -->
                <div class="invite-disclaimer">
                    <p>
                        Ao aceitar, você entrará na sala e poderá participar das sessões.
                        <a href="{{ route('salas.index') }}">Ver todas as salas</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

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
                // Desabilitar botões
                btnAceitar.disabled = true;
                btnRecusar.disabled = true;
                
                // Salvar conteúdo original
                const originalText = btnAceitar.innerHTML;
                
                // Mostrar loading
                btnAceitar.innerHTML = `
                    <span class="spinner"></span>
                    Entrando...
                `;

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
                        // Sucesso
                        btnAceitar.innerHTML = `
                            <svg viewBox="0 0 24 24" style="stroke: currentColor; fill: none; stroke-width: 2;">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Sucesso!
                        `;
                        btnAceitar.classList.add('btn-success');
                        
                        // Redirecionar após breve delay
                        setTimeout(() => {
                            window.location.href = data.redirect_to;
                        }, 600);
                    } else {
                        // Erro
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