<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResposta;
use App\Models\TicketAnexo;
use App\Models\TicketRateLimit;
use App\Models\SuporteConfiguracao;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\TicketCriadoMail;
use App\Mail\TicketRespostaMail;
use App\Mail\TicketStatusAlteradoMail;
use App\Mail\TicketAtribuidoMail;
use App\Mail\TicketFechadoMail;
use Illuminate\Support\Facades\Mail;

class SuporteController extends Controller
{
    /**
     * Exibir formulário de criação de ticket
     * GET /suporte/criar
     */
    public function create()
    {
        $usuario = auth()->user();

        // Verificar rate limit
        $bloqueado = TicketRateLimit::estaBloqueado($usuario->id, request()->ip());
        
        if ($bloqueado) {
            $rateLimit = TicketRateLimit::where('usuario_id', $usuario->id)
                ->where('ip_address', request()->ip())
                ->first();

            $minutosRestantes = $rateLimit->getTempoRestante();

            return back()->with('error', "Você excedeu o limite de tickets. Aguarde {$minutosRestantes} minutos.");
        }

        $maxAnexos = SuporteConfiguracao::getValor('max_anexos_por_ticket', 5);
        $tamanhoMaxMb = SuporteConfiguracao::getValor('tamanho_max_anexo_mb', 10);
        $tiposPermitidos = SuporteConfiguracao::getValor('tipos_anexos_permitidos', ['jpg', 'jpeg', 'png', 'pdf']);

        return view('suporte.create', compact('maxAnexos', 'tamanhoMaxMb', 'tiposPermitidos'));
    }

    /**
     * Criar novo ticket
     * POST /suporte
     */
    public function store(Request $request)
    {
        $usuario = auth()->user();

        try {
            // Verificar rate limit
            if (TicketRateLimit::estaBloqueado($usuario->id, $request->ip())) {
                $rateLimit = TicketRateLimit::where('usuario_id', $usuario->id)
                    ->where('ip_address', $request->ip())
                    ->first();

                $minutosRestantes = $rateLimit ? $rateLimit->getTempoRestante() : 60;

                return back()->with('error', "Você excedeu o limite de tickets. Aguarde {$minutosRestantes} minutos.")
                    ->withInput();
            }

            // Validação - ATUALIZADO COM VÍDEOS E MAIS FORMATOS
            $validated = $request->validate([
                'categoria' => 'required|in:duvida,denuncia,problema_tecnico,sugestao,outro',
                'assunto' => 'required|string|max:255|min:5',
                'descricao' => 'required|string|min:20|max:5000',
                'usuario_denunciado_username' => 'nullable|string|exists:usuarios,username',
                'anexos.*' => 'nullable|file|max:102400|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,glb,mp4,webm,mov,avi,xls,xlsx,ppt,pptx,csv' // 100MB, vídeos e documentos
            ], [
                'categoria.required' => 'Por favor, selecione uma categoria.',
                'categoria.in' => 'Categoria inválida.',
                'assunto.required' => 'O assunto é obrigatório.',
                'assunto.min' => 'O assunto deve ter no mínimo 5 caracteres.',
                'assunto.max' => 'O assunto não pode ter mais de 255 caracteres.',
                'descricao.required' => 'A descrição é obrigatória.',
                'descricao.min' => 'A descrição deve ter no mínimo 20 caracteres.',
                'descricao.max' => 'A descrição não pode ter mais de 5000 caracteres.',
                'usuario_denunciado_username.exists' => 'Usuário não encontrado.',
                'anexos.*.max' => 'Cada arquivo não pode ter mais de 100MB.',
                'anexos.*.mimes' => 'Formato não permitido. Aceitos: imagens, vídeos (mp4, webm, mov), documentos (pdf, doc, docx, txt, xls, xlsx, ppt, pptx), zip, glb'
            ]);

            // Se é denúncia, verificar usuário denunciado
            $usuarioDenunciadoId = null;
            if ($validated['categoria'] === 'denuncia') {
                if (!$request->filled('usuario_denunciado_username')) {
                    return back()->withErrors(['usuario_denunciado_username' => 'Para denúncias, é obrigatório informar o usuário.'])->withInput();
                }

                $usuarioDenunciado = Usuario::where('username', $request->usuario_denunciado_username)->first();

                if (!$usuarioDenunciado) {
                    return back()->withErrors(['usuario_denunciado_username' => 'Usuário não encontrado.'])->withInput();
                }

                if ($usuarioDenunciado->isStaff()) {
                    return back()->withErrors(['usuario_denunciado_username' => 'Não é possível denunciar membros da equipe.'])->withInput();
                }

                if ($usuarioDenunciado->id === $usuario->id) {
                    return back()->withErrors(['usuario_denunciado_username' => 'Você não pode denunciar a si mesmo.'])->withInput();
                }

                $usuarioDenunciadoId = $usuarioDenunciado->id;
            }

            // Usar transação para garantir consistência
            DB::beginTransaction();

            try {
                // Criar ticket
                $ticket = Ticket::create([
                    'numero_ticket' => Ticket::gerarNumeroTicket(),
                    'usuario_id' => $usuario->id,
                    'usuario_denunciado_id' => $usuarioDenunciadoId,
                    'categoria' => $validated['categoria'],
                    'assunto' => $validated['assunto'],
                    'descricao' => $validated['descricao'],
                    'status' => 'novo',
                    'prioridade' => $validated['categoria'] === 'denuncia' ? 'alta' : 'normal',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'data_abertura' => now(),
                    'visivel_usuario' => true,
                    'visualizacoes' => 0
                ]);

                // Registrar no histórico
                $ticket->registrarAcao('criado', [
                    'categoria' => $validated['categoria'],
                    'assunto' => $validated['assunto']
                ], $usuario);

                // Upload de anexos
                if ($request->hasFile('anexos')) {
                    $this->processarAnexos($request->file('anexos'), $ticket, $usuario);
                }

                // Registrar tentativa no rate limit
                TicketRateLimit::registrarTentativa($usuario->id, $request->ip());

                // ===== ENVIAR EMAIL - TICKET CRIADO =====
                try {
                    Mail::to($usuario->email)->send(new TicketCriadoMail($ticket));
                    Log::info('Email de criação de ticket enviado', ['ticket_id' => $ticket->id, 'user_email' => $usuario->email]);
                } catch (\Exception $e) {
                    Log::error('Erro ao enviar email de criação', ['error' => $e->getMessage(), 'ticket_id' => $ticket->id]);
                }

                // Notificar staff sobre novo ticket (especialmente denúncias)
                if ($ticket->ehDenuncia()) {
                    $this->notificarStaffNovoTicket($ticket);
                }

                DB::commit();

                Log::info('Ticket criado com sucesso', [
                    'ticket_id' => $ticket->id,
                    'numero_ticket' => $ticket->numero_ticket,
                    'usuario_id' => $usuario->id,
                    'categoria' => $validated['categoria']
                ]);

                return redirect()->route('suporte.show', $ticket->id)
                    ->with('success', 'Ticket criado com sucesso! Número: ' . $ticket->numero_ticket);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao criar ticket', [
                'usuario_id' => $usuario->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erro ao criar ticket. Por favor, tente novamente. Detalhes: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Visualizar lista de tickets do usuário
     * GET /suporte
     */
    public function index(Request $request)
    {
        $usuario = auth()->user();

        // Se é staff, redireciona para painel de moderação
        if ($usuario->isStaff()) {
            return redirect()->route('suporte.moderacao.index');
        }

        // Usuário normal - ver seus próprios tickets
        $tickets = Ticket::where('usuario_id', $usuario->id)
            ->where('visivel_usuario', true)
            ->with(['usuarioDenunciado'])
            ->withCount(['respostas', 'anexos'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalAbertos = Ticket::where('usuario_id', $usuario->id)
            ->abertos()
            ->count();

        $totalFechados = Ticket::where('usuario_id', $usuario->id)
            ->fechados()
            ->count();

        return view('suporte.index', compact('tickets', 'totalAbertos', 'totalFechados'));
    }

    /**
     * Visualizar ticket específico
     * GET /suporte/{id}
     */
    public function show($id)
    {
        $usuario = auth()->user();

        $ticket = Ticket::with([
            'usuario',
            'usuarioDenunciado',
            'atribuidoA',
            'respostas.usuario',
            'respostas.anexos',
            'anexos.usuario',
            'historico.usuario',
            'mensagensAnexadas.mensagem.usuario',
        'mensagensAnexadas.mensagem.anexos'
        ])->findOrFail($id);

        // Verificar permissão
        if (!$ticket->podeVer($usuario)) {
            abort(403, 'Você não tem permissão para visualizar este ticket.');
        }

        // Incrementar visualizações APENAS se for staff
        if ($usuario->isStaff()) {
            $ticket->incrementarVisualizacoes();
        }

        // Filtrar respostas internas (apenas staff vê)
        if (!$usuario->isStaff()) {
            $ticket->setRelation('respostas', $ticket->respostas->where('interno', false));
        }

        return view('suporte.show', compact('ticket'));
    }

    /**
     * Adicionar resposta ao ticket
     * POST /suporte/{id}/responder
     */
    public function responder(Request $request, $id)
    {
        $usuario = auth()->user();

        $ticket = Ticket::findOrFail($id);

        // Verificar permissão
        if (!$ticket->podeVer($usuario)) {
            abort(403, 'Você não tem permissão para responder este ticket.');
        }

        // Verificar se ticket está aberto
        if (!$ticket->estaAberto()) {
            return back()->with('error', 'Não é possível responder um ticket fechado.');
        }

        $validated = $request->validate([
            'mensagem' => 'required|string|min:10|max:5000',
            'interno' => 'nullable|boolean',
            'anexos.*' => 'nullable|file|max:102400|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,glb,mp4,webm,mov,avi,xls,xlsx,ppt,pptx,csv'
        ]);

        try {
            DB::beginTransaction();

            // Apenas staff pode fazer comentários internos
            $interno = $usuario->isStaff() && $request->has('interno') && $request->interno == true;

            // Criar resposta
            $resposta = TicketResposta::create([
                'ticket_id' => $ticket->id,
                'usuario_id' => $usuario->id,
                'mensagem' => $validated['mensagem'],
                'interno' => $interno,
                'ip_address' => $request->ip()
            ]);

            // Upload de anexos
            if ($request->hasFile('anexos')) {
                $this->processarAnexos($request->file('anexos'), $ticket, $usuario, $resposta->id);
            }

            // Atualizar última resposta
            if ($usuario->isStaff()) {
                $ticket->update([
                    'ultima_resposta_staff' => now(),
                    'status' => $ticket->status === 'aguardando_resposta' ? 'em_analise' : $ticket->status
                ]);

                // ===== ENVIAR EMAIL - RESPOSTA DO STAFF =====
                if (!$interno) {
                    try {
                        Mail::to($ticket->usuario->email)->send(new TicketRespostaMail($ticket, $resposta));
                        Log::info('Email de resposta enviado ao usuário', ['ticket_id' => $ticket->id]);
                    } catch (\Exception $e) {
                        Log::error('Erro ao enviar email de resposta', ['error' => $e->getMessage()]);
                    }
                }
            } else {
                $ticket->update([
                    'ultima_resposta_usuario' => now(),
                    'status' => $ticket->status === 'novo' ? 'aguardando_resposta' : $ticket->status
                ]);

                // Notificar staff atribuído sobre resposta do usuário
                if ($ticket->atribuido_a) {
                    $this->notificarStaffRespostaUsuario($ticket, $resposta);
                }
            }

            // Registrar no histórico
            $ticket->registrarAcao('resposta_adicionada', [
                'resposta_por' => $usuario->username,
                'interno' => $interno
            ], $usuario);

            DB::commit();

            Log::info('Resposta adicionada ao ticket', [
                'ticket_id' => $ticket->id,
                'resposta_id' => $resposta->id,
                'usuario_id' => $usuario->id,
                'interno' => $interno
            ]);

            return back()->with('success', 'Resposta adicionada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erro ao adicionar resposta', [
                'ticket_id' => $id,
                'usuario_id' => $usuario->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Erro ao adicionar resposta. Tente novamente.');
        }
    }

    /**
     * Buscar usuários para denúncia (AJAX)
     * GET /suporte/buscar-usuarios
     */
    public function buscarUsuarios(Request $request)
    {
        $termo = $request->input('termo', '');

        if (strlen($termo) < 2) {
            return response()->json([]);
        }

        $usuarios = Usuario::where('status', 'ativo')
            ->whereNotIn('nivel_usuario', ['moderador', 'admin'])
            ->where(function ($query) use ($termo) {
                $query->where('username', 'like', "%{$termo}%")
                    ->orWhere('nickname', 'like', "%{$termo}%");
            })
            ->limit(10)
            ->get(['id', 'username', 'nickname', 'avatar_url']);

        return response()->json($usuarios);
    }

    /**
     * Processar upload de anexos
     */
    private function processarAnexos($arquivos, $ticket, $usuario, $respostaId = null)
    {
        $maxAnexos = SuporteConfiguracao::getValor('max_anexos_por_ticket', 5);
        $anexosAtuais = $ticket->anexos()->count();

        foreach ($arquivos as $index => $arquivo) {
            if ($anexosAtuais + $index >= $maxAnexos) {
                break;
            }

            try {
                $nomeOriginal = $arquivo->getClientOriginalName();
                $extensao = $arquivo->getClientOriginalExtension();
                $nomeArquivo = Str::random(40) . '_' . time() . '.' . $extensao;
                
                // Criar diretório se não existir
                $diretorio = 'suporte/tickets/' . $ticket->id;
                if (!Storage::disk('public')->exists($diretorio)) {
                    Storage::disk('public')->makeDirectory($diretorio);
                }
                
                $caminho = $arquivo->storeAs($diretorio, $nomeArquivo, 'public');
                
                $hashArquivo = hash_file('sha256', $arquivo->getRealPath());

                TicketAnexo::create([
                    'ticket_id' => $ticket->id,
                    'usuario_id' => $usuario->id,
                    'resposta_id' => $respostaId,
                    'nome_original' => $nomeOriginal,
                    'nome_arquivo' => $nomeArquivo,
                    'caminho' => $caminho,
                    'tipo_mime' => $arquivo->getMimeType(),
                    'tamanho' => $arquivo->getSize(),
                    'hash_arquivo' => $hashArquivo
                ]);

                Log::info('Anexo adicionado ao ticket', [
                    'ticket_id' => $ticket->id,
                    'nome_arquivo' => $nomeArquivo,
                    'tamanho' => $arquivo->getSize(),
                    'tipo' => $arquivo->getMimeType()
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao processar anexo', [
                    'ticket_id' => $ticket->id,
                    'arquivo' => $nomeOriginal ?? 'desconhecido',
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Download de anexo
     * GET /suporte/anexos/{id}/download
     */
    public function downloadAnexo($id)
    {
        $anexo = TicketAnexo::findOrFail($id);
        $ticket = $anexo->ticket;
        $usuario = auth()->user();

        // Verificar permissão
        if (!$ticket->podeVer($usuario)) {
            abort(403, 'Você não tem permissão para baixar este anexo.');
        }

        if (!Storage::disk('public')->exists($anexo->caminho)) {
            abort(404, 'Arquivo não encontrado.');
        }

        return Storage::disk('public')->download($anexo->caminho, $anexo->nome_original);
    }

    /**
     * Notificar staff sobre novo ticket (especialmente denúncias)
     */
    private function notificarStaffNovoTicket($ticket)
    {
        try {
            $admins = Usuario::where('nivel_usuario', 'admin')
                ->where('status', 'ativo')
                ->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new \App\Mail\NovoTicketStaffMail($ticket));
            }

            Log::info('Staff notificado sobre novo ticket', ['ticket_id' => $ticket->id]);
        } catch (\Exception $e) {
            Log::error('Erro ao notificar staff', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Notificar staff sobre resposta do usuário
     */
    private function notificarStaffRespostaUsuario($ticket, $resposta)
    {
        try {
            $staff = $ticket->atribuidoA;
            if ($staff && $staff->email) {
                Mail::to($staff->email)->send(new \App\Mail\RespostaUsuarioStaffMail($ticket, $resposta));
                Log::info('Staff notificado sobre resposta do usuário', ['ticket_id' => $ticket->id]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao notificar staff sobre resposta', ['error' => $e->getMessage()]);
        }
    }
}