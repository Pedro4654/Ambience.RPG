<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\DeviceFingerprint;
use App\Models\Ticket;
use App\Models\TicketResposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ModeracaoUsuarioController extends Controller
{
    /**
     * ========================================
     * DASHBOARD DE MODERAÇÃO
     * ========================================
     */
    public function dashboard()
    {
        if (!Auth::user()->isStaff()) {
            abort(403);
        }

        // Estatísticas gerais
        $stats = [
            'total_usuarios' => Usuario::where('status', 'ativo')->count(),
            'usuarios_punidos' => Usuario::where(function($q) {
                $q->where('warning_ativo', true)
                  ->orWhereNotNull('ban_tipo')
                  ->orWhere('ip_ban_ativo', true)
                  ->orWhereNotNull('account_deleted_at');
            })->count(),
            'warnings_ativos' => Usuario::where('warning_ativo', true)->count(),
            'bans_temporarios' => Usuario::where('ban_tipo', 'temporario')->count(),
            'perma_bans' => Usuario::where('ban_tipo', 'permanente')->count(),
            'ip_bans' => Usuario::where('ip_ban_ativo', true)->count(),
            'contas_deletadas' => Usuario::whereNotNull('account_deleted_at')->count(),
            'usuarios_cadastrados_hoje' => Usuario::whereDate('data_criacao', Carbon::today())->count(),
            'usuarios_cadastrados_semana' => Usuario::whereBetween('data_criacao', [Carbon::now()->startOfWeek(), Carbon::now()])->count(),
        ];

        // Punições recentes
        $punicoesRecentes = Usuario::where(function($q) {
            $q->where('warning_ativo', true)
              ->orWhereNotNull('ban_tipo')
              ->orWhere('ip_ban_ativo', true);
        })
        ->where(function($q) {
            $q->where('warning_data', '>=', Carbon::now()->subDays(30))
              ->orWhere('ban_inicio', '>=', Carbon::now()->subDays(30))
              ->orWhere('ip_ban_data', '>=', Carbon::now()->subDays(30));
        })
        ->with(['warningAplicadoPor', 'banAplicadoPor', 'ipBanAplicadoPor'])
        ->orderBy('id', 'desc')
        ->limit(10)
        ->get();

        // Moderadores mais ativos
        $moderadoresAtivos = Usuario::whereIn('nivel_usuario', ['moderador', 'admin'])
            ->withCount([
                'warningsAplicados as total_warnings',
                'bansAplicados as total_bans'
            ])
            ->having('total_warnings', '>', 0)
            ->orHaving('total_bans', '>', 0)
            ->orderByDesc('total_warnings')
            ->orderByDesc('total_bans')
            ->limit(5)
            ->get();

        // Usuários recentemente cadastrados
        $usuariosRecentes = Usuario::where('status', 'ativo')
            ->orderBy('data_criacao', 'desc')
            ->limit(10)
            ->get();

        // Denúncias pendentes
        $denunciasPendentes = Ticket::where('categoria', 'denuncia')
            ->whereIn('status', ['novo', 'em_analise'])
            ->with(['usuario', 'usuarioDenunciado'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('moderacao.usuarios.dashboard', compact(
            'stats',
            'punicoesRecentes',
            'moderadoresAtivos',
            'usuariosRecentes',
            'denunciasPendentes'
        ));
    }

    /**
     * ========================================
     * LISTAR USUÁRIOS (CRUD)
     * ========================================
     */
    public function index(Request $request)
    {
        if (!Auth::user()->isStaff()) {
            abort(403);
        }

        $query = Usuario::query();
        $filtro = $request->input('filtro', 'todos');
        
        switch ($filtro) {
            case 'punidos':
                $query->where(function($q) {
                    $q->where('warning_ativo', true)
                      ->orWhereNotNull('ban_tipo')
                      ->orWhere('ip_ban_ativo', true)
                      ->orWhereNotNull('account_deleted_at');
                });
                break;
            case 'warning':
                $query->where('warning_ativo', true);
                break;
            case 'ban':
                $query->whereNotNull('ban_tipo');
                break;
            case 'ip_ban':
                $query->where('ip_ban_ativo', true);
                break;
            case 'deleted':
                $query->whereNotNull('account_deleted_at');
                break;
            case 'staff':
                $query->whereIn('nivel_usuario', ['moderador', 'admin']);
                break;
            case 'ativos':
                $query->where('status', 'ativo')
                     ->whereNull('ban_tipo')
                     ->where('warning_ativo', false);
                break;
        }

        // Busca
        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
                $q->where('username', 'like', "%{$busca}%")
                  ->orWhere('email', 'like', "%{$busca}%")
                  ->orWhere('nickname', 'like', "%{$busca}%");
            });
        }

        $usuarios = $query->with([
            'warningAplicadoPor',
            'banAplicadoPor',
            'ipBanAplicadoPor'
        ])
        ->withCount(['tickets', 'denunciasRecebidas'])
        ->orderBy('data_criacao', 'desc')
        ->paginate(20);

        // Stats para filtros
        $stats = [
            'total' => Usuario::count(),
            'punidos' => Usuario::where(function($q) {
                $q->where('warning_ativo', true)
                  ->orWhereNotNull('ban_tipo')
                  ->orWhere('ip_ban_ativo', true);
            })->count(),
            'warning' => Usuario::where('warning_ativo', true)->count(),
            'ban' => Usuario::whereNotNull('ban_tipo')->count(),
            'ip_ban' => Usuario::where('ip_ban_ativo', true)->count(),
            'deleted' => Usuario::whereNotNull('account_deleted_at')->count(),
            'staff' => Usuario::whereIn('nivel_usuario', ['moderador', 'admin'])->count(),
        ];

        return view('moderacao.usuarios.index', compact('usuarios', 'stats', 'filtro'));
    }

    /**
     * ========================================
     * VISUALIZAR USUÁRIO DETALHADO
     * ========================================
     */
    public function show($id)
    {
        if (!Auth::user()->isStaff()) {
            abort(403);
        }

        $usuario = Usuario::with([
            'warningAplicadoPor',
            'banAplicadoPor',
            'ipBanAplicadoPor',
            'accountDeletedPor',
            'deviceFingerprints'
        ])
        ->withCount([
            'tickets',
            'denunciasRecebidas',
            'posts',
            'comentarios'
        ])
        ->findOrFail($id);

        // Tickets do usuário
        $tickets = Ticket::where('usuario_id', $id)
            ->orWhere('usuario_denunciado_id', $id)
            ->with(['usuario', 'atribuidoA'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Histórico de punições
        $historicoPunicoes = $this->getHistoricoPunicoes($usuario);

        // Contas associadas
        $contasAssociadas = collect([]);
        if ($usuario->deviceFingerprints->count() > 0) {
            $fingerprints = $usuario->deviceFingerprints->pluck('fingerprint')->toArray();
            
            $contasAssociadas = Usuario::whereHas('deviceFingerprints', function($q) use ($fingerprints) {
                $q->whereIn('fingerprint', $fingerprints);
            })
            ->where('id', '!=', $usuario->id)
            ->limit(10)
            ->get();
        }

        return view('moderacao.usuarios.show', compact(
            'usuario',
            'tickets',
            'historicoPunicoes',
            'contasAssociadas'
        ));
    }

    /**
     * Helper: Obter histórico de punições formatado
     */
    private function getHistoricoPunicoes($usuario)
    {
        $historico = [];

        // Warning
        if ($usuario->warning_ativo || $usuario->warning_data) {
            $historico[] = [
                'tipo' => 'Warning',
                'ativo' => $usuario->warning_ativo,
                'motivo' => $usuario->warning_motivo,
                'data' => $usuario->warning_data,
                'aplicado_por' => $usuario->warningAplicadoPor,
                'cor' => 'yellow'
            ];
        }

        // Ban
        if ($usuario->ban_tipo) {
            $historico[] = [
                'tipo' => $usuario->ban_tipo === 'temporario' ? 'Ban Temporário' : 'Ban Permanente',
                'ativo' => true,
                'motivo' => $usuario->ban_motivo,
                'data' => $usuario->ban_inicio,
                'data_fim' => $usuario->ban_fim,
                'aplicado_por' => $usuario->banAplicadoPor,
                'cor' => 'red'
            ];
        }

        // IP Ban
        if ($usuario->ip_ban_ativo) {
            $historico[] = [
                'tipo' => 'IP Ban',
                'ativo' => true,
                'motivo' => $usuario->ip_ban_motivo,
                'data' => $usuario->ip_ban_data,
                'aplicado_por' => $usuario->ipBanAplicadoPor,
                'cor' => 'red'
            ];
        }

        // Account Deleted
        if ($usuario->account_deleted_at) {
            $historico[] = [
                'tipo' => 'Conta Deletada',
                'ativo' => true,
                'motivo' => $usuario->account_deleted_motivo,
                'data' => $usuario->account_deleted_at,
                'data_fim' => $usuario->account_hard_delete_at,
                'aplicado_por' => $usuario->accountDeletedPor,
                'cor' => 'gray'
            ];
        }

        return collect($historico)->sortByDesc('data');
    }

    /**
     * ========================================
     * EDITAR USUÁRIO (APENAS STAFF)
     * ========================================
     */
    public function edit($id)
    {
        if (!Auth::user()->isStaff()) {
            abort(403);
        }

        $usuario = Usuario::findOrFail($id);
        return view('moderacao.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isStaff()) {
            abort(403);
        }

        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:usuarios,username,' . $id,
            'nickname' => 'nullable|string|max:50',
            'email' => 'required|email|max:100|unique:usuarios,email,' . $id,
            'bio' => 'nullable|string|max:500',
            'nivel_usuario' => 'required|in:usuario,moderador,admin',
            'status' => 'required|in:ativo,inativo'
        ]);

        // Apenas admins podem alterar nível
        if (!Auth::user()->isAdmin()) {
            unset($validated['nivel_usuario']);
        }

        $usuario->update($validated);

        Log::info('Usuário editado por staff', [
            'usuario_id' => $usuario->id,
            'staff_id' => Auth::id()
        ]);

        return redirect()->route('moderacao.usuarios.show', $usuario->id)
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * ========================================
     * VERIFICAÇÕES DE PERMISSÃO
     * ========================================
     */
    protected function podeAplicarPunicao(Usuario $alvo): array
    {
        $moderador = Auth::user();

        // Admins podem punir qualquer um (exceto outros admins)
        if ($moderador->isAdmin()) {
            if ($alvo->isAdmin() && $moderador->id !== $alvo->id) {
                return ['pode' => false, 'mensagem' => 'Você não pode punir outros administradores.'];
            }
            return ['pode' => true];
        }

        // Moderadores só podem punir usuários comuns
        if ($moderador->isModerador()) {
            if ($alvo->isStaff()) {
                return ['pode' => false, 'mensagem' => 'Moderadores não podem punir staff.'];
            }
            return ['pode' => true];
        }

        return ['pode' => false, 'mensagem' => 'Você não tem permissão para aplicar punições.'];
    }

    /**
     * ========================================
     * WARNING (AVISO)
     * ========================================
     */
    public function aplicarWarning(Request $request, $id)
    {
        $alvo = Usuario::findOrFail($id);
        $permissao = $this->podeAplicarPunicao($alvo);

        if (!$permissao['pode']) {
            return back()->with('error', $permissao['mensagem']);
        }

        $request->validate([
            'motivo' => 'required|string|min:10|max:1000'
        ], [
            'motivo.required' => 'O motivo do warning é obrigatório.',
            'motivo.min' => 'O motivo deve ter pelo menos 10 caracteres.',
            'motivo.max' => 'O motivo não pode ter mais de 1000 caracteres.'
        ]);

        DB::beginTransaction();
        try {
            $alvo->update([
                'warning_ativo' => true,
                'warning_motivo' => $request->motivo,
                'warning_data' => now(),
                'warning_aplicado_por' => Auth::id()
            ]);

            Log::info('Warning aplicado', [
                'alvo_id' => $alvo->id,
                'alvo_username' => $alvo->username,
                'moderador_id' => Auth::id(),
                'motivo' => $request->motivo
            ]);

            // Resolver ticket se existir
            $this->resolverTicketRelacionado($request, $alvo);

            DB::commit();

            // Se vier do ticket, redirecionar de volta
            if ($request->has('ticket_id')) {
                return redirect()->route('suporte.show', $request->ticket_id)
                    ->with('success', "Warning aplicado em {$alvo->username}! Ticket marcado como resolvido.");
            }

            // Redirecionar para a página do usuário
            return redirect()->route('moderacao.usuarios.show', $alvo->id)
                ->with('success', "Warning aplicado em {$alvo->username} com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao aplicar warning', [
                'error' => $e->getMessage(),
                'alvo_id' => $id
            ]);

            return back()->with('error', 'Erro ao aplicar warning.');
        }
    }

    public function mostrarWarning()
    {
        $usuario = Auth::user();

        if (!$usuario->warning_ativo) {
            return redirect()->route('home');
        }

        $moderador = Usuario::select('username', 'nivel_usuario')
            ->find($usuario->warning_aplicado_por);

        return view('moderacao.warning', [
            'usuario' => $usuario,
            'moderador' => $moderador
        ]);
    }

    public function reativarAposWarning(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario->warning_ativo) {
            return redirect()->route('home');
        }

        DB::beginTransaction();
        try {
            $usuario->update([
                'warning_ativo' => false
            ]);

            Log::info('Usuário reativou conta após warning', [
                'usuario_id' => $usuario->id
            ]);

            DB::commit();

            return redirect()->route('home')->with('success', 'Conta reativada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao reativar conta', ['error' => $e->getMessage()]);

            return back()->with('error', 'Erro ao reativar conta.');
        }
    }

    /**
     * ========================================
     * BAN TEMPORÁRIO
     * ========================================
     */
    public function aplicarBanTemporario(Request $request, $id)
    {
        $alvo = Usuario::findOrFail($id);
        $permissao = $this->podeAplicarPunicao($alvo);

        if (!$permissao['pode']) {
            return back()->with('error', $permissao['mensagem']);
        }

        $request->validate([
            'motivo' => 'required|string|min:10|max:1000',
            'dias' => 'required|integer|min:1|max:365'
        ]);

        DB::beginTransaction();
        try {
    // garante que o valor seja int (Request retorna string mesmo após validação)
    $dias = $request->integer('dias'); // ou: (int) $request->dias

    // validação extra (defensiva)
    if ($dias <= 0) {
        return back()->with('error', 'Quantidade de dias inválida.');
    }

    $dataFim = Carbon::now()->addDays($dias);

    $alvo->update([
        'ban_tipo' => 'temporario',
        'ban_motivo' => $request->motivo,
        'ban_inicio' => now(),
        'ban_fim' => $dataFim,
        'ban_aplicado_por' => Auth::id()
    ]);

    Log::info('Ban temporário aplicado', [
        'alvo_id' => $alvo->id,
        'alvo_username' => $alvo->username,
        'moderador_id' => Auth::id(),
        'dias' => $dias,
        'motivo' => $request->motivo,
        'fim' => $dataFim
    ]);

    // Resolver ticket se existir
    $this->resolverTicketRelacionado($request, $alvo);

    DB::commit();

    if ($request->has('ticket_id')) {
        return redirect()->route('suporte.show', $request->ticket_id)
            ->with('success', "Ban temporário de {$dias} dias aplicado em {$alvo->username}! Ticket marcado como resolvido.");
    }

    return redirect()->route('moderacao.usuarios.show', $alvo->id)
        ->with('success', "Ban temporário de {$dias} dias aplicado em {$alvo->username}!");
} catch (\Exception $e) {
    DB::rollBack();
    Log::error('Erro ao aplicar ban temporário', [
        'exception' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    return back()->with('error', 'Erro ao aplicar ban temporário.');
}
    }

    /**
     * ========================================
     * PERMA-BAN
     * ========================================
     */
    public function aplicarPermaBan(Request $request, $id)
    {
        $alvo = Usuario::findOrFail($id);
        $permissao = $this->podeAplicarPunicao($alvo);

        if (!$permissao['pode']) {
            return back()->with('error', $permissao['mensagem']);
        }

        $request->validate([
            'motivo' => 'required|string|min:10|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $alvo->update([
                'ban_tipo' => 'permanente',
                'ban_motivo' => $request->motivo,
                'ban_inicio' => now(),
                'ban_fim' => null,
                'ban_aplicado_por' => Auth::id()
            ]);

            Log::warning('Ban permanente aplicado', [
                'alvo_id' => $alvo->id,
                'alvo_username' => $alvo->username,
                'moderador_id' => Auth::id(),
                'motivo' => $request->motivo
            ]);

            // Resolver ticket se existir
            $this->resolverTicketRelacionado($request, $alvo);

            DB::commit();

            if ($request->has('ticket_id')) {
                return redirect()->route('suporte.show', $request->ticket_id)
                    ->with('success', "Ban permanente aplicado em {$alvo->username}! Ticket marcado como resolvido.");
            }

            return redirect()->route('moderacao.usuarios.show', $alvo->id)
                ->with('success', "Ban permanente aplicado em {$alvo->username}!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao aplicar perma-ban', [
                'error' => $e->getMessage(),
                'alvo_id' => $id
            ]);

            return back()->with('error', 'Erro ao aplicar ban.');
        }
    }

    public function mostrarBan()
    {
        $usuario = Auth::user();

        if (!$usuario->ban_tipo) {
            return redirect()->route('home');
        }

        $moderador = Usuario::select('username', 'nivel_usuario')
            ->find($usuario->ban_aplicado_por);

        $tempoRestante = null;
        if ($usuario->ban_tipo === 'temporario' && $usuario->ban_fim) {
            $tempoRestante = Carbon::now()->diff(Carbon::parse($usuario->ban_fim));
        }

        return view('moderacao.ban', [
            'usuario' => $usuario,
            'moderador' => $moderador,
            'tempo_restante' => $tempoRestante
        ]);
    }

    /**
     * ========================================
     * IP BAN (BANIMENTO DE MÁQUINA)
     * ========================================
     */
    public function aplicarIpBan(Request $request, $id)
    {
        $moderador = Auth::user();

        // Apenas admins podem aplicar IP ban
        if (!$moderador->isAdmin()) {
            return back()->with('error', 'Apenas administradores podem aplicar IP ban.');
        }

        $alvo = Usuario::findOrFail($id);

        // Não pode banir staff
        if ($alvo->isStaff()) {
            return back()->with('error', 'Não é possível aplicar IP ban em membros da staff.');
        }

        $request->validate([
            'motivo' => 'required|string|min:10|max:1000'
        ]);

        DB::beginTransaction();
        try {
            // Buscar o fingerprint do dispositivo onde a conta foi criada
            $dispositivo = DeviceFingerprint::where('usuario_id', $alvo->id)
                ->where('conta_criada_neste_dispositivo', true)
                ->first();

            if (!$dispositivo) {
                return back()->with('error', 'Não foi possível identificar o dispositivo de criação da conta.');
            }

            $alvo->update([
                'ip_ban_ativo' => true,
                'ip_ban_fingerprint' => $dispositivo->fingerprint,
                'ip_ban_motivo' => $request->motivo,
                'ip_ban_data' => now(),
                'ip_ban_aplicado_por' => Auth::id()
            ]);

            Log::critical('IP ban aplicado', [
                'alvo_id' => $alvo->id,
                'alvo_username' => $alvo->username,
                'admin_id' => Auth::id(),
                'fingerprint' => $dispositivo->fingerprint,
                'motivo' => $request->motivo
            ]);

            DB::commit();

            return redirect()->route('moderacao.usuarios.show', $alvo->id)
                ->with('success', "IP ban aplicado em {$alvo->username}! O dispositivo foi bloqueado.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao aplicar IP ban', [
                'error' => $e->getMessage(),
                'alvo_id' => $id
            ]);

            return back()->with('error', 'Erro ao aplicar IP ban.');
        }
    }

    public function mostrarIpBan()
    {
        return view('moderacao.ip-ban');
    }

    /**
     * ========================================
     * REMOVER IP BAN (APENAS ADMINS)
     * ========================================
     */
    public function removerIpBan(Request $request, $id)
    {
        $moderador = Auth::user();

        // Apenas admins podem remover IP ban
        if (!$moderador->isAdmin()) {
            return back()->with('error', 'Apenas administradores podem remover IP ban.');
        }

        $usuario = Usuario::findOrFail($id);

        // Verificar se usuário tem IP ban ativo
        if (!$usuario->ip_ban_ativo) {
            return back()->with('error', 'Este usuário não possui IP ban ativo.');
        }

        $request->validate([
            'motivo' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $ipBanAnterior = [
                'fingerprint' => $usuario->ip_ban_fingerprint,
                'motivo' => $usuario->ip_ban_motivo,
                'data' => $usuario->ip_ban_data,
                'aplicado_por' => $usuario->ip_ban_aplicado_por
            ];

            // Remover IP ban
            $usuario->update([
                'ip_ban_ativo' => false,
                'ip_ban_fingerprint' => null,
                'ip_ban_motivo' => null,
                'ip_ban_data' => null,
                'ip_ban_aplicado_por' => null
            ]);

            Log::info('IP ban removido por admin', [
                'usuario_id' => $usuario->id,
                'usuario_username' => $usuario->username,
                'admin_id' => $moderador->id,
                'admin_username' => $moderador->username,
                'motivo_remocao' => $request->motivo ?? 'Não especificado',
                'ip_ban_anterior' => $ipBanAnterior
            ]);

            // Resolver ticket de recurso se existir
            if ($request->has('ticket_id')) {
                try {
                    $ticket = Ticket::findOrFail($request->ticket_id);
                    
                    if ($ticket->categoria === 'recurso_ip_ban') {
                        $ticket->alterarStatus('resolvido', $moderador, $request->motivo ?? 'IP ban removido após análise de recurso');
                        
                        TicketResposta::create([
                            'ticket_id' => $ticket->id,
                            'usuario_id' => $moderador->id,
                            'mensagem' => "✅ Recurso aprovado!\n\nSeu IP ban foi removido. Você já pode acessar sua conta normalmente.\n\nMotivo: " . ($request->motivo ?? 'Recurso procedente'),
                            'interno' => false,
                            'ip_address' => request()->ip()
                        ]);

                        Log::info('Ticket de recurso IP ban resolvido automaticamente', [
                            'ticket_id' => $ticket->id,
                            'usuario_id' => $usuario->id,
                            'admin_id' => $moderador->id
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Erro ao resolver ticket de recurso', [
                        'error' => $e->getMessage(),
                        'ticket_id' => $request->ticket_id
                    ]);
                }
            }

            DB::commit();

            if ($request->has('ticket_id')) {
                return redirect()->route('suporte.show', $request->ticket_id)
                    ->with('success', "IP ban removido de {$usuario->username}! Ticket marcado como resolvido.");
            }

            return back()->with('success', "IP ban removido com sucesso de {$usuario->username}!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao remover IP ban', [
                'error' => $e->getMessage(),
                'usuario_id' => $id,
                'admin_id' => $moderador->id
            ]);

            return back()->with('error', 'Erro ao remover IP ban.');
        }
    }

    /**
     * ========================================
     * ACCOUNT DELETED (EXCLUSÃO DE CONTA)
     * ========================================
     */
    public function deletarConta(Request $request, $id)
    {
        $alvo = Usuario::findOrFail($id);
        $permissao = $this->podeAplicarPunicao($alvo);

        if (!$permissao['pode']) {
            return back()->with('error', $permissao['mensagem']);
        }

        $request->validate([
            'motivo' => 'required|string|min:10|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $hardDeleteDate = Carbon::now()->addDays(30);

            $alvo->update([
                'account_deleted_at' => now(),
                'account_deleted_motivo' => $request->motivo,
                'account_deleted_por' => Auth::id(),
                'account_hard_delete_at' => $hardDeleteDate
            ]);

            Log::warning('Conta marcada para exclusão', [
                'alvo_id' => $alvo->id,
                'alvo_username' => $alvo->username,
                'moderador_id' => Auth::id(),
                'motivo' => $request->motivo,
                'hard_delete_em' => $hardDeleteDate
            ]);

            DB::commit();

            return redirect()->route('moderacao.usuarios.show', $alvo->id)
                ->with('success', "Conta de {$alvo->username} marcada para exclusão. Será deletada permanentemente em 30 dias.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao deletar conta', [
                'error' => $e->getMessage(),
                'alvo_id' => $id
            ]);

            return back()->with('error', 'Erro ao deletar conta.');
        }
    }

    public function mostrarAccountDeleted()
    {
        $usuario = Auth::user();

        if (!$usuario->account_deleted_at) {
            return redirect()->route('home');
        }

        $moderador = Usuario::select('username', 'nivel_usuario')
            ->find($usuario->account_deleted_por);

        $diasRestantes = Carbon::now()->diffInDays(Carbon::parse($usuario->account_hard_delete_at));

        return view('moderacao.account-deleted', [
            'usuario' => $usuario,
            'moderador' => $moderador,
            'dias_restantes' => $diasRestantes
        ]);
    }

    /**
     * ========================================
     * REATIVAR USUÁRIO (ATUALIZADO - INCLUI IP BAN)
     * ========================================
     */
    public function reativar(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            return back()->with('error', 'Apenas administradores podem reativar usuários.');
        }

        $usuario = Usuario::findOrFail($id);

        DB::beginTransaction();
        try {
            $usuario->update([
                'warning_ativo' => false,
                'warning_motivo' => null,
                'warning_data' => null,
                'warning_aplicado_por' => null,
                'ban_tipo' => null,
                'ban_motivo' => null,
                'ban_inicio' => null,
                'ban_fim' => null,
                'ban_aplicado_por' => null,
                'ip_ban_ativo' => false,        // ✅ REMOVER IP BAN TAMBÉM
                'ip_ban_fingerprint' => null,   // ✅ LIMPAR FINGERPRINT
                'ip_ban_motivo' => null,
                'ip_ban_data' => null,
                'ip_ban_aplicado_por' => null,
                'status' => 'ativo'
            ]);

            Log::info('Usuário completamente reativado por admin', [
                'usuario_id' => $usuario->id,
                'admin_id' => Auth::id()
            ]);

            DB::commit();

            return back()->with('success', 'Usuário reativado com sucesso! Todas as punições foram removidas.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao reativar', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao reativar usuário.');
        }
    }

    /**
     * ========================================
     * INTEGRAÇÃO COM SISTEMA DE TICKETS
     * ========================================
     */
    private function resolverTicketRelacionado(Request $request, Usuario $usuario)
    {
        if ($request->has('ticket_id')) {
            try {
                $ticket = Ticket::findOrFail($request->ticket_id);
                
                if ($ticket->categoria === 'denuncia' && $ticket->usuario_denunciado_id === $usuario->id) {
                    $ticket->alterarStatus('resolvido', Auth::user(), 'Punição aplicada ao usuário denunciado');
                    
                    TicketResposta::create([
                        'ticket_id' => $ticket->id,
                        'usuario_id' => Auth::id(),
                        'mensagem' => "Punição aplicada ao usuário {$usuario->username}. Denúncia resolvida automaticamente.",
                        'interno' => true,
                        'ip_address' => request()->ip()
                    ]);

                    Log::info('Ticket resolvido automaticamente após punição', [
                        'ticket_id' => $ticket->id,
                        'usuario_punido_id' => $usuario->id,
                        'moderador_id' => Auth::id()
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Erro ao resolver ticket relacionado', [
                    'error' => $e->getMessage(),
                    'ticket_id' => $request->ticket_id
                ]);
            }
        }
    }
}