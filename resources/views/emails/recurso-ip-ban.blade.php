<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recurso de IP Ban - Ambience RPG</title>
</head>
<body style="margin:0;padding:0;background-color:#0f1117;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;">
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0f1117;padding:20px;">
        <tr>
            <td align="center">
                
                <table width="500" cellpadding="0" cellspacing="0" border="0" style="background:#1a1d29;border-radius:8px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.3);">
                    
                    <!-- Header Vermelho -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#991b1b 0%,#7f1d1d 100%);padding:30px 25px;text-align:center;">
                            <h1 style="margin:0;font-size:20px;color:#ffffff;font-weight:700;letter-spacing:0.5px;text-transform:uppercase;">
                                RECURSO DE IP BAN
                            </h1>
                            <p style="margin:8px 0 0 0;font-size:13px;color:#fca5a5;">Solicitação recebida</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px 25px;background:#1a1d29;">
                            
                            <p style="margin:0 0 15px 0;font-size:15px;color:#e0e0e0;line-height:1.5;">
                                Olá,
                            </p>

                            <p style="margin:0 0 20px 0;font-size:14px;color:#b0b0b0;line-height:1.6;">
                                Seu recurso de IP Ban foi recebido com sucesso! Nossa equipe de moderação irá analisar seu caso detalhadamente.
                            </p>

                            <!-- Box do Número -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#1f0f0f;border:2px dashed #ff4444;border-radius:8px;margin:20px 0;">
                                <tr>
                                    <td style="padding:25px;text-align:center;">
                                        <div style="font-size:13px;color:#ff4444;margin-bottom:10px;font-weight:600;">NÚMERO DO RECURSO</div>
                                        <div style="font-size:42px;font-weight:900;color:#ff4444;letter-spacing:10px;font-family:'Courier New',monospace;">
                                            {{ $recurso->numero_ticket }}
                                        </div>
                                        <p style="margin:12px 0 0 0;font-size:12px;color:#b0b0b0;">Guarde este número para acompanhar seu recurso</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Dados do Recurso -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#232631;border-radius:6px;margin:20px 0;">
                                <tr>
                                    <td style="padding:15px 20px;">
                                        <div style="font-size:14px;color:#ff4444;margin-bottom:10px;font-weight:600;">Dados do Recurso</div>
                                        <table width="100%" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;">Nome:</td>
                                                <td style="font-size:13px;color:#e0e0e0;text-align:right;">{{ $recurso->nome }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;padding-top:5px;">Email:</td>
                                                <td style="font-size:13px;color:#e0e0e0;text-align:right;padding-top:5px;">{{ $recurso->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;padding-top:5px;">IP Banido:</td>
                                                <td style="font-size:13px;color:#ff4444;text-align:right;padding-top:5px;font-weight:600;">{{ $recurso->ip_banido }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;padding-top:5px;">Data:</td>
                                                <td style="font-size:13px;color:#e0e0e0;text-align:right;padding-top:5px;">{{ $recurso->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;padding-top:5px;">Status:</td>
                                                <td style="font-size:13px;color:#ffcc00;text-align:right;padding-top:5px;font-weight:600;">{{ ucfirst($recurso->status) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Motivo -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#232631;border-radius:6px;margin:20px 0;">
                                <tr>
                                    <td style="padding:15px 20px;">
                                        <div style="font-size:14px;color:#ff4444;margin-bottom:10px;font-weight:600;">Seu Relato</div>
                                        <div style="font-size:13px;color:#e0e0e0;line-height:1.7;">
                                            {{ $recurso->motivo }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Contato -->
                            @if($recurso->username_discord || $recurso->telefone)
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#232631;border-radius:6px;margin:20px 0;">
                                <tr>
                                    <td style="padding:15px 20px;">
                                        <div style="font-size:14px;color:#ff4444;margin-bottom:10px;font-weight:600;">Informações de Contato</div>
                                        <table width="100%" cellpadding="5" cellspacing="0">
                                            @if($recurso->username_discord)
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;">Discord:</td>
                                                <td style="font-size:13px;color:#e0e0e0;text-align:right;">{{ $recurso->username_discord }}</td>
                                            </tr>
                                            @endif
                                            @if($recurso->telefone)
                                            <tr>
                                                <td style="font-size:13px;color:#b0b0b0;padding-top:5px;">Telefone:</td>
                                                <td style="font-size:13px;color:#e0e0e0;text-align:right;padding-top:5px;">{{ $recurso->telefone }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Próximos Passos -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#2a2416;border-radius:6px;margin:20px 0;">
                                <tr>
                                    <td style="padding:15px 20px;">
                                        <div style="font-size:14px;color:#ffcc00;margin-bottom:8px;font-weight:600;">Próximos Passos</div>
                                        <ul style="margin:0;padding-left:18px;color:#e0e0e0;font-size:12px;line-height:1.8;">
                                            <li>Nossa equipe irá analisar seu caso detalhadamente</li>
                                            <li>O prazo de resposta é de até 7 dias úteis</li>
                                            <li>Você receberá uma notificação por email sobre a decisão</li>
                                            <li>Em caso de dúvidas, use o número do recurso para consultas</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>

                            <!-- Aviso -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0f1a14;border-left:3px solid #00ff88;border-radius:4px;margin:20px 0 0 0;">
                                <tr>
                                    <td style="padding:12px 15px;">
                                        <p style="margin:0;font-size:12px;color:#b0b0b0;line-height:1.6;">
                                            <strong style="color:#00ff88;">Como acompanhar:</strong> Acesse nosso sistema de suporte e insira o número do recurso {{ $recurso->numero_ticket }} para verificar o status.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Aviso Importante -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#2a1416;border-radius:6px;margin:20px 0 0 0;">
                                <tr>
                                    <td style="padding:15px 20px;">
                                        <div style="font-size:14px;color:#ff4444;margin-bottom:8px;font-weight:600;">Aviso Importante</div>
                                        <ul style="margin:0;padding-left:18px;color:#ffaaaa;font-size:12px;line-height:1.8;">
                                            <li>Este é um processo de recurso único</li>
                                            <li>Envio de múltiplos recursos pode resultar em desconsideração</li>
                                            <li>Seja honesto e detalhado em seu relato</li>
                                            <li>A decisão da equipe de moderação é final</li>
                                        </ul>
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
