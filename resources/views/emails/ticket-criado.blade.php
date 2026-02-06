<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Criado - Ambience RPG</title>
</head>
<body style="margin:0;padding:0;background-color:#0f1117;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;">
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0f1117;padding:20px;">
        <tr>
            <td align="center">
                
                <table width="500" cellpadding="0" cellspacing="0" border="0" style="background:#1a1d29;border-radius:8px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.3);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#0d5f3a 0%,#0a4a2e 100%);padding:30px 25px;text-align:center;">
                            <h1 style="margin:0;font-size:20px;color:#ffffff;font-weight:700;letter-spacing:0.5px;text-transform:uppercase;">
                                TICKET CRIADO
                            </h1>
                            <p style="margin:8px 0 0 0;font-size:13px;color:#b8e6d5;">Acompanhe seu atendimento</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px 25px;background:#1a1d29;">
                            
                            <p style="margin:0 0 15px 0;font-size:15px;color:#e0e0e0;line-height:1.5;">
                                Olá, <strong style="color:#00ff88;">{{ $ticket->usuario->username }}</strong>
                            </p>

                            <p style="margin:0 0 20px 0;font-size:14px;color:#b0b0b0;line-height:1.6;">
                                Seu ticket foi criado com sucesso! Nossa equipe irá analisar e responder em breve.
                            </p>

                            <!-- Box do Ticket -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0f1a14;border:2px dashed #00ff88;border-radius:8px;margin:20px 0;">
                                <tr>
                                    <td style="padding:25px;text-align:center;">
                                        <div style="font-size:13px;color:#00ff88;margin-bottom:10px;font-weight:600;">NÚMERO DO TICKET</div>
                                        <div style="font-size:42px;font-weight:900;color:#00ff88;letter-spacing:10px;font-family:'Courier New',monospace;">
                                            #{{ $ticket->id }}
                                        </div>
                                        <p style="margin:12px 0 0 0;font-size:12px;color:#b0b0b0;">{{ $ticket->assunto }}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Informações do Ticket -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#232631;border-radius:6px;margin:20px 0;">
                                <tr>
                                    <td style="padding:15px 20px;">
                                        <div style="font-size:14px;color:#00ff88;margin-bottom:10px;font-weight:600;">Informações do Ticket</div>
                                        <table width="100%" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;">Categoria:</td>
                                                <td style="font-size:13px;color:#e0e0e0;text-align:right;font-weight:600;">{{ ucfirst($ticket->categoria) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;padding-top:5px;">Status:</td>
                                                <td style="font-size:13px;color:#00ff88;text-align:right;padding-top:5px;font-weight:600;">{{ ucfirst($ticket->status) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;padding-top:5px;">Data:</td>
                                                <td style="font-size:13px;color:#e0e0e0;text-align:right;padding-top:5px;">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Mensagem -->
                            @if(!empty($ticket->mensagem) || !empty($ticket->descricao) || !empty($ticket->conteudo))
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#232631;border-radius:6px;margin:20px 0;">
                                <tr>
                                    <td style="padding:15px 20px;">
                                        <div style="font-size:14px;color:#00ff88;margin-bottom:10px;font-weight:600;">Mensagem</div>
                                        <div style="font-size:13px;color:#e0e0e0;line-height:1.6;">
                                            {{ $ticket->mensagem ?? $ticket->descricao ?? $ticket->conteudo ?? 'Sem mensagem.' }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Aviso -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0f1a14;border-left:3px solid #00ff88;border-radius:4px;margin:20px 0 0 0;">
                                <tr>
                                    <td style="padding:12px 15px;">
                                        <p style="margin:0;font-size:12px;color:#b0b0b0;line-height:1.6;">
                                            Você receberá notificações por email sobre atualizações no seu ticket.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#14161f;padding:20px;text-align:center;border-top:1px solid #2a2d3a;">
                            <p style="margin:0 0 5px 0;font-size:13px;color:#00ff88;font-weight:600;">
                                Ambience RPG
                            </p>
                            <p style="margin:0 0 10px 0;font-size:12px;color:#888;">
                                Sistema de RPG Online
                            </p>
                            <p style="margin:0;font-size:11px;color:#666;line-height:1.5;">
                                Este é um email automático, não responda esta mensagem.<br>
                                © {{ date('Y') }} Ambience RPG. Todos os direitos reservados.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
