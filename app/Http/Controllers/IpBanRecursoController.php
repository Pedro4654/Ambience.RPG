<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResposta;
use App\Models\TicketAnexo;
use App\Models\DeviceFingerprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCriadoMail;

/**
 * Controller para gerenciar tickets de recurso de IP Ban
 * Permite que usuários banidos por IP criem tickets SEM autenticação
 */
class IpBanRecursoController extends Controller
{
    /**
     * Exibir formulário público para recurso de IP Ban
     * GET /ip-ban/recurso
     */
    public function showRecursoForm(Request $request)
    {
        // Verificar se realmente está banido por IP
        $fingerprint = DeviceFingerprint::generateFingerprint($request);
        
        $deviceBanido = DeviceFingerprint::where('fingerprint', $fingerprint)
            ->where('conta_criada_neste_dispositivo', true)
            ->whereHas('usuario', function ($query) {
                $query->where('ip_ban_ativo', true);
            })
            ->with('usuario')
            ->first();

        if (!$deviceBanido) {
            return redirect()->route('home')
                ->with('error', 'Você não está sob IP ban.');
        }

        $usuarioBanido = $deviceBanido->usuario;

        return view('ip-ban.recurso-form', compact('usuarioBanido', 'deviceBanido'));
    }

    /**
     * Processar envio de recurso de IP Ban
     * POST /ip-ban/recurso
     */
    public function submitRecurso(Request $request)
    {
        try {
            // Verificar se realmente está banido
            $fingerprint = DeviceFingerprint::generateFingerprint($request);
            
            $deviceBanido = DeviceFingerprint::where('fingerprint', $fingerprint)
                ->where('conta_criada_neste_dispositivo', true)
                ->whereHas('usuario', function ($query) {
                    $query->where('ip_ban_ativo', true);
                })
                ->with('usuario')
                ->first();

            if (!$deviceBanido) {
                return back()->with('error', 'Você não está sob IP ban.');
            }

            $usuarioBanido = $deviceBanido->usuario;

            // Verificar se já não tem ticket de recurso pendente
            $ticketExistente = Ticket::where('usuario_id', $usuarioBanido->id)
                ->where('categoria', 'recurso_ip_ban')
                ->whereIn('status', ['novo', 'em_analise', 'aguardando_resposta'])
                ->first();

            if ($ticketExistente) {
                return back()->with('error', 'Você já possui um recurso de IP ban em análise. Número: #' . $ticketExistente->numero_ticket);
            }

            // Validação
            $validated = $request->validate([
                'email' => 'required|email|max:100',
                'assunto' => 'required|string|max:255|min:10',
                'descricao' => 'required|string|min:50|max:5000',
                'anexos.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,txt'
            ], [
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'Digite um email válido.',
                'assunto.required' => 'O assunto é obrigatório.',
                'assunto.min' => 'O assunto deve ter no mínimo 10 caracteres.',
                'descricao.required' => 'A descrição é obrigatória.',
                'descricao.min' => 'A descrição deve ter no mínimo 50 caracteres.',
                'anexos.*.max' => 'Cada arquivo não pode ter mais de 10MB.',
                'anexos.*.mimes' => 'Formato não permitido. Aceitos: jpg, png, pdf, doc, docx, txt'
            ]);

            DB::beginTransaction();

            // Criar ticket especial de recurso
            $ticket = Ticket::create([
                'numero_ticket' => Ticket::gerarNumeroTicket(),
                'usuario_id' => $usuarioBanido->id,
                'categoria' => 'recurso_ip_ban',
                'assunto' => '[RECURSO IP BAN] ' . $validated['assunto'],
                'descricao' => "📧 Email de contato: {$validated['email']}\n\n" . $validated['descricao'],
                'status' => 'novo',
                'prioridade' => 'urgente',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data_abertura' => now(),
                'visivel_usuario' => true,
                'visualizacoes' => 0
            ]);

            // Registrar no histórico
            $ticket->registrarAcao('criado', [
                'categoria' => 'recurso_ip_ban',
                'tipo' => 'recurso_publico',
                'fingerprint' => $fingerprint
            ], $usuarioBanido);

            // Upload de anexos
            if ($request->hasFile('anexos')) {
                $this->processarAnexos($request->file('anexos'), $ticket, $usuarioBanido);
            }

            // Enviar email de confirmação
            try {
                Mail::to($validated['email'])->send(new TicketCriadoMail($ticket));
                Log::info('Email de recurso IP ban enviado', ['ticket_id' => $ticket->id, 'email' => $validated['email']]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de recurso', ['error' => $e->getMessage()]);
            }

            // Notificar admins
            $this->notificarAdminsRecursoIpBan($ticket, $validated['email']);

            DB::commit();

            Log::info('Recurso de IP ban criado', [
                'ticket_id' => $ticket->id,
                'usuario_id' => $usuarioBanido->id,
                'email' => $validated['email']
            ]);

            return view('ip-ban.recurso-sucesso', compact('ticket'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar recurso de IP ban', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erro ao enviar recurso. Tente novamente.')->withInput();
        }
    }

    /**
     * Processar upload de anexos
     */
    private function processarAnexos($arquivos, $ticket, $usuario)
    {
        foreach ($arquivos as $arquivo) {
            try {
                $nomeOriginal = $arquivo->getClientOriginalName();
                $extensao = $arquivo->getClientOriginalExtension();
                $nomeArquivo = Str::random(40) . '_' . time() . '.' . $extensao;
                
                $diretorio = 'suporte/tickets/' . $ticket->id;
                if (!Storage::disk('public')->exists($diretorio)) {
                    Storage::disk('public')->makeDirectory($diretorio);
                }
                
                $caminho = $arquivo->storeAs($diretorio, $nomeArquivo, 'public');
                $hashArquivo = hash_file('sha256', $arquivo->getRealPath());

                TicketAnexo::create([
                    'ticket_id' => $ticket->id,
                    'usuario_id' => $usuario->id,
                    'resposta_id' => null,
                    'nome_original' => $nomeOriginal,
                    'nome_arquivo' => $nomeArquivo,
                    'caminho' => $caminho,
                    'tipo_mime' => $arquivo->getMimeType(),
                    'tamanho' => $arquivo->getSize(),
                    'hash_arquivo' => $hashArquivo
                ]);

                Log::info('Anexo adicionado ao recurso IP ban', [
                    'ticket_id' => $ticket->id,
                    'arquivo' => $nomeOriginal
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao processar anexo de recurso', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Notificar admins sobre novo recurso de IP ban
     */
    private function notificarAdminsRecursoIpBan($ticket, $email)
    {
        try {
            $admins = \App\Models\Usuario::where('nivel_usuario', 'admin')
                ->where('status', 'ativo')
                ->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new \App\Mail\RecursoIpBanMail($ticket, $email));
            }

            Log::info('Admins notificados sobre recurso IP ban', ['ticket_id' => $ticket->id]);
        } catch (\Exception $e) {
            Log::error('Erro ao notificar admins sobre recurso', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Verificar status do recurso (público)
     * GET /ip-ban/recurso/status/{numero_ticket}
     */
    public function verificarStatus(Request $request, $numeroTicket)
    {
        $ticket = Ticket::where('numero_ticket', $numeroTicket)
            ->where('categoria', 'recurso_ip_ban')
            ->with(['respostas' => function($query) {
                $query->where('interno', false)->orderBy('created_at', 'desc');
            }])
            ->first();

        if (!$ticket) {
            return back()->with('error', 'Ticket não encontrado.');
        }

        // Verificar se está acessando do mesmo dispositivo
        $fingerprint = DeviceFingerprint::generateFingerprint($request);
        $deviceBanido = DeviceFingerprint::where('fingerprint', $fingerprint)
            ->where('usuario_id', $ticket->usuario_id)
            ->where('conta_criada_neste_dispositivo', true)
            ->first();

        if (!$deviceBanido) {
            return back()->with('error', 'Você não tem permissão para visualizar este recurso.');
        }

        return view('ip-ban.recurso-status', compact('ticket'));
    }
}