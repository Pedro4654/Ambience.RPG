<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuário Respondeu - Staff - Ambience RPG</title>
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
                                USUÁRIO RESPONDEU
                            </h1>
                            <p style="margin:8px 0 0 0;font-size:13px;color:#b8e6d5;">Ticket #{{ $ticket->numero_ticket }}</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px 25px;background:#1a1d29;">
                            
                            <p style="margin:0 0 15px 0;font-size:15px;color:#e0e0e0;line-height:1.5;">
                                Olá, <strong style="color:#00ff88;">{{ $ticket->atribuidoA->username }}</strong>
                            </p>

                            <p style="margin:0 0 20px 0;font-size:14px;color:#b0b0b0;line-height:1.6;">
                                O usuário <strong style="color:#00ff88;">{{ $resposta->usuario->username }}</strong> respondeu no ticket atribuído a você!
                            </p>

                            <!-- Box do Ticket -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0f1a14;border:2px dashed #00ff88;border-radius:8px;margin:20px 0;">
                                <tr>
                                    <td style="padding:25px;text-align:center;">
                                        <div style="font-size:13px;color:#00ff88;margin-bottom:10px;font-weight:600;">TICKET</div>
                                        <div style="font-size:42px;font-weight:900;color:#00ff88;letter-spacing:10px;font-family:'Courier New',monospace;">
                                            #{{ $ticket->numero_ticket }}
                                        </div>
                                        <p style="margin:12px 0 0 0;font-size:12px;color:#b0b0b0;">{{ $ticket->assunto }}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Informações -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#232631;border-radius:6px;margin:20px 0;">
                                <tr>
                                    <td style="padding:15px 20px;">
                                        <div style="font-size:14px;color:#00ff88;margin-bottom:10px;font-weight:600;">Informações</div>
                                        <table width="100%" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;">Número:</td>
                                                <td style="font-size:13px;color:#00ff88;text-align:right;font-weight:600;">{{ $ticket->numero_ticket }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;padding-top:5px;">Assunto:</td>
                                                <td style="font-size:13px;color:#e0e0e0;text-align:right;padding-top:5px;">{{ $ticket->assunto }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;padding-top:5px;">Usuário:</td>
                                                <td style="font-size:13px;color:#00ff88;text-align:right;padding-top:5px;font-weight:600;">{{ $resposta->usuario->username }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Resposta -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#232631;border-radius:6px;margin:20px 0;">
                                <tr>
                                    <td style="padding:15px 20px;">
                                        <div style="font-size:14px;color:#00ff88;margin-bottom:10px;font-weight:600;">Nova Resposta</div>
                                        <div style="font-size:13px;color:#e0e0e0;margin-bottom:5px;font-weight:600;">
                                            {{ $resposta->usuario->username }} escreveu:
                                        </div>
                                        <div style="font-size:13px;color:#e0e0e0;line-height:1.7;padding:12px;background:#1a1d29;border-radius:6px;">
                                            {{ $resposta->mensagem }}
                                        </div>
                                        <p style="margin:10px 0 0 0;font-size:11px;color:#888;">
                                            {{ $resposta->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Botão -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:20px 0 0 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('suporte.show', $ticket->id) }}" style="display:inline-block;padding:14px 32px;background:linear-gradient(135deg,#00ff88 0%,#00cc66 100%);color:#0a0e1a;text-decoration:none;border-radius:6px;font-weight:700;font-size:14px;">
                                            Ver e Responder
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Aviso -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0f1a14;border-left:3px solid #00ff88;border-radius:4px;margin:20px 0 0 0;">
                                <tr>
                                    <td style="padding:12px 15px;">
                                        <p style="margin:0;font-size:12px;color:#b0b0b0;line-height:1.6;">
                                            O usuário está aguardando sua resposta. Responda o mais breve possível!
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
