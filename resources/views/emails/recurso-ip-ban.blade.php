<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Recurso de IP Ban</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f3f4f6;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); padding: 40px 30px; text-align: center;">
                            <div style="width: 80px; height: 80px; background: white; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 40px;">🚨</span>
                            </div>
                            <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: white;">Novo Recurso de IP Ban</h1>
                            <p style="margin: 10px 0 0 0; font-size: 16px; color: rgba(255,255,255,0.9);">Requer atenção imediata da moderação</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <!-- Alerta Urgente -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-left: 4px solid #dc2626; border-radius: 12px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 700; color: #991b1b;">⚠️ Prioridade Urgente</h3>
                                        <p style="margin: 0; font-size: 14px; color: #991b1b; line-height: 1.6;">
                                            Um usuário banido por IP está solicitando revisão do banimento. Por favor, analise o caso o mais rápido possível.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Informações do Ticket -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #f9fafb; border-radius: 12px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 700; color: #1a202c;">📋 Informações do Recurso</h3>
                                        
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; font-weight: 600; color: #6b7280; width: 40%;">Ticket:</td>
                                                <td style="padding: 8px 0; font-size: 14px; font-weight: 700; color: #1a202c;">#{{ $ticket->numero_ticket }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; border-top: 1px solid #e5e7eb; font-size: 14px; font-weight: 600; color: #6b7280;">Usuário:</td>
                                                <td style="padding: 8px 0; border-top: 1px solid #e5e7eb; font-size: 14px; font-weight: 700; color: #1a202c;">{{ $usuario->username }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; border-top: 1px solid #e5e7eb; font-size: 14px; font-weight: 600; color: #6b7280;">Email:</td>
                                                <td style="padding: 8px 0; border-top: 1px solid #e5e7eb; font-size: 14px; color: #1a202c;">{{ $email_contato }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; border-top: 1px solid #e5e7eb; font-size: 14px; font-weight: 600; color: #6b7280;">Data:</td>
                                                <td style="padding: 8px 0; border-top: 1px solid #e5e7eb; font-size: 14px; color: #1a202c;">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Assunto -->
                            <div style="margin-bottom: 25px;">
                                <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 700; color: #1a202c;">📌 Assunto</h3>
                                <p style="margin: 0; font-size: 14px; color: #374151; line-height: 1.6; background: #f9fafb; padding: 15px; border-radius: 8px;">
                                    {{ $ticket->assunto }}
                                </p>
                            </div>

                            <!-- Descrição -->
                            <div style="margin-bottom: 30px;">
                                <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 700; color: #1a202c;">📝 Descrição</h3>
                                <div style="font-size: 14px; color: #374151; line-height: 1.8; background: #f9fafb; padding: 15px; border-radius: 8px; white-space: pre-wrap;">{{ $ticket->descricao }}</div>
                            </div>

                            <!-- Botão CTA -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url_ticket }}" style="display: inline-block; padding: 16px 40px; background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; text-decoration: none; border-radius: 10px; font-weight: 700; font-size: 16px; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);">
                                            🔍 Analisar Recurso Agora
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background: #f9fafb; padding: 30px; text-align: center; border-top: 2px solid #e5e7eb;">
                            <p style="margin: 0 0 10px 0; font-size: 14px; color: #6b7280;">
                                Este é um email automático do sistema de moderação
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                                © {{ date('Y') }} Ambience RPG - Todos os direitos reservados
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>