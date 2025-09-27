<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperação de Senha - Ambience RPG</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: #ffffff;
        }
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .reset-button:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }
        .backup-link {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .backup-link p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .backup-link a {
            word-break: break-all;
            color: #007bff;
            text-decoration: none;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .warning-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 5px;
        }
        .warning-text {
            color: #856404;
            font-size: 14px;
            margin: 0;
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
        .button-center {
            text-align: center;
            margin: 30px 0;
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
            .reset-button {
                display: block;
                width: 100%;
                text-align: center;
                padding: 15px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎮 Ambience RPG</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">Recuperação de Senha</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Olá, <strong>{{ $usuario->username }}</strong>! 👋
            </div>
            
            <div class="message">
                <p>Você solicitou a recuperação da sua senha no <strong>Ambience RPG</strong>.</p>
                <p>Para redefinir sua senha de forma segura, clique no botão abaixo:</p>
            </div>
            
            <div class="button-center">
                <a href="{{ $resetUrl }}" class="reset-button">
                    🔐 Redefinir Minha Senha
                </a>
            </div>
            
            <div class="backup-link">
                <p><strong>Se o botão não funcionar</strong>, copie e cole este link no seu navegador:</p>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </div>
            
            <div class="warning">
                <div class="warning-title">⚠️ Informações Importantes:</div>
                <div class="warning-text">
                    <p style="margin: 5px 0;">• Este link expira em <strong>1 hora</strong></p>
                    <p style="margin: 5px 0;">• Se você não solicitou esta recuperação, ignore este email</p>
                    <p style="margin: 5px 0;">• Sua senha atual permanecerá ativa até que você a altere</p>
                    <p style="margin: 5px 0;">• Não compartilhe este link com ninguém</p>
                </div>
            </div>

            <div style="margin-top: 30px; padding: 15px; background: #e3f2fd; border-radius: 5px; border-left: 4px solid #2196f3;">
                <p style="margin: 0; font-size: 14px; color: #1565c0;">
                    <strong>💡 Dica de Segurança:</strong> Sempre verifique se você está no site oficial do Ambience RPG antes de inserir suas credenciais.
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
