<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recurso Enviado - Ambience RPG</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #0a0f14;
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: scaleIn 0.5s ease 0.2s backwards;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .success-icon svg {
            width: 60px;
            height: 60px;
            color: #10b981;
        }

        .card-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .card-body {
            padding: 40px 30px;
        }

        .info-box {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-left: 4px solid #10b981;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .info-box h3 {
            font-size: 16px;
            font-weight: 700;
            color: #065f46;
            margin-bottom: 10px;
        }

        .info-box p {
            font-size: 14px;
            color: #047857;
            line-height: 1.6;
        }

        .ticket-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .ticket-info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .ticket-info-item:last-child {
            border-bottom: none;
        }

        .ticket-info-label {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
        }

        .ticket-info-value {
            font-size: 14px;
            font-weight: 700;
            color: #1a202c;
        }

        .next-steps {
            background: #eff6ff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 4px solid #3b82f6;
        }

        .next-steps h3 {
            font-size: 16px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 15px;
        }

        .next-steps ul {
            list-style: none;
            padding: 0;
        }

        .next-steps li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
            font-size: 14px;
            color: #1e40af;
            line-height: 1.6;
        }

        .next-steps li:before {
            content: "✓";
            position: absolute;
            left: 0;
            top: 10px;
            width: 20px;
            height: 20px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-container {
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .card-header {
                padding: 40px 20px;
            }

            .card-header h1 {
                font-size: 22px;
            }

            .card-body {
                padding: 30px 20px;
            }

            .btn-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="success-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1>✅ Recurso Enviado com Sucesso!</h1>
                <p>Sua solicitação de revisão está sendo analisada</p>
            </div>

            <div class="card-body">
                <div class="info-box">
                    <h3>📧 Confirmação Enviada</h3>
                    <p>Enviamos um email de confirmação para você. Verifique sua caixa de entrada e spam.</p>
                </div>

                <div class="ticket-info">
                    <div class="ticket-info-item">
                        <span class="ticket-info-label">Número do Ticket</span>
                        <span class="ticket-info-value">#{{ $ticket->numero_ticket }}</span>
                    </div>
                    <div class="ticket-info-item">
                        <span class="ticket-info-label">Status</span>
                        <span class="ticket-info-value">{{ $ticket->getStatusLabel() }}</span>
                    </div>
                    <div class="ticket-info-item">
                        <span class="ticket-info-label">Prioridade</span>
                        <span class="ticket-info-value">{{ $ticket->getPrioridadeLabel() }}</span>
                    </div>
                    <div class="ticket-info-item">
                        <span class="ticket-info-label">Data de Abertura</span>
                        <span class="ticket-info-value">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="next-steps">
                    <h3>🎯 Próximos Passos</h3>
                    <ul>
                        <li>Nossa equipe de moderação analisará seu caso em até 72 horas</li>
                        <li>Você receberá atualizações por email</li>
                        <li>Mantenha este número de ticket para consultas: <strong>#{{ $ticket->numero_ticket }}</strong></li>
                        <li>Se aprovado, você poderá acessar sua conta normalmente</li>
                    </ul>
                </div>

                <div class="btn-container">
                    <a href="{{ route('ip-ban.recurso.status', $ticket->numero_ticket) }}" class="btn btn-primary">
                        📊 Acompanhar Status
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-secondary">
                        🏠 Ir para Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>