<?php

namespace App\Http\Controllers;

use App\Models\MensagemChat;
use App\Models\ChatAnexo;
use App\Models\Sala;
use App\Models\Usuario;
use App\Models\Ticket;
use App\Models\DenunciaMensagemAnexada;
use App\Models\TicketAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\PermissaoSala;

class ChatController extends Controller
{
    /**
     * Listar mensagens de uma sala (paginadas)
     * GET /salas/{id}/chat/mensagens
     */
    public function listarMensagens(Request $request, $salaId)
    {
        try {
            $usuario = Auth::user();
            $sala = Sala::findOrFail($salaId);

            if (!$sala->temParticipante($usuario->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem acesso a este chat.'
                ], 403);
            }

            $perPage = $request->input('per_page', 50);
            $mensagens = MensagemChat::where('sala_id', $salaId)
                ->ativas()
                ->with(['usuario', 'anexos'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $mensagensProcessadas = $mensagens->getCollection()->map(function ($msg) use ($usuario) {
                $flags = $msg->flags_detectadas ?? [];
                
                return [
                    'id' => $msg->id,
                    'usuario' => [
                        'id' => $msg->usuario->id,
                        'username' => $msg->usuario->username,
                        'avatar_url' => $msg->usuario->avatar_url
                    ],
                    'mensagem' => $msg->getMensagemParaUsuario($usuario),
                    'mensagem_original' => $msg->mensagem_original,
                    'mensagem_censurada' => $msg->deveCensurarPara($usuario),
                    'pode_ver_censurado' => $msg->usuarioPodeVerCensurado($usuario),
                    'flags' => $msg->censurada ? $flags : [],
                    'censurada' => $msg->censurada,
                    'anexos' => $msg->anexos->map(function ($anexo) {
                        return [
                            'id' => $anexo->id,
                            'url' => $anexo->getUrl(),
                            'nome' => $anexo->nome_original,
                            'tamanho' => $anexo->getTamanhoFormatado(),
                            'eh_imagem' => $anexo->ehImagem(),
                            'nsfw_detectado' => $anexo->nsfw_detectado
                        ];
                    }),
                    'editada' => $msg->editada,
                    'created_at' => $msg->created_at->toIso8601String(),
                    'timestamp_formatado' => $msg->getTimestampFormatado()
                ];
            });

            $mensagens->setCollection($mensagensProcessadas);

            return response()->json([
                'success' => true,
                'mensagens' => $mensagens
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao listar mensagens do chat', [
                'error' => $e->getMessage(),
                'sala_id' => $salaId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar mensagens.'
            ], 500);
        }
    }

    /**
     * Enviar nova mensagem
     * POST /salas/{id}/chat/enviar
     */
    public function enviarMensagem(Request $request, $salaId)
    {
        try {
            $usuario = Auth::user();
            $sala = Sala::findOrFail($salaId);

            if (!$sala->temParticipante($usuario->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem acesso a este chat.'
                ], 403);
            }

            $validated = $request->validate([
                'mensagem' => 'required|string|max:2000',
                'flags' => 'nullable|array',
                'anexos' => 'nullable|array',
                'anexos.*' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240'
            ]);

            DB::beginTransaction();

            $mensagemOriginal = $validated['mensagem'];
            $flagsDetectadas = $validated['flags'] ?? [];
            
            $moderacaoBackend = $this->moderarMensagemBackend($mensagemOriginal);

            // ✅ ADICIONAR LOG AQUI:
Log::info('[Chat] Moderação Backend', [
    'inappropriate' => $moderacaoBackend['inappropriate'],
    'flags' => $moderacaoBackend['flags'] ?? [],
    'cleaned' => $moderacaoBackend['cleaned'] ?? '',
    'original' => $mensagemOriginal
]);
            
            if ($moderacaoBackend['inappropriate']) {
                $flagsBackend = $moderacaoBackend['flags'] ?? [];
                $flagsDetectadas = array_unique(array_merge($flagsDetectadas, $flagsBackend));
            }

            Log::info('[Chat] Flags finais detectadas', [
    'flags' => $flagsDetectadas,
    'censurada' => count($flagsDetectadas) > 0
]);

            $censurada = count($flagsDetectadas) > 0;
            $mensagemFinal = $censurada ? $moderacaoBackend['cleaned'] : $mensagemOriginal;

            Log::info('[Chat] Criando mensagem', [
    'censurada' => $censurada,
    'mensagem_final' => $mensagemFinal,
    'mensagem_original' => $censurada ? $mensagemOriginal : null,
    'flags' => $censurada ? $flagsDetectadas : null
]);

            $mensagem = MensagemChat::create([
                'sala_id' => $salaId,
                'usuario_id' => $usuario->id,
                'mensagem' => $mensagemFinal,
                'mensagem_original' => $censurada ? $mensagemOriginal : null,
                'censurada' => $censurada,
                'flags_detectadas' => $censurada ? $flagsDetectadas : null,
                'motivo_censura' => $censurada ? 'content_policy' : null,
                'ip_address' => $request->ip(),
                'editada' => false
            ]);

            if ($request->hasFile('anexos')) {
                foreach ($request->file('anexos') as $file) {
                    $this->processarAnexo($file, $mensagem, $usuario);
                }
            }

            DB::commit();

            $mensagem->load(['usuario', 'anexos']);
            
            // ✅ BROADCAST COM LOGGING DETALHADO
try {
    Log::info('[Chat] 🔔 Preparando broadcast', [
        'mensagem_id' => $mensagem->id,
        'sala_id' => $sala->id,
        'usuario' => $mensagem->usuario->username ?? 'N/A',
        'anexos' => $mensagem->anexos->count()
    ]);

    $evento = new \App\Events\NovaMensagemChat($mensagem, $sala);
    
    Log::info('[Chat] 📡 Evento criado, disparando broadcast...');
    
    broadcast($evento)->toOthers();
    
    Log::info('[Chat] ✅ Broadcast disparado com sucesso');
    
} catch (\Exception $e) {
    Log::error('[Chat] ❌ Erro ao fazer broadcast', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'mensagem_id' => $mensagem->id
    ]);
}

            

            $mensagemRetorno = [
                'id' => $mensagem->id,
                'usuario' => [
                    'id' => $mensagem->usuario->id,
                    'username' => $mensagem->usuario->username,
                    'avatar_url' => $mensagem->usuario->avatar_url
                ],
                'mensagem' => $mensagem->getMensagemParaUsuario($usuario),
                'mensagem_censurada' => $mensagem->deveCensurarPara($usuario),
                'pode_ver_censurado' => $mensagem->usuarioPodeVerCensurado($usuario),
                'flags' => $mensagem->censurada ? ($mensagem->flags_detectadas ?? []) : [],
                'censurada' => $mensagem->censurada,
                'anexos' => $mensagem->anexos->map(function ($anexo) {
                    return [
                        'id' => $anexo->id,
                        'url' => $anexo->getUrl(),
                        'nome' => $anexo->nome_original,
                        'tamanho' => $anexo->getTamanhoFormatado(),
                        'eh_imagem' => $anexo->ehImagem(),
                        'nsfw_detectado' => $anexo->nsfw_detectado
                    ];
                }),
                'editada' => false,
                'created_at' => $mensagem->created_at->toIso8601String(),
                'timestamp_formatado' => $mensagem->getTimestampFormatado()
            ];

            return response()->json([
                'success' => true,
                'message' => 'Mensagem enviada!',
                'mensagem' => $mensagemRetorno
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao enviar mensagem do chat', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar mensagem.'
            ], 500);
        }
    }

    /**
 * Notificar que usuário está digitando
 * POST /salas/{id}/chat/typing
 */
public function notificarDigitando(Request $request, $salaId)
{
    try {
        $usuario = Auth::user();
        $sala = Sala::findOrFail($salaId);

        if (!$sala->temParticipante($usuario->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem acesso a este chat.'
            ], 403);
        }

        $validated = $request->validate([
            'is_typing' => 'required|boolean'
        ]);

        // Broadcast do evento
        broadcast(new \App\Events\UsuarioDigitando(
            $usuario->id,
            $usuario->username,
            $salaId,
            $validated['is_typing']
        ))->toOthers();

        return response()->json([
            'success' => true
        ]);

    } catch (\Exception $e) {
        Log::error('Erro ao notificar digitação', [
            'error' => $e->getMessage(),
            'sala_id' => $salaId
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erro ao notificar digitação.'
        ], 500);
    }
}

    /**
     * ✅ NOVO: Editar mensagem
     * PUT /chat/mensagens/{id}/editar
     */
    public function editarMensagem(Request $request, $id)
{
    try {
        $usuario = Auth::user();
        
        // ✅ CARREGAR MENSAGEM COM RELACIONAMENTOS
        $mensagem = MensagemChat::with(['sala', 'usuario'])->findOrFail($id);

        // ✅ VERIFICAR SE MENSAGEM FOI DELETADA PRIMEIRO
        if ($mensagem->deletada) {
            return response()->json([
                'success' => false,
                'message' => 'Esta mensagem foi deletada e não pode ser editada.'
            ], 400);
        }

        // ✅ VERIFICAR PERMISSÕES
        $ehAutor = (int)$mensagem->usuario_id === (int)$usuario->id;
        $ehCriadorSala = (int)$mensagem->sala->criador_id === (int)$usuario->id;
        
        // ✅ BUSCAR PERMISSÃO (pode não existir)
        $permissao = PermissaoSala::where('sala_id', $mensagem->sala_id)
            ->where('usuario_id', $usuario->id)
            ->first();
        
        $podeModerarChat = $permissao ? (bool)$permissao->pode_moderar_chat : false;
        
        // ✅ VERIFICAR SE TEM PERMISSÃO
        if (!$ehAutor && !$ehCriadorSala && !$podeModerarChat) {
            Log::warning('[Chat] Tentativa de edição sem permissão', [
                'mensagem_id' => $id,
                'usuario_id' => $usuario->id,
                'eh_autor' => $ehAutor,
                'eh_criador' => $ehCriadorSala,
                'pode_moderar' => $podeModerarChat
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para editar esta mensagem.'
            ], 403);
        }

        // ✅ VALIDAR INPUT
        $validated = $request->validate([
            'mensagem' => 'required|string|max:2000'
        ]);

        $novaMensagem = trim($validated['mensagem']);
        
        // ✅ VERIFICAR SE A MENSAGEM É DIFERENTE
        $mensagemOriginalAtual = $mensagem->mensagem_original ?? $mensagem->mensagem;
        if ($novaMensagem === $mensagemOriginalAtual) {
            return response()->json([
                'success' => false,
                'message' => 'A mensagem não foi alterada.'
            ], 400);
        }

        // ✅ MODERAR NOVA MENSAGEM
        $moderacao = $this->moderarMensagemBackend($novaMensagem);
        $flags = $moderacao['flags'] ?? [];
        $censurada = count($flags) > 0;
        $mensagemFinal = $censurada ? ($moderacao['cleaned'] ?? $novaMensagem) : $novaMensagem;

        // ✅ ATUALIZAR MENSAGEM
        $mensagem->update([
            'mensagem' => $mensagemFinal,
            'mensagem_original' => $censurada ? $novaMensagem : null,
            'censurada' => $censurada,
            'flags_detectadas' => $censurada ? $flags : null,
            'editada' => true,
            'editada_em' => now()
        ]);

        Log::info('[Chat] Mensagem editada com sucesso', [
            'mensagem_id' => $mensagem->id,
            'sala_id' => $mensagem->sala_id,
            'editado_por' => $usuario->id,
            'eh_moderador' => !$ehAutor,
            'censurada' => $censurada
        ]);

        // ✅ BROADCAST (COM TRY-CATCH SEPARADO)
        try {
            broadcast(new \App\Events\MensagemChatEditada(
                $mensagem->id,
                $mensagem->sala_id,
                $mensagemFinal
            ))->toOthers();
            
            Log::info('[Chat] ✅ Broadcast de edição enviado');
        } catch (\Exception $e) {
            // Não falhar se o broadcast der erro
            Log::error('[Chat] ⚠️ Erro no broadcast de edição (não crítico)', [
                'error' => $e->getMessage(),
                'mensagem_id' => $mensagem->id
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mensagem editada com sucesso!',
            'mensagem' => $mensagemFinal,
            'editada' => true,
            'censurada' => $censurada
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::warning('[Chat] Erro de validação ao editar mensagem', [
            'errors' => $e->errors(),
            'mensagem_id' => $id
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Dados inválidos.',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('[Chat] Mensagem não encontrada', [
            'mensagem_id' => $id
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Mensagem não encontrada.'
        ], 404);
        
    } catch (\Exception $e) {
        Log::error('[Chat] Erro crítico ao editar mensagem', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'mensagem_id' => $id,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erro interno ao editar mensagem.'
        ], 500);
    }
}

    /**
     * Deletar mensagem (apenas autor ou staff)
     * DELETE /chat/mensagens/{id}
     */
    public function deletarMensagem($id)
{
    try {
        $usuario = Auth::user();
        $mensagem = MensagemChat::with('sala')->findOrFail($id);

        // ✅ VERIFICAR PERMISSÕES
        $ehAutor = $mensagem->usuario_id === $usuario->id;
        $ehCriadorSala = $mensagem->sala->criador_id === $usuario->id;
        
        $permissao = PermissaoSala::where('sala_id', $mensagem->sala_id)
            ->where('usuario_id', $usuario->id)
            ->first();
        
        $podeModerarChat = $permissao && $permissao->pode_moderar_chat;
        
        // ✅ NOVO: Verificar se é autor OU moderador
        if (!$ehAutor && !$ehCriadorSala && !$podeModerarChat) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para deletar esta mensagem.'
            ], 403);
        }

        $mensagem->marcarDeletada($usuario);

        // ✅ Broadcast
        try {
            Log::info('[Chat] Broadcasting deleção de mensagem', [
                'mensagem_id' => $mensagem->id,
                'sala_id' => $mensagem->sala_id,
                'deletado_por' => $usuario->id,
                'eh_moderador' => !$ehAutor
            ]);

            broadcast(new \App\Events\MensagemChatDeletada(
                $mensagem->id,
                $mensagem->sala_id
            ));

            Log::info('[Chat] ✅ Broadcast de deleção enviado para todos');
        } catch (\Exception $e) {
            Log::error('[Chat] ❌ Erro ao fazer broadcast de deleção', [
                'error' => $e->getMessage(),
                'mensagem_id' => $mensagem->id
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mensagem deletada!'
        ]);

    } catch (\Exception $e) {
        Log::error('Erro ao deletar mensagem', [
            'error' => $e->getMessage(),
            'mensagem_id' => $id
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erro ao deletar mensagem.'
        ], 500);
    }
}

    /**
     * Ver conteúdo censurado (apenas +18)
     * POST /chat/mensagens/{id}/ver-censurado
     */
    public function verConteudoCensurado($id)
    {
        try {
            $usuario = Auth::user();
            $mensagem = MensagemChat::findOrFail($id);

            if (!$mensagem->usuarioPodeVerCensurado($usuario)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você precisa ter 18+ anos para ver este conteúdo.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'mensagem_original' => $mensagem->mensagem_original ?? $mensagem->mensagem,
                'flags' => $mensagem->flags_detectadas ?? []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar conteúdo.'
            ], 500);
        }
    }

    /**
     * Denunciar usuário via chat
     * POST /chat/denunciar-usuario
     */
    public function denunciarUsuario(Request $request)
    {
        try {
            $usuario = Auth::user();

            $validated = $request->validate([
                'sala_id' => 'required|exists:salas,id',
                'usuario_denunciado_id' => 'required|exists:usuarios,id',
                'tipo_denuncia' => 'required|in:spam,abuso,assedio,sexual,outro',
                'descricao' => 'required|string|min:20|max:2000',
                'mensagens_selecionadas' => 'nullable|array',
                'mensagens_selecionadas.*' => 'exists:mensagens_chat,id',
                'anexos.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf'
            ]);

            $usuarioDenunciado = Usuario::findOrFail($validated['usuario_denunciado_id']);

            if ($usuarioDenunciado->id === $usuario->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não pode denunciar a si mesmo.'
                ], 400);
            }

            if ($usuarioDenunciado->isStaff()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível denunciar membros da equipe.'
                ], 400);
            }

            DB::beginTransaction();

            $ticket = Ticket::create([
                'numero_ticket' => Ticket::gerarNumeroTicket(),
                'usuario_id' => $usuario->id,
                'usuario_denunciado_id' => $usuarioDenunciado->id,
                'sala_id' => $validated['sala_id'],
                'categoria' => 'denuncia',
                'tipo_denuncia' => 'chat',
                'assunto' => "Denúncia de Chat - {$validated['tipo_denuncia']}",
                'descricao' => $validated['descricao'],
                'status' => 'novo',
                'prioridade' => 'alta',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data_abertura' => now(),
                'visivel_usuario' => true
            ]);

            if (!empty($validated['mensagens_selecionadas'])) {
                foreach ($validated['mensagens_selecionadas'] as $mensagemId) {
                    DenunciaMensagemAnexada::create([
                        'ticket_id' => $ticket->id,
                        'mensagem_id' => $mensagemId
                    ]);
                }
            }

            if ($request->hasFile('anexos')) {
                foreach ($request->file('anexos') as $file) {
                    $this->processarAnexoDenuncia($file, $ticket, $usuario);
                }
            }

            $ticket->registrarAcao('criado', [
                'tipo' => 'denuncia_chat',
                'tipo_denuncia' => $validated['tipo_denuncia'],
                'usuario_denunciado' => $usuarioDenunciado->username
            ], $usuario);

            DB::commit();

            Log::info('Denúncia de chat criada', [
                'ticket_id' => $ticket->id,
                'denunciante' => $usuario->id,
                'denunciado' => $usuarioDenunciado->id,
                'sala_id' => $validated['sala_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Denúncia enviada com sucesso! Ticket #' . $ticket->numero_ticket,
                'ticket_id' => $ticket->id,
                'numero_ticket' => $ticket->numero_ticket
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar denúncia de chat', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar denúncia.'
            ], 500);
        }
    }

    // ==================== MÉTODOS PRIVADOS ====================

    private function moderarMensagemBackend($texto)
    {
        try {
            $moderationController = new \App\Http\Controllers\ModerationController();
            $request = new Request(['text' => $texto]);
            $response = $moderationController->moderate($request);
            return $response->getData(true);
        } catch (\Exception $e) {
            Log::error('Erro na moderação backend', ['error' => $e->getMessage()]);
            return ['inappropriate' => false, 'matched' => [], 'cleaned' => $texto, 'flags' => []];
        }
    }

    private function processarAnexo($file, MensagemChat $mensagem, Usuario $usuario)
    {
        try {
            $nomeOriginal = $file->getClientOriginalName();
            $extensao = $file->getClientOriginalExtension();
            $nomeArquivo = Str::random(40) . '_' . time() . '.' . $extensao;
            
            $diretorio = 'chat/sala_' . $mensagem->sala_id;
            if (!Storage::disk('public')->exists($diretorio)) {
                Storage::disk('public')->makeDirectory($diretorio);
            }
            
            $caminho = $file->storeAs($diretorio, $nomeArquivo, 'public');
            $hashArquivo = hash_file('sha256', $file->getRealPath());

            ChatAnexo::create([
                'mensagem_id' => $mensagem->id,
                'usuario_id' => $usuario->id,
                'nome_original' => $nomeOriginal,
                'nome_arquivo' => $nomeArquivo,
                'caminho' => $caminho,
                'tipo_mime' => $file->getMimeType(),
                'tamanho' => $file->getSize(),
                'hash_arquivo' => $hashArquivo,
                'nsfw_detectado' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao processar anexo do chat', [
                'mensagem_id' => $mensagem->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function processarAnexoDenuncia($file, Ticket $ticket, Usuario $usuario)
    {
        try {
            $nomeOriginal = $file->getClientOriginalName();
            $extensao = $file->getClientOriginalExtension();
            $nomeArquivo = Str::random(40) . '_' . time() . '.' . $extensao;
            
            $diretorio = 'suporte/tickets/' . $ticket->id;
            if (!Storage::disk('public')->exists($diretorio)) {
                Storage::disk('public')->makeDirectory($diretorio);
            }
            
            $caminho = $file->storeAs($diretorio, $nomeArquivo, 'public');
            $hashArquivo = hash_file('sha256', $file->getRealPath());

            TicketAnexo::create([
                'ticket_id' => $ticket->id,
                'usuario_id' => $usuario->id,
                'resposta_id' => null,
                'nome_original' => $nomeOriginal,
                'nome_arquivo' => $nomeArquivo,
                'caminho' => $caminho,
                'tipo_mime' => $file->getMimeType(),
                'tamanho' => $file->getSize(),
                'hash_arquivo' => $hashArquivo
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao processar anexo de denúncia', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}