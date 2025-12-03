<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convite Inválido - Ambience RPG</title>
    
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

        body {
            font-family: Inter, system-ui, -apple-system, sans-serif;
            background: linear-gradient(180deg, var(--bg-dark) 0%, #0d1419 100%);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background decorativo */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at top, rgba(34, 197, 94, 0.1), transparent 60%);
            pointer-events: none;
            z-index: 1;
        }

        body::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.05), transparent 70%);
            pointer-events: none;
            z-index: 1;
        }

        .error-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 520px;
        }

        .error-card {
            background: linear-gradient(145deg, rgba(31, 42, 51, 0.95), rgba(20, 28, 35, 0.9));
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(20px);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Header com gradiente */
        .error-header {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            padding: 48px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .error-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at top, rgba(34, 197, 94, 0.2), transparent 60%);
            pointer-events: none;
        }

        .error-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 24px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.15);
            border: 3px solid rgba(239, 68, 68, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #ef4444;
            animation: pulse 2s ease-in-out infinite;
            position: relative;
            z-index: 2;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(239, 68, 68, 0);
            }
        }

        .error-header h1 {
            font-family: Montserrat, sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            margin: 0;
            position: relative;
            z-index: 2;
            letter-spacing: 0.5px;
        }

        /* Body do card */
        .error-body {
            padding: 40px;
        }

        .error-message {
            font-size: 1.05rem;
            line-height: 1.7;
            color: var(--text-secondary);
            margin-bottom: 24px;
            text-align: center;
        }

        .error-details {
            background: rgba(17, 24, 39, 0.6);
            border: 1px solid rgba(55, 65, 81, 0.5);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 32px;
        }

        .error-details-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
        }

        .error-details-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-details-value i {
            color: #ef4444;
            font-size: 1.1rem;
        }

        /* Botões */
        .error-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            font-family: Inter, sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #052e16;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.5);
        }

        .btn-primary svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        .btn-secondary {
            background: rgba(55, 65, 81, 0.4);
            color: var(--text-secondary);
            border: 1px solid rgba(55, 65, 81, 0.6);
        }

        .btn-secondary:hover {
            background: rgba(55, 65, 81, 0.6);
            border-color: var(--border-hover);
            transform: translateY(-2px);
            color: var(--text-primary);
        }

        .btn-secondary svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        /* Link adicional */
        .error-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border-subtle);
        }

        .error-link a {
            color: var(--accent);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .error-link a:hover {
            color: var(--accent-light);
            gap: 10px;
        }

        .error-link a svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .error-card {
                border-radius: 20px;
            }

            .error-header {
                padding: 40px 24px;
            }

            .error-header h1 {
                font-size: 1.5rem;
            }

            .error-body {
                padding: 30px 24px;
            }

            .error-icon {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .error-message {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <!-- Header -->
            <div class="error-header">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1>Convite Inválido</h1>
            </div>

            <!-- Body -->
            <div class="error-body">
                <p class="error-message">
                    {{ $mensagem ?? 'Este link de convite não é válido ou expirou.' }}
                </p>

                @if(isset($motivo))
                    <div class="error-details">
                        <span class="error-details-label">Motivo do erro</span>
                        <div class="error-details-value">
                            <i class="fas fa-info-circle"></i>
                            <span>{{ ucfirst($motivo) }}</span>
                        </div>
                    </div>
                @endif

                <!-- Ações -->
                <div class="error-actions">
                    <a href="{{ route('salas.index') }}" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22" fill="none" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        Voltar para Salas
                    </a>

                    @if(isset($sala))
                        <a href="{{ route('salas.show', ['id' => $sala->id]) }}" class="btn btn-secondary">
                            <svg viewBox="0 0 24 24">
                                <path d="M15 18l-6-6 6-6"/>
                            </svg>
                            Ver sala: {{ \Illuminate\Support\Str::limit($sala->nome, 25) }}
                        </a>
                    @endif
                </div>

                @if(!isset($sala))
                    <div class="error-link">
                        <a href="{{ route('home') }}">
                            <svg viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                            Ir para página inicial
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>