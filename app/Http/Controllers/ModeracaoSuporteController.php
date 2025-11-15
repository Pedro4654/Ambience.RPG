<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketAtribuidoMail;
use App\Mail\TicketStatusAlteradoMail;
use App\Mail\TicketFechadoMail;
use App\Mail\TicketPrioridadeAlteradaMail;

class ModeracaoSuporteController extends Controller
{
    /**
     * Painel de moderação
     * GET /suporte/moderacao
     */
    public function index(Request $request)
    {
        $usuario = auth()->user();

        // Verificar se é staff
        if (!$usuario->isStaff()) {
            abort(403, 'Acesso negado.');
        }

        $filtro = $request->input('filtro', 'todos');
        $busca = $request->input('busca', '');
        $prioridade = $request->input('prioridade', '');

        $query = Ticket::with(['usuario', 'atribuidoA', 'usuarioDenunciado'])
            ->orderBy('created_at', 'desc');

        // Aplicar filtros
        switch ($filtro) {
            case 'novos':
                $query->novos();
                break;
            case 'denuncias':
                $query->denuncias();
                break;
            case 'meus':
                $query->atribuidosA($usuario->id);
                break;
            case 'pendentes':
                $query->whereIn('status', ['pendente', 'aguardando_resposta']);
                break;
            case 'resolvidos':
                $query->where('status', 'resolvido');
                break;
            case 'fechados':
                $query->where('status', 'fechado');
                break;
            case 'spam':
                $query->spam();
                break;
        }

        // Filtro de prioridade
        if ($prioridade) {
            $query->porPrioridade($prioridade);
        }

        // Busca inteligente
        if ($busca) {
            if (preg_match('/^categoria:(.+)$/i', $busca, $matches)) {
                $categoria = trim($matches[1]);
                $query->where('categoria', $categoria);
            } elseif (preg_match('/^status:(.+)$/i', $busca, $matches)) {
                $status = trim($matches[1]);
                $query->where('status', $status);
            } elseif (preg_match('/^prioridade:(.+)$/i', $busca, $matches)) {
                $prioridadeBusca = trim($matches[1]);
                $query->where('prioridade', $prioridadeBusca);
            } elseif (preg_match('/^usuario:(.+)$/i', $busca, $matches)) {
                $username = trim($matches[1]);
                $query->whereHas('usuario', function ($userQuery) use ($username) {
                    $userQuery->where('username', 'like', "%{$username}%");
                });
            } elseif (preg_match('/^#?(\d+)$/', $busca, $matches)) {
                $numero = $matches[1];
                $query->where('numero_ticket', 'like', "%{$numero}%");
            } else {
                $query->where(function ($q) use ($busca) {
                    $q->where('numero_ticket', 'like', "%{$busca}%")
                        ->orWhere('assunto', 'like', "%{$busca}%")
                        ->orWhereHas('usuario', function ($userQuery) use ($busca) {
                            $userQuery->where('username', 'like', "%{$busca}%");
                        });
                });
            }
        }

        $tickets = $query->paginate(20);

        // Estatísticas
        $stats = [
            'total' => Ticket::count(),
            'novos' => Ticket::novos()->count(),
            'meus' => Ticket::atribuidosA($usuario->id)->abertos()->count(),
            'denuncias' => Ticket::denuncias()->abertos()->count(),
            'pendentes' => Ticket::whereIn('status', ['pendente', 'aguardando_resposta'])->count(),
            'spam' => Ticket::spam()->count()
        ];

        // Staff disponível para atribuição
        $staffDisponiveis = Usuario::whereIn('nivel_usuario', ['moderador', 'admin'])
            ->where('status', 'ativo')
            ->orderBy('username')
            ->get(['id', 'username', 'nivel_usuario']);

        return view('suporte.moderacao.index', compact('tickets', 'stats', 'filtro', 'staffDisponiveis'));
    }

    /**
     * Atribuir ticket a um staff
     * POST /suporte/moderacao/{id}/atribuir
     */
    public function atribuir(Request $request, $id)
    {
        $usuario = auth()->user();

        if (!$usuario->isStaff()) {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
            'staff_id' => 'required|exists:usuarios,id'
        ]);

        $ticket = Ticket::findOrFail($id);
        $staff = Usuario::findOrFail($validated['staff_id']);

        if (!$staff->isStaff()) {
            return back()->with('error', 'Apenas staff pode ser atribuído a tickets.');
        }

        try {
            $ticket->atribuir($staff, $usuario);

            // ===== ENVIAR EMAIL - TICKET ATRIBUÍDO =====
            try {
                Mail::to($staff->email)->send(new TicketAtribuidoMail($ticket, $staff));
                Log::info('Email de atribuição enviado', ['ticket_id' => $ticket->id, 'staff_email' => $staff->email]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de atribuição', ['error' => $e->getMessage()]);
            }

            Log::info('Ticket atribuído via painel', [
                'ticket_id' => $ticket->id,
                'staff_id' => $staff->id,
                'atribuido_por' => $usuario->id
            ]);

            return back()->with('success', "Ticket atribuído a {$staff->username} com sucesso!");

        } catch (\Exception $e) {
            Log::error('Erro ao atribuir ticket', [
                'ticket_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Erro ao atribuir ticket.');
        }
    }

    /**
     * Alterar status do ticket
     * POST /suporte/moderacao/{id}/status
     */
    public function alterarStatus(Request $request, $id)
    {
        $usuario = auth()->user();

        if (!$usuario->isStaff()) {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
            'status' => 'required|in:novo,pendente,em_analise,aguardando_resposta,resolvido,fechado,spam',
            'observacao' => 'nullable|string|max:500'
        ]);

        $ticket = Ticket::findOrFail($id);

        try {
            $statusAntigo = $ticket->status;
            $ticket->alterarStatus($validated['status'], $usuario, $validated['observacao'] ?? null);

            // ===== ENVIAR EMAIL - STATUS ALTERADO =====
            try {
                Mail::to($ticket->usuario->email)->send(new TicketStatusAlteradoMail($ticket, $statusAntigo, $validated['status']));
                Log::info('Email de alteração de status enviado', ['ticket_id' => $ticket->id]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de status', ['error' => $e->getMessage()]);
            }

            Log::info('Status alterado via painel', [
                'ticket_id' => $ticket->id,
                'status_novo' => $validated['status'],
                'alterado_por' => $usuario->id
            ]);

            return back()->with('success', 'Status alterado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao alterar status', [
                'ticket_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Erro ao alterar status.');
        }
    }

    /**
     * Alterar prioridade do ticket
     * POST /suporte/moderacao/{id}/prioridade
     */
    public function alterarPrioridade(Request $request, $id)
    {
        $usuario = auth()->user();

        if (!$usuario->isStaff()) {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
            'prioridade' => 'required|in:baixa,normal,alta,urgente',
            'observacao' => 'nullable|string|max:500'
        ]);

        $ticket = Ticket::findOrFail($id);

        try {
            $prioridadeAnterior = $ticket->prioridade;
            $ticket->alterarPrioridade($validated['prioridade'], $usuario, $validated['observacao'] ?? null);

            // ===== ENVIAR EMAIL - PRIORIDADE ALTERADA =====
            try {
                Mail::to($ticket->usuario->email)->send(new TicketPrioridadeAlteradaMail($ticket, $prioridadeAnterior, $validated['prioridade']));
                Log::info('Email de alteração de prioridade enviado', ['ticket_id' => $ticket->id]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de prioridade', ['error' => $e->getMessage()]);
            }

            Log::info('Prioridade alterada via painel', [
                'ticket_id' => $ticket->id,
                'prioridade_nova' => $validated['prioridade'],
                'alterado_por' => $usuario->id
            ]);

            return back()->with('success', 'Prioridade alterada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao alterar prioridade', [
                'ticket_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Erro ao alterar prioridade.');
        }
    }

    /**
     * Fechar ticket
     * POST /suporte/moderacao/{id}/fechar
     */
    public function fechar(Request $request, $id)
    {
        $usuario = auth()->user();

        if (!$usuario->isStaff()) {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
            'observacao' => 'nullable|string|max:500'
        ]);

        $ticket = Ticket::findOrFail($id);

        try {
            $ticket->alterarStatus('fechado', $usuario, $validated['observacao'] ?? null);

            // ===== ENVIAR EMAIL - TICKET FECHADO =====
            try {
                Mail::to($ticket->usuario->email)->send(new TicketFechadoMail($ticket, $validated['observacao'] ?? null));
                Log::info('Email de fechamento enviado', ['ticket_id' => $ticket->id]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de fechamento', ['error' => $e->getMessage()]);
            }

            Log::info('Ticket fechado via painel', [
                'ticket_id' => $ticket->id,
                'fechado_por' => $usuario->id
            ]);

            return back()->with('success', 'Ticket fechado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao fechar ticket', [
                'ticket_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Erro ao fechar ticket.');
        }
    }

    /**
     * Reabrir ticket
     * POST /suporte/moderacao/{id}/reabrir
     */
    public function reabrir(Request $request, $id)
    {
        $usuario = auth()->user();

        if (!$usuario->isStaff()) {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
            'observacao' => 'nullable|string|max:500'
        ]);

        $ticket = Ticket::findOrFail($id);

        try {
            $ticket->alterarStatus('em_analise', $usuario, $validated['observacao'] ?? 'Ticket reaberto');
            
            $ticket->update(['data_fechamento' => null]);

            $ticket->registrarAcao('reaberto', [], $usuario, $validated['observacao'] ?? null);

            // ===== ENVIAR EMAIL - TICKET REABERTO =====
            try {
                Mail::to($ticket->usuario->email)->send(new \App\Mail\TicketReabertoMail($ticket, $validated['observacao'] ?? null));
                Log::info('Email de reabertura enviado', ['ticket_id' => $ticket->id]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de reabertura', ['error' => $e->getMessage()]);
            }

            Log::info('Ticket reaberto via painel', [
                'ticket_id' => $ticket->id,
                'reaberto_por' => $usuario->id
            ]);

            return back()->with('success', 'Ticket reaberto com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao reabrir ticket', [
                'ticket_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Erro ao reabrir ticket.');
        }
    }

    /**
     * Marcar como spam
     * POST /suporte/moderacao/{id}/marcar-spam
     */
    public function marcarSpam(Request $request, $id)
    {
        $usuario = auth()->user();

        if (!$usuario->isStaff()) {
            abort(403, 'Acesso negado.');
        }

        $ticket = Ticket::findOrFail($id);

        try {
            $ticket->alterarStatus('spam', $usuario, 'Marcado como spam');
            $ticket->update(['visivel_usuario' => false]);

            Log::info('Ticket marcado como spam', [
                'ticket_id' => $ticket->id,
                'marcado_por' => $usuario->id
            ]);

            return back()->with('success', 'Ticket marcado como spam!');

        } catch (\Exception $e) {
            Log::error('Erro ao marcar como spam', [
                'ticket_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Erro ao marcar como spam.');
        }
    }

    /**
     * Dashboard com estatísticas gerais
     * GET /suporte/moderacao/dashboard
     */
    public function dashboard()
    {
        $usuario = auth()->user();

        if (!$usuario->isStaff()) {
            abort(403, 'Acesso negado.');
        }

        // Estatísticas gerais
        $stats = [
            'total_tickets' => Ticket::count(),
            'tickets_abertos' => Ticket::abertos()->count(),
            'tickets_novos' => Ticket::novos()->count(),
            'denuncias_pendentes' => Ticket::denuncias()->abertos()->count(),
            'meus_tickets' => Ticket::atribuidosA($usuario->id)->abertos()->count(),
            'aguardando_resposta' => Ticket::where('status', 'aguardando_resposta')->count(),
            'urgentes' => Ticket::porPrioridade('urgente')->abertos()->count()
        ];

        // Tickets por categoria
        $porCategoria = Ticket::selectRaw('categoria, COUNT(*) as total')
            ->groupBy('categoria')
            ->pluck('total', 'categoria')
            ->toArray();

        // Tickets por status
        $porStatus = Ticket::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Últimos tickets
        $ultimosTickets = Ticket::with(['usuario', 'atribuidoA'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Staff com mais tickets atribuídos
        $staffAtivo = Usuario::whereIn('nivel_usuario', ['moderador', 'admin'])
            ->withCount(['ticketsAtribuidos as tickets_abertos_count' => function ($query) {
                $query->abertos();
            }])
            ->having('tickets_abertos_count', '>', 0)
            ->orderBy('tickets_abertos_count', 'desc')
            ->limit(5)
            ->get();

        return view('suporte.moderacao.dashboard', compact(
            'stats',
            'porCategoria',
            'porStatus',
            'ultimosTickets',
            'staffAtivo'
        ));
    }
}