<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Ambience RPG</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f3f4f6;
            padding: 20px;
            line-height: 1.6;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .email-header h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }
        
        .email-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .email-body {
            padding: 30px;
        }
        
        .ticket-info {
            background: #f9fafb;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .ticket-info h3 {
            font-size: 16px;
            color: #1a202c;
            margin-bottom: 12px;
        }
        
        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #6b7280;
            width: 120px;
            font-size: 14px;
        }
        
        .info-value {
            color: #1a202c;
            flex: 1;
            font-size: 14px;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge.status-novo { background: #dbeafe; color: #1e40af; }
        .badge.status-em_analise { background: #fef3c7; color: #92400e; }
        .badge.status-resolvido { background: #d1fae5; color: #065f46; }
        .badge.status-fechado { background: #e5e7eb; color: #374151; }
        
        .badge.priority-urgente { background: #fee2e2; color: #991b1b; }
        .badge.priority-alta { background: #ffedd5; color: #9a3412; }
        .badge.priority-normal { background: #dbeafe; color: #1e40af; }
        .badge.priority-baixa { background: #e5e7eb; color: #374151; }
        
        .message-content {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 3px solid #667eea;
        }
        
        .message-content p {
            color: #374151;
            font-size: 14px;
            line-height: 1.8;
            white-space: pre-wrap;
        }
        
        .button {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .button:hover {
            opacity: 0.9;
        }
        
        .email-footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            border-top: 2px solid #e5e7eb;
        }
        
        .email-footer p {
            font-size: 13px;
            color: #6b7280;
            margin: 5px 0;
        }
        
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>@yield('header-title')</h1>
            <p>@yield('header-subtitle')</p>
        </div>
        
        <div class="email-body">
            @yield('content')
        </div>
        
        <div class="email-footer">
            <p><strong>Ambience RPG</strong> - Sistema de Suporte</p>
            <p>Este é um email automático. Por favor, não responda diretamente.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                © {{ date('Y') }} Ambience RPG. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>