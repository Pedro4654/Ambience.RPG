<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Usuario;
use App\Models\ParticipanteSala;
use App\Models\PermissaoSala;
use App\Models\ConviteSala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

/**
 * Controlador do Sistema de Salas de RPG
 * 
 * Gerencia a criação, entrada e administração de salas de RPG
 * Preparado para integração com React e WebSocket
 * 
 * @package App\Http\Controllers
 * @author Sistema Ambience RPG
 * @version 2.0
 */
class SalaController extends Controller
{
    /**
     * Exibir dashboard principal do sistema de salas
     * 
     * Rota: GET /salas
     * Middleware: auth (usuário deve estar logado)
     * 
     * Retorna view com dados das salas ou JSON para API
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('usuarios.login')->withError('Faça login para acessar as salas.');
        }

        $userId = Auth::id();

        // Buscar dados para a view inicial (pode ser vazio se quiser carregar via AJAX)
        $minhasSalas = collect(); // Coleção vazia
        $salasPublicas = collect(); // Coleção vazia

        // SEMPRE retornar VIEW - nunca JSON
        return view('salas.dashboard', [
            'minhasSalas' => $minhasSalas,
            'salasPublicas' => $salasPublicas,
            'userId' => $userId
        ]);
    }

    /**
     * Retornar dados das salas APENAS para requisições AJAX
     */
    public function getSalasAjax(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado',
                'redirect_to' => route('usuarios.login')
            ], 401);
        }

        $userId = Auth::id();

        // Buscar salas do usuário
        $minhasSalas = Sala::whereHas('participantes', function ($query) use ($userId) {
            $query->where('usuario_id', $userId)->where('ativo', true);
        })
            ->with(['criador', 'participantes' => function ($query) {
                $query->where('ativo', true)->with('usuario');
            }])
            ->where('ativa', true)
            ->orderBy('data_criacao', 'desc')
            ->get();

        // Buscar salas públicas que o usuário NÃO participa
        $salasPublicas = Sala::where('tipo', 'publica')
            ->where('ativa', true)
            ->whereDoesntHave('participantes', function ($query) use ($userId) {
                $query->where('usuario_id', $userId)->where('ativo', true);
            })
            ->with(['criador', 'participantes' => function ($query) {
                $query->where('ativo', true)->with('usuario');
            }])
            ->orderBy('data_criacao', 'desc')
            ->limit(10)
            ->get();

        $response = response()->json([
            'success' => true,
            'minhas_salas' => $minhasSalas,
            'salas_publicas' => $salasPublicas,
            'user_id' => $userId,
            'estatisticas' => [
                'total_minhas_salas' => $minhasSalas->count(),
                'total_salas_publicas' => $salasPublicas->count()
            ]
        ]);

        // Headers anti-cache
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        return $response;
    }

    /**
     * Criar nova sala
     * POST /salas
     */
    public function store(Request $request)
    {
        Log::info('Iniciando criação de sala', ['user_id' => Auth::id(), 'dados' => $request->all()]);

        // Validação dos dados de entrada
        $request->validate([
            'nome' => 'required|string|max:100|min:3',
            'descricao' => 'nullable|string|max:1000',
            'tipo' => 'required|in:publica,privada,apenas_convite',
            'senha' => 'nullable|string|min:4|max:50',
            'max_participantes' => 'integer|min:2|max:100',
            'imagem_url' => 'nullable|url|max:255'
        ], [
            'nome.required' => 'O nome da sala é obrigatório',
            'nome.min' => 'O nome deve ter pelo menos 3 caracteres',
            'nome.max' => 'O nome pode ter no máximo 100 caracteres',
            'tipo.required' => 'O tipo da sala é obrigatório',
            'tipo.in' => 'Tipo inválido. Use: publica, privada ou apenas_convite',
            'senha.min' => 'A senha deve ter pelo menos 4 caracteres',
            'max_participantes.min' => 'Mínimo de 2 participantes',
            'max_participantes.max' => 'Máximo de 100 participantes'
        ]);

        try {
            // Preparar dados para criação
            $dadosSala = [
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'criador_id' => Auth::id(),
                'tipo' => $request->tipo,
                'max_participantes' => $request->max_participantes ?? 50,
                'imagem_url' => $request->imagem_url,
                'data_criacao' => now(),
                'ativa' => true
            ];

            // Se a sala for privada, hash da senha
            if ($request->tipo === 'privada' && $request->filled('senha')) {
                $dadosSala['senha_hash'] = Hash::make($request->senha);
                Log::info('Senha definida para sala privada');
            }

            // Criar a sala
            $sala = Sala::create($dadosSala);

            // Adicionar o criador como mestre da sala
            ParticipanteSala::create([
                'sala_id' => $sala->id,
                'usuario_id' => Auth::id(),
                'papel' => 'mestre', // Criador é sempre mestre
                'data_entrada' => now(),
                'ativo' => true
            ]);

            // Definir permissões padrão para o criador (todas as permissões)
            PermissaoSala::create([
                'sala_id' => $sala->id,
                'usuario_id' => Auth::id(),
                'pode_criar_conteudo' => true,
                'pode_editar_grid' => true,
                'pode_iniciar_sessao' => true,
                'pode_moderar_chat' => true,
                'pode_convidar_usuarios' => true
            ]);

            Log::info('Sala criada com sucesso', [
                'sala_id' => $sala->id,
                'nome' => $sala->nome,
                'tipo' => $sala->tipo,
                'criador_id' => Auth::id()
            ]);

            // Carregar dados completos para retorno
            $sala->load(['criador', 'participantes.usuario']);

            return response()->json([
                'success' => true,
                'message' => 'Sala criada com sucesso!',
                'sala' => $sala
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar sala', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Entrar em sala por ID (e senha se necessário)
     * POST /salas/entrar
     */
    public function entrarSala(Request $request)
{
    Log::info('Tentativa de entrada em sala', [
        'user_id' => Auth::id(),
        'dados' => $request->only('sala_id')
    ]);

    // Validação
    $request->validate([
        'sala_id' => 'required|integer|exists:salas,id',
        'senha' => 'nullable|string'
    ], [
        'sala_id.required' => 'ID da sala obrigatório',
        'sala_id.exists' => 'Sala não encontrada'
    ]);

    try {
        $salaId = $request->sala_id;
        $userId = Auth::id();

        // Buscar a sala
        $sala = Sala::with(['participantes' => function ($query) {
            $query->where('ativo', true)->with('usuario');
        }])->findOrFail($salaId);

        // Verificar se a sala está ativa
        if (!$sala->ativa) {
            return response()->json([
                'success' => false,
                'message' => 'Esta sala não está mais ativa.'
            ], 400);
        }

        // Verificar se já é participante ativo
        $jaParticipa = ParticipanteSala::where('sala_id', $salaId)
            ->where('usuario_id', $userId)
            ->where('ativo', true)
            ->exists();

        if ($jaParticipa) {
            return response()->json([
                'success' => true,
                'message' => 'Você já participa desta sala!',
                'sala' => $sala,
                'redirect_to' => "/salas/{$sala->id}"
            ]);
        }

        // Se não é participante ativo, procurar registro existente (mesmo inativo) para reativar
        $participante = ParticipanteSala::where('sala_id', $salaId)
            ->where('usuario_id', $userId)
            ->first();

        if ($participante) {
            // Reactiva o participante em vez de criar um novo (evita conflito de UNIQUE)
            $participante->ativo = true;
            $participante->data_entrada = now();
            // caso tenha campos de papel/permissão, manter ou resetar conforme lógica do seu app
            if (empty($participante->papel)) {
                $participante->papel = 'membro';
            }
            $participante->save();
        } else {
            // Criar novo participante (somente se não existir nenhum registro)
            $participante = ParticipanteSala::create([
                'sala_id' => $salaId,
                'usuario_id' => $userId,
                'papel' => 'membro',
                'data_entrada' => now(),
                'ativo' => true
            ]);
        }

        // Definir permissões padrão para o usuário — apenas se não existir já
        $permExists = PermissaoSala::where('sala_id', $salaId)
            ->where('usuario_id', $userId)
            ->exists();

        if (!$permExists) {
            PermissaoSala::create([
                'sala_id' => $salaId,
                'usuario_id' => $userId,
                'pode_criar_conteudo' => true,
                'pode_editar_grid' => false,
                'pode_iniciar_sessao' => false,
                'pode_moderar_chat' => false,
                'pode_convidar_usuarios' => false
            ]);
        }

        // Se entrou por convite, marcar como aceito
        if ($sala->tipo === 'apenas_convite') {
            ConviteSala::where('sala_id', $salaId)
                ->where('destinatario_id', $userId)
                ->where('status', 'pendente')
                ->update(['status' => 'aceito']);
        }

        Log::info('Usuário entrou na sala com sucesso', [
            'user_id' => $userId,
            'sala_id' => $salaId,
            'nome_sala' => $sala->nome
        ]);

        // Carregar dados atualizados
        $sala->load(['criador', 'participantes' => function ($query) {
            $query->where('ativo', true)->with('usuario');
        }]);

        return response()->json([
            'success' => true,
            'message' => "Bem-vindo(a) à sala {$sala->nome}!",
            'sala' => $sala,
            'redirect_to' => "/salas/{$sala->id}"
        ]);
    } catch (\Exception $e) {
        Log::error('Erro ao entrar na sala', [
            'error' => $e->getMessage(),
            'user_id' => Auth::id(),
            'sala_id' => $request->sala_id ?? null,
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erro interno. Tente novamente.'
        ], 500);
    }
}


// Retorna dados do participante + permissões (JSON)
public function getPermissoesParticipante(Request $request, $salaId, $usuarioId)
{
    try {
        $userId = Auth::id();

        // Buscar sala
        $sala = Sala::findOrFail($salaId);

        // Só o criador pode gerenciar permissões
        if ($sala->criador_id !== $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Somente o criador da sala pode gerenciar permissões.'
            ], 403);
        }

        // Buscar participante (mesmo que inativo — mas só permitir gerenciar se existir)
        $participante = ParticipanteSala::where('sala_id', $salaId)
            ->where('usuario_id', $usuarioId)
            ->first();

        if (!$participante) {
            return response()->json([
                'success' => false,
                'message' => 'Participante não encontrado.'
            ], 404);
        }

        // Buscar permissões (se não existir, devolver padrões desativados)
        $permissoes = PermissaoSala::where('sala_id', $salaId)
            ->where('usuario_id', $usuarioId)
            ->first();

        if (!$permissoes) {
            $permissoes = (object)[
                'pode_criar_conteudo' => false,
                'pode_editar_grid' => false,
                'pode_iniciar_sessao' => false,
                'pode_moderar_chat' => false,
                'pode_convidar_usuarios' => false
            ];
        }

        // Carregar dados do usuário (username, avatar) se desejar
        // Carregar dados do usuário (username, avatar) de forma segura
$columns = ['id', 'username'];
if (Schema::hasColumn('usuarios', 'avatar')) {
    $columns[] = 'avatar';
} elseif (Schema::hasColumn('usuarios', 'avatar_url')) {
    // seleciona avatar_url mas renomeia para 'avatar' na resposta
    $columns[] = 'avatar_url as avatar';
}

// Se preferir usar o model Eloquent do relacionamento:
$usuario = $participante->usuario()->select($columns)->first();

// fallback caso não exista (segurança)
if (!$usuario) {
    $usuario = (object)[
        'id' => $usuarioId,
        'username' => null,
        'avatar' => null
    ];
}

        return response()->json([
            'success' => true,
            'participante' => [
                'usuario' => $usuario,
                'papel' => $participante->papel,
                'ativo' => (bool) $participante->ativo
            ],
            'permissoes' => $permissoes
        ]);
    } catch (\Exception $e) {
        Log::error('Erro ao buscar permissões do participante', [
            'error' => $e->getMessage(),
            'sala_id' => $salaId,
            'usuario_id' => $usuarioId,
            'user_id' => Auth::id()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Erro interno. Tente novamente.'
        ], 500);
    }
}

// Atualiza permissões do participante (JSON)
public function updatePermissoesParticipante(Request $request, $salaId, $usuarioId)
{
    try {
        $userId = Auth::id();

        // Buscar sala
        $sala = Sala::findOrFail($salaId);

        // Só o criador pode gerenciar permissões
        if ($sala->criador_id !== $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Somente o criador da sala pode gerenciar permissões.'
            ], 403);
        }

        // Não permitir gerenciar as próprias permissões (criador)
        if ($usuarioId == $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Você não pode alterar suas próprias permissões aqui.'
            ], 400);
        }

        // Encontrar participante
        $participante = ParticipanteSala::where('sala_id', $salaId)
            ->where('usuario_id', $usuarioId)
            ->first();

        if (!$participante) {
            return response()->json([
                'success' => false,
                'message' => 'Participante não encontrado.'
            ], 404);
        }

        // Validação simples dos inputs (booleans e papel)
        $data = $request->validate([
            'pode_criar_conteudo' => 'sometimes|boolean',
            'pode_editar_grid' => 'sometimes|boolean',
            'pode_iniciar_sessao' => 'sometimes|boolean',
            'pode_moderar_chat' => 'sometimes|boolean',
            'pode_convidar_usuarios' => 'sometimes|boolean',
            'papel' => 'sometimes|string|in:membro,admin_sala,mestre'
        ]);

        // Atualizar ou criar a linha de permissões
        $permissoes = PermissaoSala::firstOrNew([
            'sala_id' => $salaId,
            'usuario_id' => $usuarioId
        ]);

        // Para cada campo, se vier no request, atribuir; caso contrário, manter
        $fields = [
            'pode_criar_conteudo',
            'pode_editar_grid',
            'pode_iniciar_sessao',
            'pode_moderar_chat',
            'pode_convidar_usuarios'
        ];
        foreach ($fields as $f) {
            if ($request->has($f)) {
                $permissoes->$f = (bool) $request->input($f);
            }
        }
        $permissoes->save();

        // Atualizar papel se informado
        if ($request->has('papel')) {
            $participante->papel = $request->input('papel');
            $participante->save();
        }

        Log::info('Permissões atualizadas', [
            'by_user' => $userId,
            'target_user' => $usuarioId,
            'sala_id' => $salaId
        ]);

        // Opcional: broadcast evento de atualização de permissões (se tiver websocket)
        // event(new PermissoesAtualizadas($salaId, $usuarioId));

        return response()->json([
            'success' => true,
            'message' => 'Permissões atualizadas com sucesso.',
            'permissoes' => $permissoes,
            'participante' => [
                'papel' => $participante->papel
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Erro ao atualizar permissões do participante', [
            'error' => $e->getMessage(),
            'sala_id' => $salaId,
            'usuario_id' => $usuarioId,
            'by_user' => Auth::id()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Erro interno. Tente novamente.'
        ], 500);
    }
}

    /**
     * Exibir sala específica com teste WebSocket
     * GET /salas/{id}
     */
    public function show($id, Request $request)
    {
        try {
            $userId = Auth::id();

            // Buscar sala com relacionamentos
            $sala = Sala::with([
                'criador',
                'participantes' => function ($query) {
                    $query->where('ativo', true)->with('usuario');
                },
                'permissoes' => function ($query) use ($userId) {
                    $query->where('usuario_id', $userId);
                }
            ])->findOrFail($id);

            // Verificar se o usuário é participante
            $participante = $sala->participantes->where('usuario_id', $userId)->first();

            if (!$participante) {
                Log::warning('Tentativa de acesso não autorizado à sala', [
                    'user_id' => $userId,
                    'sala_id' => $id
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Você não tem acesso a esta sala.'
                    ], 403);
                }

                return redirect('/salas')->with('error', 'Você não tem acesso a esta sala.');
            }

            // Simular teste de WebSocket para verificar conectividade em tempo real
            $websocketStatus = $this->testarWebSocket();

            Log::info('Usuário acessou sala', [
                'user_id' => $userId,
                'sala_id' => $id,
                'papel' => $participante->papel
            ]);

            $dadosSala = [
                'sala' => $sala,
                'meu_papel' => $participante->papel,
                'minhas_permissoes' => $sala->permissoes->first(),
                'websocket_status' => $websocketStatus,
                'participantes_online' => $sala->participantes->count(), // Simulado
                'success' => true
            ];

            // Retornar JSON para API ou view para navegação
            if ($request->expectsJson()) {
                return response()->json($dadosSala);
            }

            return view('salas.show', $dadosSala);
        } catch (\Exception $e) {
            Log::error('Erro ao carregar sala', [
                'error' => $e->getMessage(),
                'sala_id' => $id,
                'user_id' => Auth::id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sala não encontrada.'
                ], 404);
            }

            return redirect('/salas')->with('error', 'Sala não encontrada.');
        }
    }

    /**
     * Sair da sala
     * POST /salas/{id}/sair
     */
    // Método sairSala atualizado
    public function sairSala(Request $request, $id)
{
    try {
        $userId = Auth::id();
        $user = Auth::user();

        // Buscar participação na sala
        $participante = ParticipanteSala::where('sala_id', $id)
            ->where('usuario_id', $userId)
            ->where('ativo', true)
            ->first();

        if (!$participante) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não está nesta sala.'
                ], 403);
            }
            return redirect()->route('salas.index')->withErrors('Você não está nesta sala.');
        }

        // Impedir que o criador saia diretamente (exemplo)
        $sala = Sala::find($id);
        if ($sala && $sala->criador_id == $userId) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Como criador, você não pode sair da sala. Transfira a liderança primeiro.'
                ], 400);
            }
            return redirect()->route('salas.show', ['id' => $id])->withErrors('Como criador, você não pode sair da sala. Transfira a liderança primeiro.');
        }

        // Marcar como inativo / remover participação
        $participante->ativo = false;
        $participante->save();

        Log::info('Usuário saiu da sala', [
            'user_id' => $userId,
            'sala_id' => $id
        ]);

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Você saiu da sala.',
                'redirect_to' => route('salas.index')
            ]);
        }

        return redirect()->route('salas.index')->with('success', 'Você saiu da sala.');
    } catch (\Exception $e) {
        Log::error('Erro ao sair da sala', [
            'error' => $e->getMessage(),
            'sala_id' => $id,
            'user_id' => Auth::id()
        ]);

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno.'
            ], 500);
        }

        return redirect()->back()->withErrors('Erro interno ao tentar sair da sala.');
    }
}

    // Novo método para obter usuários online da sala
    public function getOnlineUsers($id)
    {
        try {
            $sala = Sala::findOrFail($id);

            // Verificar se o usuário participa da sala
            $participante = ParticipanteSala::where('sala_id', $id)
                ->where('usuario_id', Auth::id())
                ->where('ativo', true)
                ->first();

            if (!$participante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem acesso a esta sala.'
                ], 403);
            }

            // Buscar usuários online na sala
            $onlineUsers = Usuario::onlineInRoom($id)
                ->with(['participantesSala' => function ($query) use ($id) {
                    $query->where('sala_id', $id)->where('ativo', true);
                }])
                ->get()
                ->map(function ($user) {
                    $participante = $user->participantesSala->first();
                    return [
    'id' => $user->id,
    'username' => $user->username,
    'avatar' => $user->avatar ?? $user->avatar_url ?? null,
    'papel' => $participante ? $participante->papel : 'membro',
    'last_seen' => $user->last_seen,
    'is_online' => true
];
                });

            return response()->json([
                'success' => true,
                'online_users' => $onlineUsers,
                'count' => $onlineUsers->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar usuários online', [
                'error' => $e->getMessage(),
                'sala_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno.'
            ], 500);
        }
    }

    /**
     * Gerar convite para sala
     * POST /salas/{id}/convidar
     */
    public function gerarConvite(Request $request, $id)
    {
        $request->validate([
            'destinatario_email' => 'required|email|exists:usuarios,email',
            'expira_em_horas' => 'integer|min:1|max:168' // max 1 semana
        ]);

        try {
            $userId = Auth::id();
            $sala = Sala::findOrFail($id);

            // Verificar permissão para convidar
            $permissao = PermissaoSala::where('sala_id', $id)
                ->where('usuario_id', $userId)
                ->where('pode_convidar_usuarios', true)
                ->first();

            if (!$permissao) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para convidar usuários.'
                ], 403);
            }

            // Buscar destinatário
            $destinatario = Usuario::where('email', $request->destinatario_email)->first();

            // Verificar se já é participante
            if (ParticipanteSala::where('sala_id', $id)->where('usuario_id', $destinatario->id)->where('ativo', true)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este usuário já participa da sala.'
                ], 400);
            }

            // Gerar token único
            $token = Str::random(32);
            $expiracaoHoras = $request->expira_em_horas ?? 72; // 3 dias por padrão

            // Criar convite
            $convite = ConviteSala::create([
                'sala_id' => $id,
                'remetente_id' => $userId,
                'destinatario_id' => $destinatario->id,
                'token' => $token,
                'status' => 'pendente',
                'data_criacao' => now(),
                'data_expiracao' => now()->addHours($expiracaoHoras)
            ]);

            Log::info('Convite criado', [
                'convite_id' => $convite->id,
                'sala_id' => $id,
                'remetente_id' => $userId,
                'destinatario_id' => $destinatario->id
            ]);

            return response()->json([
                'success' => true,
                'message' => "Convite enviado para {$destinatario->username}!",
                'convite' => $convite,
                'link_convite' => url("/convites/{$token}")
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao gerar convite', [
                'error' => $e->getMessage(),
                'sala_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno.'
            ], 500);
        }
    }

    /**
     * Aceitar convite via token
     * GET /convites/{token}
     */
    public function aceitarConvite($token, Request $request)
    {
        try {
            // Buscar convite válido
            $convite = ConviteSala::with(['sala', 'remetente'])
                ->where('token', $token)
                ->where('status', 'pendente')
                ->where('data_expiracao', '>', now())
                ->first();

            if (!$convite) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Convite inválido ou expirado.'
                    ], 400);
                }
                return redirect('/salas')->with('error', 'Convite inválido ou expirado.');
            }

            $userId = Auth::id();

            // Verificar se é o destinatário correto
            if ($convite->destinatario_id != $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este convite não é para você.'
                ], 403);
            }

            // Verificar se já participa
            if (ParticipanteSala::where('sala_id', $convite->sala_id)->where('usuario_id', $userId)->where('ativo', true)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você já participa desta sala.'
                ], 400);
            }

            // Aceitar convite - adicionar como participante
            ParticipanteSala::create([
                'sala_id' => $convite->sala_id,
                'usuario_id' => $userId,
                'papel' => 'membro',
                'data_entrada' => now(),
                'ativo' => true
            ]);

            // Definir permissões padrão
            PermissaoSala::create([
                'sala_id' => $convite->sala_id,
                'usuario_id' => $userId,
                'pode_criar_conteudo' => true,
                'pode_editar_grid' => false,
                'pode_iniciar_sessao' => false,
                'pode_moderar_chat' => false,
                'pode_convidar_usuarios' => false
            ]);

            // Marcar convite como aceito
            $convite->update(['status' => 'aceito']);

            Log::info('Convite aceito com sucesso', [
                'convite_id' => $convite->id,
                'user_id' => $userId,
                'sala_id' => $convite->sala_id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Bem-vindo(a) à sala '{$convite->sala->nome}'!",
                    'redirect_to' => "/salas/{$convite->sala_id}"
                ]);
            }

            return redirect("/salas/{$convite->sala_id}")->with('success', "Bem-vindo(a) à sala '{$convite->sala->nome}'!");
        } catch (\Exception $e) {
            Log::error('Erro ao aceitar convite', [
                'error' => $e->getMessage(),
                'token' => $token,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno.'
            ], 500);
        }
    }

    /**
     * Método privado para simular teste de WebSocket
     * Para garantir que o sistema suporte tempo real quando implementado
     */
    private function testarWebSocket()
    {
        // Simular teste de conectividade WebSocket
        // Em produção, isso seria uma conexão real com o servidor WebSocket
        $status = [
            'connected' => true, // Simulado
            'ping' => rand(10, 50), // ms simulado
            'server' => 'ws://localhost:6001', // Endereço do servidor WebSocket
            'last_check' => now()->toISOString(),
            'message' => 'WebSocket simulado - conectado com sucesso!'
        ];

        Log::info('Teste WebSocket simulado', $status);
        return $status;
    }

    public function infoSala($id, Request $request)
    {
        try {
            $sala = Sala::select(['id', 'nome', 'tipo', 'max_participantes', 'ativa'])
                ->with(['participantes' => function ($query) {
                    $query->where('ativo', true);
                }])
                ->findOrFail($id);

            if (!$sala->ativa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta sala não está mais ativa.'
                ], 400);
            }

            $userId = Auth::id();

            // Verificar se já é participante
            $jaParticipa = ParticipanteSala::where('sala_id', $id)
                ->where('usuario_id', $userId)
                ->where('ativo', true)
                ->exists();

            if ($jaParticipa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você já participa desta sala!'
                ], 400);
            }

            // Verificar limite de participantes
            $participantesAtivos = $sala->participantes->count();
            if ($participantesAtivos >= $sala->max_participantes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sala lotada! Máximo de participantes atingido.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'sala' => [
                    'id' => $sala->id,
                    'nome' => $sala->nome,
                    'tipo' => $sala->tipo,
                    'participantes_atuais' => $participantesAtivos,
                    'max_participantes' => $sala->max_participantes,
                    'precisa_senha' => $sala->tipo === 'privada',
                    'apenas_convite' => $sala->tipo === 'apenas_convite'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar informações da sala', [
                'error' => $e->getMessage(),
                'sala_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sala não encontrada.'
            ], 404);
        }
    }
}
