<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Usuario;
use App\Models\SessaoJogo;
use App\Models\ParticipanteSessao;
use App\Models\ParticipanteSala;
use App\Models\PermissaoSala;
use App\Models\ConviteSala;
use App\Models\LinkConviteSala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

/**
 * Controlador do Sistema de Salas de RPG - VERSÃO COMPLETA
 * 
 * Gerencia a criação, entrada e administração de salas de RPG
 * Inclui CRUD completo para staff (moderadores e admins)
 * 
 * @package App\Http\Controllers
 * @author Sistema Ambience RPG
 * @version 3.0
 */
class SalaController extends Controller
{
    /**
     * Exibir dashboard principal do sistema de salas
     * 
     * Rota: GET /salas
     * Middleware: auth (usuário deve estar logado)
     */
    public function index(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('usuarios.login')->withError('Faça login para acessar as salas.');
    }

    $userId = Auth::id();
    $isStaff = Auth::user()->isStaff();
    
    // NOVO: Contar salas desativadas do usuário
    $minhasSalasDesativadasCount = Sala::where('criador_id', $userId)
        ->where('ativa', false)
        ->count();

    return view('salas.dashboard', [
        'minhasSalas' => collect(),
        'salasPublicas' => collect(),
        'userId' => $userId,
        'isStaff' => $isStaff,
        'minhasSalasDesativadasCount' => $minhasSalasDesativadasCount // NOVO
    ]);
}

    /**
     * Retornar dados das salas APENAS para requisições AJAX
     * Agora com paginação (3 salas por vez) e busca
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
        $isStaff = Auth::user()->isStaff();
        
        // Parâmetros de paginação e busca
        $perPage = 3; // 3 salas por vez
        $pageMinhas = $request->input('page_minhas', 1);
        $pagePublicas = $request->input('page_publicas', 1);
        $searchMinhas = $request->input('search_minhas', '');
        $searchPublicas = $request->input('search_publicas', '');

        // Query base para minhas salas
        $queryMinhas = Sala::whereHas('participantes', function ($query) use ($userId) {
    $query->where('usuario_id', $userId)->where('ativo', true);
})
->with([
    'criador', 
    'desativadaPor', // NOVO
    'participantes' => function ($query) {
        $query->where('ativo', true)->with('usuario');
    }
])
        ->where('ativa', true);

        // Aplicar busca em minhas salas
        if (!empty($searchMinhas)) {
            $queryMinhas->where(function($q) use ($searchMinhas) {
                $q->where('nome', 'like', "%{$searchMinhas}%")
                  ->orWhere('descricao', 'like', "%{$searchMinhas}%")
                  ->orWhere('id', 'like', "%{$searchMinhas}%");
            });
        }

        $minhasSalas = $queryMinhas->orderBy('data_criacao', 'desc')
            ->skip(($pageMinhas - 1) * $perPage)
            ->take($perPage)
            ->get();

        $totalMinhas = Sala::whereHas('participantes', function ($query) use ($userId) {
            $query->where('usuario_id', $userId)->where('ativo', true);
        })
        ->where('ativa', true)
        ->when(!empty($searchMinhas), function($q) use ($searchMinhas) {
            $q->where(function($query) use ($searchMinhas) {
                $query->where('nome', 'like', "%{$searchMinhas}%")
                      ->orWhere('descricao', 'like', "%{$searchMinhas}%")
                      ->orWhere('id', 'like', "%{$searchMinhas}%");
            });
        })
        ->count();

        // Query base para salas públicas
        $queryPublicas = Sala::where('tipo', 'publica')
            ->where('ativa', true)
            ->whereDoesntHave('participantes', function ($query) use ($userId) {
                $query->where('usuario_id', $userId)->where('ativo', true);
            })
            ->with(['criador', 'desativadaPor', 'participantes' => function ($query) {
                $query->where('ativo', true)->with('usuario');
            }]);

        // Aplicar busca em salas públicas
        if (!empty($searchPublicas)) {
            $queryPublicas->where(function($q) use ($searchPublicas) {
                $q->where('nome', 'like', "%{$searchPublicas}%")
                  ->orWhere('descricao', 'like', "%{$searchPublicas}%")
                  ->orWhere('id', 'like', "%{$searchPublicas}%");
            });
        }

        $salasPublicas = $queryPublicas->orderBy('data_criacao', 'desc')
            ->skip(($pagePublicas - 1) * $perPage)
            ->take($perPage)
            ->get();

        $totalPublicas = Sala::where('tipo', 'publica')
            ->where('ativa', true)
            ->whereDoesntHave('participantes', function ($query) use ($userId) {
                $query->where('usuario_id', $userId)->where('ativo', true);
            })
            ->when(!empty($searchPublicas), function($q) use ($searchPublicas) {
                $q->where(function($query) use ($searchPublicas) {
                    $query->where('nome', 'like', "%{$searchPublicas}%")
                          ->orWhere('descricao', 'like', "%{$searchPublicas}%")
                          ->orWhere('id', 'like', "%{$searchPublicas}%");
                });
            })
            ->count();

        $response = response()->json([
            'success' => true,
            'minhas_salas' => $minhasSalas,
            'salas_publicas' => $salasPublicas,
            'user_id' => $userId,
            'is_staff' => $isStaff,
            'pagination' => [
                'minhas' => [
                    'current_page' => $pageMinhas,
                    'total' => $totalMinhas,
                    'per_page' => $perPage,
                    'has_more' => ($pageMinhas * $perPage) < $totalMinhas
                ],
                'publicas' => [
                    'current_page' => $pagePublicas,
                    'total' => $totalPublicas,
                    'per_page' => $perPage,
                    'has_more' => ($pagePublicas * $perPage) < $totalPublicas
                ]
            ],
            'estatisticas' => [
                'total_minhas_salas' => $totalMinhas,
                'total_salas_publicas' => $totalPublicas
            ]
        ]);

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        return $response;
    }

    /**
     * CRUD STAFF - Editar sala (apenas staff)
     * GET /salas/{id}/staff/edit
     */
    public function staffEdit($id)
    {
        if (!Auth::user()->isStaff()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado. Apenas moderadores e admins podem editar salas.'
            ], 403);
        }

        $sala = Sala::with(['criador', 'participantes.usuario'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'sala' => $sala
        ]);
    }

    /**
     * CRUD STAFF - Atualizar sala (apenas staff)
     * PUT /salas/{id}/staff/update
     */
    public function staffUpdate(Request $request, $id)
    {
        if (!Auth::user()->isStaff()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado. Apenas moderadores e admins podem editar salas.'
            ], 403);
        }

        $request->validate([
            'nome' => 'required|string|max:100|min:3',
            'descricao' => 'nullable|string|max:1000',
            'tipo' => 'required|in:publica,privada,apenas_convite',
            'max_participantes' => 'integer|min:2|max:100',
            'ativa' => 'boolean'
        ]);

        try {
            $sala = Sala::findOrFail($id);

            $sala->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'tipo' => $request->tipo,
                'max_participantes' => $request->max_participantes ?? $sala->max_participantes,
                'ativa' => $request->has('ativa') ? $request->ativa : $sala->ativa
            ]);

            Log::info('Sala editada por staff', [
                'sala_id' => $id,
                'staff_id' => Auth::id(),
                'changes' => $request->only(['nome', 'descricao', 'tipo', 'max_participantes', 'ativa'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sala atualizada com sucesso!',
                'sala' => $sala->load(['criador', 'participantes.usuario'])
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar sala (staff)', [
                'error' => $e->getMessage(),
                'sala_id' => $id,
                'staff_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar sala.'
            ], 500);
        }
    }

    /**
     * CRUD STAFF - Deletar sala (apenas staff)
     * DELETE /salas/{id}/staff/delete
     */
    public function staffDestroy($id)
    {
        if (!Auth::user()->isStaff()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado. Apenas moderadores e admins podem deletar salas.'
            ], 403);
        }

        try {
            $sala = Sala::findOrFail($id);
            $nomeSala = $sala->nome;

            // Deletar arquivos associados (banner e foto de perfil)
            if (!empty($sala->banner_url)) {
                $oldPath = preg_replace('~^/storage/~', '', parse_url($sala->banner_url, PHP_URL_PATH) ?? '');
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            if (!empty($sala->profile_photo_url)) {
                $oldPath = preg_replace('~^/storage/~', '', parse_url($sala->profile_photo_url, PHP_URL_PATH) ?? '');
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $sala->delete();

            Log::info('Sala deletada por staff', [
                'sala_id' => $id,
                'sala_nome' => $nomeSala,
                'staff_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Sala '{$nomeSala}' deletada com sucesso!"
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao deletar sala (staff)', [
                'error' => $e->getMessage(),
                'sala_id' => $id,
                'staff_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar sala.'
            ], 500);
        }
    }

    /**
 * CRUD STAFF - Desativar/Reativar sala (apenas staff)
 * POST /salas/{id}/staff/toggle-status
 */
public function staffToggleStatus(Request $request, $id)
{
    if (!Auth::user()->isStaff()) {
        return response()->json([
            'success' => false,
            'message' => 'Acesso negado.'
        ], 403);
    }

    try {
        $sala = Sala::findOrFail($id);
        $novoStatus = !$sala->ativa;
        
        // Se estiver DESATIVANDO a sala
        if (!$novoStatus) {
            $validated = $request->validate([
                'motivo' => 'nullable|string|max:1000'
            ]);

            $sala->ativa = false;
            $sala->motivo_desativacao = $validated['motivo'] ?? null;
            $sala->desativada_por = Auth::id();
            $sala->data_desativacao = now();
            
            $status = 'desativada';
            
            Log::info('Sala desativada por staff', [
                'sala_id' => $id,
                'staff_id' => Auth::id(),
                'motivo' => $validated['motivo'] ?? 'Sem motivo especificado'
            ]);
        } 
        // Se estiver REATIVANDO a sala
        else {
            $sala->ativa = true;
            $sala->motivo_desativacao = null;
            $sala->desativada_por = null;
            $sala->data_desativacao = null;
            
            $status = 'ativada';
            
            Log::info('Sala reativada por staff', [
                'sala_id' => $id,
                'staff_id' => Auth::id()
            ]);
        }
        
        $sala->save();

        return response()->json([
            'success' => true,
            'message' => "Sala {$status} com sucesso!",
            'ativa' => $sala->ativa
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Dados inválidos.',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Erro ao alterar status da sala (staff)', [
            'error' => $e->getMessage(),
            'sala_id' => $id
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erro ao alterar status da sala.'
        ], 500);
    }
}

    /**
     * Criar nova sala
     * POST /salas
     */
    public function store(Request $request)
    {
        Log::info('Iniciando criação de sala', ['user_id' => Auth::id(), 'dados' => $request->all()]);

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

            if ($request->tipo === 'privada' && $request->filled('senha')) {
                $dadosSala['senha_hash'] = Hash::make($request->senha);
                Log::info('Senha definida para sala privada');
            }

            $sala = Sala::create($dadosSala);

            ParticipanteSala::create([
                'sala_id' => $sala->id,
                'usuario_id' => Auth::id(),
                'papel' => 'mestre',
                'data_entrada' => now(),
                'ativo' => true
            ]);

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
        $isStaff = Auth::user()->isStaff();

        $sala = Sala::with(['participantes' => function ($query) {
            $query->where('ativo', true)->with('usuario');
        }])->findOrFail($salaId);

        // NOVO: Verificar se sala está desativada
        if (!$sala->ativa && !$isStaff) {
            return response()->json([
                'success' => false,
                'message' => 'Esta sala foi desativada e não aceita novos participantes.'
            ], 400);
        }

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

            $participante = ParticipanteSala::where('sala_id', $salaId)
                ->where('usuario_id', $userId)
                ->first();

            if ($participante) {
                $participante->ativo = true;
                $participante->data_entrada = now();
                if (empty($participante->papel)) {
                    $participante->papel = 'membro';
                }
                $participante->save();
            } else {
                $participante = ParticipanteSala::create([
                    'sala_id' => $salaId,
                    'usuario_id' => $userId,
                    'papel' => 'membro',
                    'data_entrada' => now(),
                    'ativo' => true
                ]);
            }

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

    /**
     * Iniciar sessão de jogo
     * POST /salas/{id}/iniciar-sessao
     */
    public function iniciarSessao(Request $request, $id)
    {
        try {
            $userId = Auth::id();
            $sala = Sala::findOrFail($id);

            $participante = ParticipanteSala::where('sala_id', $id)
                ->where('usuario_id', $userId)
                ->where('ativo', true)
                ->first();

            if (!$participante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não faz parte desta sala.'
                ], 403);
            }

            $permissao = PermissaoSala::where('sala_id', $id)
                ->where('usuario_id', $userId)
                ->first();

            $ehCriador = (int)$sala->criador_id === (int)$userId;
            $temPermissao = $permissao && $permissao->pode_iniciar_sessao === true;

            if (!$ehCriador && !$temPermissao) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para iniciar sessões nesta sala.'
                ], 403);
            }

            $sessaoAtiva = SessaoJogo::where('sala_id', $id)
                ->whereIn('status', ['ativa', 'pausada'])
                ->first();

            if ($sessaoAtiva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Já existe uma sessão ativa ou pausada nesta sala.',
                    'sessao_existente' => [
                        'id' => $sessaoAtiva->id,
                        'nome' => $sessaoAtiva->nome_sessao,
                        'status' => $sessaoAtiva->status
                    ]
                ], 400);
            }

            $validated = $request->validate([
                'nome_sessao' => 'nullable|string|max:100',
                'configuracoes' => 'nullable|array'
            ]);

            $sessao = SessaoJogo::create([
                'sala_id' => $id,
                'nome_sessao' => $validated['nome_sessao'] ?? 'Sessão de ' . $sala->nome,
                'mestre_id' => $sala->criador_id,
                'data_inicio' => now(),
                'status' => 'ativa',
                'configuracoes' => $validated['configuracoes'] ?? []
            ]);

            $participantesAtivos = ParticipanteSala::where('sala_id', $id)
                ->where('ativo', true)
                ->get();

            foreach ($participantesAtivos as $part) {
                ParticipanteSessao::firstOrCreate(
                    [
                        'sessao_id' => $sessao->id,
                        'usuario_id' => $part->usuario_id
                    ],
                    [
                        'data_entrada' => now(),
                        'ativo' => true
                    ]
                );
            }

            Log::info('Sessão iniciada com sucesso', [
                'sessao_id' => $sessao->id,
                'sala_id' => $id,
                'mestre_id' => $sala->criador_id,
                'iniciada_por' => $userId,
                'participantes' => $participantesAtivos->count()
            ]);

            try {
                broadcast(new \App\Events\SessionStarted($sessao, $sala));
            } catch (\Exception $e) {
                Log::warning('Erro ao disparar evento de sessão iniciada', [
                    'error' => $e->getMessage()
                ]);
            }

            $sessao->load(['mestre', 'participantes.usuario', 'sala']);

            return response()->json([
                'success' => true,
                'message' => 'Sessão iniciada com sucesso!',
                'sessao' => $sessao,
                'redirect_to' => route('sessoes.show', ['id' => $sessao->id])
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao iniciar sessão', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sala_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao iniciar sessão. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Visualizar sala de sessão ativa
     * GET /sessoes/{id}
     */
    public function showSessao($id)
    {
        try {
            $userId = Auth::id();
            $sessao = SessaoJogo::with([
                'sala',
                'mestre',
                'participantes.usuario'
            ])->findOrFail($id);

            $participaSessao = ParticipanteSessao::where('sessao_id', $id)
                ->where('usuario_id', $userId)
                ->where('ativo', true)
                ->exists();

            if (!$participaSessao) {
                return redirect()->route('salas.show', ['id' => $sessao->sala_id])
                    ->with('error', 'Você não faz parte desta sessão.');
            }

            $permissao = PermissaoSala::where('sala_id', $sessao->sala_id)
                ->where('usuario_id', $userId)
                ->first();

            $ehCriador = (int)$sessao->sala->criador_id === (int)$userId;
            $podeFinalizarSessao = $ehCriador || ($permissao && $permissao->pode_iniciar_sessao === true);

            $idsParticipantes = $sessao->participantes->pluck('usuario_id')->toArray();

            return view('sessoes.show', [
                'sessao' => $sessao,
                'pode_finalizar_sessao' => $podeFinalizarSessao,
                'idsParticipantes' => $idsParticipantes
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao carregar sessão', [
                'error' => $e->getMessage(),
                'sessao_id' => $id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('salas.index')
                ->with('error', 'Sessão não encontrada.');
        }
    }

    /**
     * Entrar em uma sessão já iniciada
     * POST /sessoes/{id}/entrar
     */
    public function entrarNaSessao($id)
    {
        try {
            $userId = Auth::id();
            $sessao = SessaoJogo::with('sala')->findOrFail($id);

            if ($sessao->status !== 'ativa') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta sessão não está ativa.'
                ], 400);
            }

            $participanteSala = ParticipanteSala::where('sala_id', $sessao->sala_id)
                ->where('usuario_id', $userId)
                ->where('ativo', true)
                ->first();

            if (!$participanteSala) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não faz parte desta sala.'
                ], 403);
            }

            $jaParticipa = ParticipanteSessao::where('sessao_id', $id)
                ->where('usuario_id', $userId)
                ->where('ativo', true)
                ->exists();

            if ($jaParticipa) {
                return response()->json([
                    'success' => true,
                    'message' => 'Você já está nesta sessão.',
                    'redirect_to' => route('sessoes.show', ['id' => $id])
                ]);
            }

            ParticipanteSessao::create([
                'sessao_id' => $id,
                'usuario_id' => $userId,
                'data_entrada' => now(),
                'ativo' => true
            ]);

            Log::info('Usuário entrou na sessão', [
                'sessao_id' => $id,
                'usuario_id' => $userId,
                'sala_id' => $sessao->sala_id
            ]);

            try {
                broadcast(new \App\Events\UserJoinedSession($sessao, $userId))->toOthers();
            } catch (\Exception $e) {
                Log::warning('Erro ao disparar evento de entrada na sessão', [
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Você entrou na sessão!',
                'redirect_to' => route('sessoes.show', ['id' => $id])
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao entrar na sessão', [
                'error' => $e->getMessage(),
                'sessao_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao entrar na sessão. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Obter sessão ativa de uma sala
     * GET /salas/{id}/sessao-ativa
     */
    public function getSessaoAtiva($id)
    {
        try {
            $sessaoAtiva = SessaoJogo::where('sala_id', $id)
                ->whereIn('status', ['ativa', 'pausada'])
                ->with(['mestre', 'participantes.usuario'])
                ->first();

            return response()->json([
                'success' => true,
                'sessao' => $sessaoAtiva
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar sessão.'
            ], 500);
        }
    }

    /**
     * Finalizar sessão
     * POST /sessoes/{id}/finalizar
     */
    public function finalizarSessao($id)
    {
        try {
            $userId = Auth::id();
            $sessao = SessaoJogo::with('sala')->findOrFail($id);

            $ehCriador = (int)$sessao->sala->criador_id === (int)$userId;
            
            $permissao = PermissaoSala::where('sala_id', $sessao->sala_id)
                ->where('usuario_id', $userId)
                ->first();
            
            $temPermissao = $permissao && $permissao->pode_iniciar_sessao === true;

            if (!$ehCriador && !$temPermissao) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas o mestre ou usuários com permissão podem finalizar a sessão.'
                ], 403);
            }

            if ($sessao->status === 'finalizada') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta sessão já foi finalizada.'
                ], 400);
            }

            $sessao->status = 'finalizada';
            $sessao->data_fim = now();
            $sessao->save();

            ParticipanteSessao::where('sessao_id', $id)
                ->update(['ativo' => false]);

            Log::info('Sessão finalizada com sucesso', [
                'sessao_id' => $id,
                'mestre_id' => $sessao->mestre_id,
                'finalizada_por' => $userId,
                'sala_id' => $sessao->sala_id
            ]);

            try {
                broadcast(new \App\Events\SessionEnd($sessao));
            } catch (\Exception $e) {
                Log::warning('Erro ao disparar evento de sessão finalizada', [
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sessão finalizada com sucesso!',
                'redirect_to' => route('salas.show', ['id' => $sessao->sala_id])
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao finalizar sessão', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sessao_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao finalizar sessão. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Retorna dados do participante + permissões (JSON)
     * GET /salas/{sala}/participantes/{usuario}/permissoes
     */
    public function getPermissoesParticipante(Request $request, $salaId, $usuarioId)
    {
        try {
            $userId = Auth::id();

            $sala = Sala::findOrFail($salaId);

            if ($sala->criador_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Somente o criador da sala pode gerenciar permissões.'
                ], 403);
            }

            $participante = ParticipanteSala::where('sala_id', $salaId)
                ->where('usuario_id', $usuarioId)
                ->first();

            if (!$participante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Participante não encontrado.'
                ], 404);
            }

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

            $columns = ['id', 'username'];
            if (Schema::hasColumn('usuarios', 'avatar')) {
                $columns[] = 'avatar';
            } elseif (Schema::hasColumn('usuarios', 'avatar_url')) {
                $columns[] = 'avatar_url as avatar';
            }

            $usuario = $participante->usuario()->select($columns)->first();

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

    /**
     * Atualiza permissões do participante (JSON)
     * POST /salas/{sala}/participantes/{usuario}/permissoes
     */
    public function updatePermissoesParticipante(Request $request, $salaId, $usuarioId)
    {
        try {
            $userId = Auth::id();

            $sala = Sala::findOrFail($salaId);

            if ($sala->criador_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Somente o criador da sala pode gerenciar permissões.'
                ], 403);
            }

            if ($usuarioId == $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não pode alterar suas próprias permissões aqui.'
                ], 400);
            }

            $participante = ParticipanteSala::where('sala_id', $salaId)
                ->where('usuario_id', $usuarioId)
                ->first();

            if (!$participante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Participante não encontrado.'
                ], 404);
            }

            $data = $request->validate([
                'pode_criar_conteudo' => 'sometimes|boolean',
                'pode_editar_grid' => 'sometimes|boolean',
                'pode_iniciar_sessao' => 'sometimes|boolean',
                'pode_moderar_chat' => 'sometimes|boolean',
                'pode_convidar_usuarios' => 'sometimes|boolean',
                'papel' => 'sometimes|string|in:membro,admin_sala,mestre'
            ]);

            $permissoes = PermissaoSala::firstOrNew([
                'sala_id' => $salaId,
                'usuario_id' => $usuarioId
            ]);

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

            if ($request->has('papel')) {
                $participante->papel = $request->input('papel');
                $participante->save();
            }

            Log::info('Permissões atualizadas', [
                'by_user' => $userId,
                'target_user' => $usuarioId,
                'sala_id' => $salaId
            ]);

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
   public function show($id)
{
    try {
        $userId = Auth::id();
        $isStaff = Auth::user()->isStaff();
        
        $sala = Sala::with([
            'criador',
            'participantes' => function ($query) {
                $query->where('ativo', true)->with('usuario');
            }
        ])->findOrFail($id);

        // NOVO: Verificar se sala está desativada
        if (!$sala->ativa && !$isStaff) {
            return redirect()->route('salas.index')
                ->with('error', 'Esta sala foi desativada e não está mais acessível.');
        }

        $participante = ParticipanteSala::where('sala_id', $id)
            ->where('usuario_id', $userId)
            ->where('ativo', true)
            ->first();

        if (!$participante) {
            return redirect()->route('salas.index')
                ->with('error', 'Você não tem acesso a esta sala.');
        }

            $permissoes = PermissaoSala::where('sala_id', $id)
                ->where('usuario_id', $userId)
                ->first();

            $sessaoAtiva = SessaoJogo::where('sala_id', $id)
                ->whereIn('status', ['ativa', 'pausada'])
                ->with(['mestre', 'participantes'])
                ->first();

            $participaNaSessao = false;
            if ($sessaoAtiva) {
                $participaNaSessao = ParticipanteSessao::where('sessao_id', $sessaoAtiva->id)
                    ->where('usuario_id', $userId)
                    ->where('ativo', true)
                    ->exists();
            }

            $dados = [
                'sala' => $sala,
                'meu_papel' => $participante->papel,
                'minhas_permissoes' => $permissoes,
                'sessao_ativa' => $sessaoAtiva,
                'participa_na_sessao' => $participaNaSessao,
                'websocket_status' => [
                    'ping' => rand(20, 50),
                    'server' => config('broadcasting.connections.pusher.options.host', 'localhost:6001')
                ]
            ];

            return view('salas.show', $dados);

        } catch (\Exception $e) {
            Log::error('Erro ao carregar sala', [
                'error' => $e->getMessage(),
                'sala_id' => $id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('salas.index')
                ->with('error', 'Sala não encontrada.');
        }
    }

    /**
     * Sair da sala
     * POST /salas/{id}/sair
     */
    public function sairSala(Request $request, $id)
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();

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

    /**
     * Obter usuários online da sala
     * GET /salas/{id}/online-users
     */
    public function getOnlineUsers($id)
    {
        try {
            $sala = Sala::findOrFail($id);

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
            'expira_em_horas' => 'integer|min:1|max:168'
        ]);

        try {
            $userId = Auth::id();
            $sala = Sala::findOrFail($id);

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

            $destinatario = Usuario::where('email', $request->destinatario_email)->first();

            if (ParticipanteSala::where('sala_id', $id)->where('usuario_id', $destinatario->id)->where('ativo', true)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este usuário já participa da sala.'
                ], 400);
            }

            $token = Str::random(32);
            $expiracaoHoras = $request->expira_em_horas ?? 72;

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

            if ($convite->destinatario_id != $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este convite não é para você.'
                ], 403);
            }

            if (ParticipanteSala::where('sala_id', $convite->sala_id)->where('usuario_id', $userId)->where('ativo', true)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você já participa desta sala.'
                ], 400);
            }

            ParticipanteSala::create([
                'sala_id' => $convite->sala_id,
                'usuario_id' => $userId,
                'papel' => 'membro',
                'data_entrada' => now(),
                'ativo' => true
            ]);

            PermissaoSala::create([
                'sala_id' => $convite->sala_id,
                'usuario_id' => $userId,
                'pode_criar_conteudo' => true,
                'pode_editar_grid' => false,
                'pode_iniciar_sessao' => false,
                'pode_moderar_chat' => false,
                'pode_convidar_usuarios' => false
            ]);

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
     * Upload/Replace banner da sala
     * POST /salas/{id}/banner
     */
    public function uploadBanner(Request $request, $id)
    {
        $userId = Auth::id();
        $sala = Sala::findOrFail($id);

        if ($sala->criador_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
        }

        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg|max:8192',
        ]);

        try {
            $file = $request->file('banner');

            $img = Image::read($file->getRealPath());

            $width  = $img->width();
            $height = $img->height();

            if ($width <= $height) {
                return response()->json([
                    'success' => false,
                    'message' => 'A imagem precisa estar na orientação horizontal (largura > altura). Recomendado: proporção 16:9.'
                ], 422);
            }

            $img = $img->cover(1600, 900);

            $filename = 'banner_' . \Illuminate\Support\Str::uuid() . '.jpg';
            $path = "salas/banners/{$filename}";

            \Illuminate\Support\Facades\Storage::disk('public')->put($path, (string) $img->toJpeg(85));

            if (!empty($sala->banner_url)) {
                $oldPath = preg_replace('~^/storage/~', '', parse_url($sala->banner_url, PHP_URL_PATH) ?? '');
                if ($oldPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }

            $sala->banner_url = \Illuminate\Support\Facades\Storage::url($path);
            $sala->save();

            return response()->json(['success' => true, 'message' => 'Banner atualizado.', 'banner_url' => $sala->banner_url]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Erro upload banner sala: ' . $e->getMessage(), ['sala_id' => $id, 'user_id' => $userId]);
            return response()->json(['success' => false, 'message' => 'Erro interno ao enviar banner.'], 500);
        }
    }

    /**
     * Criar novo link de convite para sala
     * POST /salas/{id}/links-convite
     */
    public function criarLinkConvite(Request $request, $id)
    {
        try {
            $userId = Auth::id();
            $sala = Sala::findOrFail($id);

            $ehCriador = (int)$sala->criador_id === (int)$userId;
            $permissao = PermissaoSala::where('sala_id', $id)
                ->where('usuario_id', $userId)
                ->first();

            if (!$ehCriador && (!$permissao || !$permissao->pode_convidar_usuarios)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para criar links de convite.'
                ], 403);
            }

            $validated = $request->validate([
                'validade' => 'required|in:1h,1d,1w,1m,never',
                'max_usos' => 'nullable|integer|min:1|max:1000'
            ]);

            $dataExpiracao = null;
            switch ($validated['validade']) {
                case '1h':
                    $dataExpiracao = now()->addHour();
                    break;
                case '1d':
                    $dataExpiracao = now()->addDay();
                    break;
                case '1w':
                    $dataExpiracao = now()->addWeek();
                    break;
                case '1m':
                    $dataExpiracao = now()->addMonth();
                    break;
                case 'never':
                    $dataExpiracao = null;
                    break;
            }

            $link = LinkConviteSala::create([
                'sala_id' => $id,
                'criador_id' => $userId,
                'codigo' => LinkConviteSala::gerarCodigoUnico(),
                'max_usos' => $validated['max_usos'] ?? null,
                'usos_atual' => 0,
                'data_criacao' => now(),
                'data_expiracao' => $dataExpiracao,
                'ativo' => true
            ]);

            Log::info('Link de convite criado', [
                'link_id' => $link->id,
                'sala_id' => $id,
                'criador_id' => $userId,
                'codigo' => $link->codigo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Link de convite criado com sucesso!',
                'link' => [
                    'id' => $link->id,
                    'codigo' => $link->codigo,
                    'url' => $link->getLinkCompleto(),
                    'validade' => $link->getTempoRestante(),
                    'max_usos' => $link->max_usos,
                    'usos_atual' => $link->usos_atual,
                    'criado_em' => $link->data_criacao->format('d/m/Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao criar link de convite', [
                'error' => $e->getMessage(),
                'sala_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar link de convite.'
            ], 500);
        }
    }

    /**
     * Listar links de convite ativos da sala
     * GET /salas/{id}/links-convite
     */
    public function listarLinksConvite($id)
    {
        try {
            $userId = Auth::id();

            $participante = ParticipanteSala::where('sala_id', $id)
                ->where('usuario_id', $userId)
                ->where('ativo', true)
                ->exists();

            if (!$participante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem acesso a esta sala.'
                ], 403);
            }

            $links = \DB::table('links_convite_sala as lcs')
                ->leftJoin('usuarios as u', 'u.id', '=', 'lcs.criador_id')
                ->where('lcs.sala_id', $id)
                ->where('lcs.ativo', true)
                ->select(
                    'lcs.id',
                    'lcs.codigo',
                    'lcs.max_usos',
                    'lcs.usos_atual',
                    'lcs.data_criacao',
                    'lcs.data_expiracao',
                    'u.username as criador_username'
                )
                ->orderBy('lcs.data_criacao', 'desc')
                ->get()
                ->map(function ($link) {
                    $url = url("/convite/{$link->codigo}");

                    $validade = 'Nunca expira';
                    if ($link->data_expiracao) {
                        $expiracao = \Carbon\Carbon::parse($link->data_expiracao);
                        if ($expiracao->isPast()) {
                            $validade = 'Expirado';
                        } else {
                            $validade = $expiracao->diffForHumans();
                        }
                    }

                    $usosRestantes = null;
                    if ($link->max_usos) {
                        $usosRestantes = max(0, $link->max_usos - $link->usos_atual);
                    }

                    $estaValido = true;
                    if ($link->data_expiracao && \Carbon\Carbon::parse($link->data_expiracao)->isPast()) {
                        $estaValido = false;
                    }
                    if ($link->max_usos && $link->usos_atual >= $link->max_usos) {
                        $estaValido = false;
                    }

                    return [
                        'id' => $link->id,
                        'codigo' => $link->codigo,
                        'url' => $url,
                        'criador' => $link->criador_username ?? 'Desconhecido',
                        'validade' => $validade,
                        'max_usos' => $link->max_usos,
                        'usos_atual' => $link->usos_atual,
                        'usos_restantes' => $usosRestantes,
                        'criado_em' => \Carbon\Carbon::parse($link->data_criacao)->format('d/m/Y H:i'),
                        'esta_valido' => $estaValido
                    ];
                });

            Log::info('Links de convite listados', [
                'sala_id' => $id,
                'total' => $links->count()
            ]);

            return response()->json([
                'success' => true,
                'links' => $links
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao listar links', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'sala_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar links: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revogar (deletar) link de convite
     * DELETE /salas/{id}/links-convite/{linkId}
     */
    public function revogarLinkConvite($id, $linkId)
    {
        try {
            $userId = Auth::id();
            $sala = Sala::findOrFail($id);
            $link = LinkConviteSala::findOrFail($linkId);

            if ($link->sala_id != $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link não pertence a esta sala.'
                ], 400);
            }

            $ehCriadorSala = (int)$sala->criador_id === (int)$userId;
            $ehCriadorLink = (int)$link->criador_id === (int)$userId;
            
            $permissao = PermissaoSala::where('sala_id', $id)
                ->where('usuario_id', $userId)
                ->first();

            $temPermissao = $permissao && $permissao->pode_convidar_usuarios;

            if (!$ehCriadorSala && !$ehCriadorLink && !$temPermissao) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para revogar este link.'
                ], 403);
            }

            $link->revogar();

            Log::info('Link de convite revogado', [
                'link_id' => $linkId,
                'sala_id' => $id,
                'revogado_por' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Link de convite revogado com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao revogar link de convite', [
                'error' => $e->getMessage(),
                'sala_id' => $id,
                'link_id' => $linkId,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao revogar link.'
            ], 500);
        }
    }

    /**
     * Exibir página de convite (estilo Discord)
     * GET /convite/{codigo}
     */
    public function mostrarConvite($codigo)
    {
        try {
            $link = LinkConviteSala::where('codigo', $codigo)
                ->with([
                    'sala.criador',
                    'sala.participantes' => function ($query) {
                        $query->where('ativo', true);
                    }
                ])
                ->first();

            if (!$link) {
                return view('convites.invalido', [
                    'mensagem' => 'Link de convite não encontrado.'
                ]);
            }

            $sala = $link->sala;

            if (!$link->estaValido()) {
                $motivo = 'expirado';
                if ($link->expirou()) {
                    $motivo = 'expirado por tempo';
                } elseif ($link->max_usos && $link->usos_atual >= $link->max_usos) {
                    $motivo = 'limite de usos atingido';
                } elseif (!$link->ativo) {
                    $motivo = 'revogado';
                }

                return view('convites.invalido', [
                    'mensagem' => 'Este link de convite está inválido.',
                    'motivo' => $motivo,
                    'sala' => $sala
                ]);
            }

            if (!Auth::check()) {
                session(['redirect_after_login' => "/convite/{$codigo}"]);
                return redirect()->route('usuarios.login')
                    ->with('info', 'Faça login para aceitar o convite.');
            }

            $userId = Auth::id();

            $jaParticipa = ParticipanteSala::where('sala_id', $sala->id)
                ->where('usuario_id', $userId)
                ->where('ativo', true)
                ->exists();

            if ($jaParticipa) {
                return redirect()->route('salas.show', ['id' => $sala->id])
                    ->with('info', 'Você já participa desta sala!');
            }

            $dados = [
                'sala' => $sala,
                'link' => $link,
                'num_membros' => $sala->participantes->count(),
                'data_criacao' => $sala->data_criacao->format('d/m/Y'),
                'codigo' => $codigo
            ];

            return view('convites.mostrar', $dados);

        } catch (\Exception $e) {
            Log::error('Erro ao mostrar convite', [
                'error' => $e->getMessage(),
                'codigo' => $codigo
            ]);

            return view('convites.invalido', [
                'mensagem' => 'Erro ao processar convite.'
            ]);
        }
    }

    /**
     * Aceitar convite via link
     * POST /convite/{codigo}/aceitar
     */
    public function aceitarConviteLink(Request $request, $codigo)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você precisa estar logado.',
                    'redirect_to' => route('usuarios.login')
                ], 401);
            }

            $userId = Auth::id();

            $link = LinkConviteSala::buscarValidoPorCodigo($codigo);

            if (!$link) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link de convite inválido ou expirado.'
                ], 400);
            }

            $sala = $link->sala;

            $jaParticipa = ParticipanteSala::where('sala_id', $sala->id)
                ->where('usuario_id', $userId)
                ->where('ativo', true)
                ->exists();

            if ($jaParticipa) {
                return response()->json([
                    'success' => true,
                    'message' => 'Você já participa desta sala!',
                    'redirect_to' => route('salas.show', ['id' => $sala->id])
                ]);
            }

            if ($sala->estaLotada()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta sala está lotada.'
                ], 400);
            }

            $participante = ParticipanteSala::where('sala_id', $sala->id)
                ->where('usuario_id', $userId)
                ->first();

            if ($participante) {
                $participante->ativo = true;
                $participante->data_entrada = now();
                if (empty($participante->papel)) {
                    $participante->papel = 'membro';
                }
                $participante->save();
            } else {
                ParticipanteSala::create([
                    'sala_id' => $sala->id,
                    'usuario_id' => $userId,
                    'papel' => 'membro',
                    'data_entrada' => now(),
                    'ativo' => true
                ]);
            }

            $permExists = PermissaoSala::where('sala_id', $sala->id)
                ->where('usuario_id', $userId)
                ->exists();

            if (!$permExists) {
                PermissaoSala::create([
                    'sala_id' => $sala->id,
                    'usuario_id' => $userId,
                    'pode_criar_conteudo' => true,
                    'pode_editar_grid' => false,
                    'pode_iniciar_sessao' => false,
                    'pode_moderar_chat' => false,
                    'pode_convidar_usuarios' => false
                ]);
            }

            $link->incrementarUso();

            Log::info('Convite aceito via link', [
                'user_id' => $userId,
                'sala_id' => $sala->id,
                'link_id' => $link->id,
                'codigo' => $codigo,
                'reativado' => isset($participante) && $participante->wasChanged()
            ]);

            try {
                $usuario = Usuario::find($userId);
                broadcast(new \App\Events\UserJoinedRoom($usuario, $sala))->toOthers();
            } catch (\Exception $e) {
                Log::warning('Erro ao disparar evento de entrada', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => "Bem-vindo(a) à sala {$sala->nome}!",
                'redirect_to' => route('salas.show', ['id' => $sala->id])
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao aceitar convite via link', [
                'error' => $e->getMessage(),
                'codigo' => $codigo,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao aceitar convite.'
            ], 500);
        }
    }

    /**
     * Definir cor fallback do banner (hex)
     * POST /salas/{id}/banner/color
     */
    public function setBannerColor(Request $request, $id)
    {
        $userId = Auth::id();
        $sala = Sala::findOrFail($id);

        if ($sala->criador_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
        }

        $validated = $request->validate([
            'color' => ['required','regex:/^#[0-9A-Fa-f]{6}$/']
        ]);

        $sala->banner_color = $validated['color'];
        $sala->save();

        return response()->json(['success' => true, 'message' => 'Cor do banner atualizada.', 'banner_color' => $sala->banner_color]);
    }

    /**
     * Remove banner atual (apaga arquivo e limpa DB)
     * DELETE /salas/{id}/banner
     */
    public function removeBanner(Request $request, $id)
    {
        $userId = Auth::id();
        $sala = Sala::findOrFail($id);

        if ($sala->criador_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
        }

        if (!empty($sala->banner_url)) {
            $oldPath = preg_replace('~^/storage/~', '', parse_url($sala->banner_url, PHP_URL_PATH) ?? '');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $sala->banner_url = null;
        $sala->save();

        return response()->json(['success' => true, 'message' => 'Banner removido.']);
    }

    /**
     * Upload/Replace foto de perfil da sala
     * POST /salas/{id}/profile-photo
     */
    public function uploadProfilePhoto(Request $request, $id)
    {
        $userId = Auth::id();
        $sala = Sala::findOrFail($id);
        
        if ($sala->criador_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
        }

        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            $file = $request->file('profile_photo');
            
            $img = Image::read($file->getRealPath());
            $width = $img->width();
            $height = $img->height();

            $ratio = $width / $height;
            if ($ratio < 0.9 || $ratio > 1.1) {
                return response()->json([
                    'success' => false,
                    'message' => 'A imagem precisa ter proporção quadrada (1:1). Recomendado: 500x500 pixels.'
                ], 422);
            }

            $img = $img->cover(500, 500);

            $filename = 'profile_' . \Illuminate\Support\Str::uuid() . '.jpg';
            $path = "salas/profiles/{$filename}";
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, (string) $img->toJpeg(90));

            if (!empty($sala->profile_photo_url)) {
                $oldPath = preg_replace('~^/storage/~', '', parse_url($sala->profile_photo_url, PHP_URL_PATH) ?? '');
                if ($oldPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }

            $sala->profile_photo_url = \Illuminate\Support\Facades\Storage::url($path);
            $sala->save();

            return response()->json([
                'success' => true, 
                'message' => 'Foto de perfil atualizada com sucesso.', 
                'profile_photo_url' => $sala->profile_photo_url
            ]);
            
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Erro upload foto de perfil sala: ' . $e->getMessage(), [
                'sala_id' => $id, 
                'user_id' => $userId
            ]);
            return response()->json(['success' => false, 'message' => 'Erro interno ao enviar foto de perfil.'], 500);
        }
    }

    /**
     * Definir cor fallback da foto de perfil (hex)
     * POST /salas/{id}/profile-photo/color
     */
    public function setProfilePhotoColor(Request $request, $id)
    {
        $userId = Auth::id();
        $sala = Sala::findOrFail($id);
        
        if ($sala->criador_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
        }

        $validated = $request->validate([
            'color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/']
        ]);

        $sala->profile_photo_color = $validated['color'];
        $sala->save();

        return response()->json([
            'success' => true, 
            'message' => 'Cor da foto de perfil atualizada.', 
            'profile_photo_color' => $sala->profile_photo_color
        ]);
    }

    /**
     * Remove foto de perfil atual (apaga arquivo e limpa DB)
     * DELETE /salas/{id}/profile-photo
     */
    public function removeProfilePhoto(Request $request, $id)
    {
        $userId = Auth::id();
        $sala = Sala::findOrFail($id);
        
        if ($sala->criador_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
        }

        if (!empty($sala->profile_photo_url)) {
            $oldPath = preg_replace('~^/storage/~', '', parse_url($sala->profile_photo_url, PHP_URL_PATH) ?? '');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $sala->profile_photo_url = null;
        $sala->save();

        return response()->json(['success' => true, 'message' => 'Foto de perfil removida.']);
    }

    /**
     * Obter informações da sala (para modal de entrada)
     * GET /salas/{id}/info
     */
    public function infoSala($id, Request $request)
    {
        try {
            $sala = Sala::select(['id', 'nome', 'tipo', 'max_participantes', 'ativa'])
                ->with(['participantes' => function ($query) {
                    $query->where('ativo', true);
                }])
                ->findOrFail($id);

                $isStaff = Auth::user()->isStaff();
        
        if (!$sala->ativa && !$isStaff) {
            return response()->json([
                'success' => false,
                'message' => 'Esta sala foi desativada e não está mais disponível.'
            ], 400);
        }

            $userId = Auth::id();

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
                    'precisa_senha' => $sala->tipo === 'privada'
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

    /**
 * Retornar salas desativadas APENAS para staff
 * GET /api/salas/desativadas
 */
public function getSalasDesativadas(Request $request)
{
    if (!Auth::user()->isStaff()) {
        return response()->json([
            'success' => false,
            'message' => 'Acesso negado.'
        ], 403);
    }

    $perPage = 3;
    $page = $request->input('page', 1);
    $search = $request->input('search', '');

    $query = Sala::with(['criador', 'desativadaPor', 'participantes' => function ($query) {
        $query->where('ativo', true)->with('usuario');
    }])
    ->where('ativa', false);

    // Aplicar busca
    if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->where('nome', 'like', "%{$search}%")
              ->orWhere('descricao', 'like', "%{$search}%")
              ->orWhere('id', 'like', "%{$search}%");
        });
    }

    $total = $query->count();
    
    $salas = $query->orderBy('data_criacao', 'desc')
        ->skip(($page - 1) * $perPage)
        ->take($perPage)
        ->get();

    return response()->json([
        'success' => true,
        'salas' => $salas,
        'pagination' => [
            'current_page' => $page,
            'total' => $total,
            'per_page' => $perPage,
            'has_more' => ($page * $perPage) < $total
        ]
    ]);
}

/**
 * Obter salas desativadas criadas pelo usuário atual
 * GET /api/salas/minhas-desativadas
 */
public function getMinhasSalasDesativadas(Request $request)
{
    $userId = Auth::id();
    
    $salas = Sala::with(['criador', 'desativadaPor', 'participantes' => function ($query) {
        $query->where('ativo', true)->with('usuario');
    }])
    ->where('criador_id', $userId)
    ->where('ativa', false)
    ->orderBy('data_criacao', 'desc')
    ->get();

    return response()->json([
        'success' => true,
        'salas' => $salas,
        'total' => $salas->count()
    ]);
}
}