<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Código de Recuperação - Ambience RPG</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .token-container {
            background: #f8f9fa;
            border: 3px dashed #007bff;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
        }
        .token-code {
            font-size: 3rem;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 0.5rem;
            margin: 20px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .token-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .warning-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 10px;
        }
        .warning-text {
            color: #856404;
            font-size: 14px;
            line-height: 1.5;
        }
        .footer {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 25px;
            text-align: center;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .timer {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .timer strong {
            color: #1976d2;
        }
        /* Responsivo */
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 0;
            }
            .content {
                padding: 20px 15px;
            }
            .header {
                padding: 20px 15px;
            }
            .token-code {
                font-size: 2rem;
                letter-spacing: 0.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎮 Ambience RPG</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">Código de Recuperação</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Olá, <strong>{{ $usuario->username }}</strong>! 👋
            </div>
            
            <p style="font-size: 16px; color: #555; margin-bottom: 30px;">
                Você solicitou a recuperação da sua senha no <strong>Ambience RPG</strong>.
            </p>

            <div class="token-container">
                <div class="token-label">SEU CÓDIGO DE RECUPERAÇÃO</div>
                <div class="token-code">{{ $token }}</div>
                <p style="color: #666; margin: 0; font-size: 14px;">
                    Digite este código na tela de verificação
                </p>
            </div>

            <div class="timer">
                <p style="margin: 0;">
                    <strong>⏰ Tempo de validade:</strong> 15 minutos<br>
                    <small>Expira em {{ $expires_at->format('d/m/Y às H:i') }}</small>
                </p>
            </div>
            
            <div class="warning">
                <div class="warning-title">🛡️ Informações de Segurança:</div>
                <div class="warning-text">
                    • Este código expira em <strong>15 minutos</strong><br>
                    • Máximo de <strong>5 tentativas por hora</strong><br>
                    • Se você não solicitou esta recuperação, ignore este email<br>
                    • Não compartilhe este código com ninguém<br>
                    • Sua senha atual permanece válida até que você a altere
                </div>
            </div>

            <div style="margin-top: 30px; padding: 15px; background: #e8f5e8; border-radius: 5px; border-left: 4px solid #4caf50;">
                <p style="margin: 0; font-size: 14px; color: #2e7d32;">
                    <strong>💡 Dica:</strong> Se você não recebeu este email, verifique sua pasta de spam ou lixo eletrônico.
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>© {{ date('Y') }} Ambience RPG</strong></p>
            <p>Sistema de RPG Online - Todos os direitos reservados</p>
            <p style="opacity: 0.7; margin-top: 10px;">
                Este é um email automático, não responda a esta mensagem.<br>
                Em caso de dúvidas, entre em contato com nosso suporte.
            </p>
        </div>
    </div>
</body>
</html>
