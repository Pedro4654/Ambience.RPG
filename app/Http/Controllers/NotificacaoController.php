<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificacaoController extends Controller
{
    /**
     * Listar notificações do usuário (AJAX)
     * GET /api/notificacoes
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            
            $limit = $request->input('limit', 10);
            $offset = $request->input('offset', 0);
            
            $notificacoes = Notificacao::where('usuario_id', $userId)
                ->with('remetente:id,username,avatar_url')
                ->orderBy('created_at', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get()
                ->map(function ($notificacao) {
                    return [
                        'id' => $notificacao->id,
                        'tipo' => $notificacao->tipo,
                        'mensagem' => $notificacao->mensagem,
                        'icone' => $notificacao->icone_formatado,
                        'cor' => $notificacao->cor,
                        'cor_tailwind' => $notificacao->cor_tailwind,
                        'link' => $notificacao->link,
                        'lida' => $notificacao->lida,
                        'tempo' => $notificacao->tempo_decorrido,
                        'created_at' => $notificacao->created_at->toIso8601String(),
                        'remetente' => $notificacao->remetente ? [
                            'id' => $notificacao->remetente->id,
                            'username' => $notificacao->remetente->username,
                            'avatar' => $notificacao->remetente->avatar_url
                        ] : null
                    ];
                });

            $totalNaoLidas = Notificacao::contarNaoLidas($userId);

            return response()->json([
                'success' => true,
                'notificacoes' => $notificacoes,
                'total_nao_lidas' => $totalNaoLidas,
                'has_more' => $notificacoes->count() >= $limit
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar notificações', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar notificações'
            ], 500);
        }
    }

    /**
     * Contar notificações não lidas
     * GET /api/notificacoes/count
     */
    public function count()
    {
        try {
            $count = Notificacao::contarNaoLidas(Auth::id());

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao contar notificações', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    /**
     * Marcar notificação como lida
     * POST /api/notificacoes/{id}/marcar-lida
     */
    public function marcarComoLida($id)
    {
        try {
            $notificacao = Notificacao::where('id', $id)
                ->where('usuario_id', Auth::id())
                ->firstOrFail();

            $notificacao->marcarComoLida();

            $totalNaoLidas = Notificacao::contarNaoLidas(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Notificação marcada como lida',
                'total_nao_lidas' => $totalNaoLidas
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao marcar notificação como lida', [
                'error' => $e->getMessage(),
                'notificacao_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar como lida'
            ], 500);
        }
    }

    /**
     * Marcar todas como lidas
     * POST /api/notificacoes/marcar-todas-lidas
     */
    public function marcarTodasComoLidas()
    {
        try {
            Notificacao::marcarTodasComoLidas(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Todas as notificações foram marcadas como lidas',
                'total_nao_lidas' => 0
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao marcar todas como lidas', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar notificações'
            ], 500);
        }
    }

    /**
     * Deletar notificação
     * DELETE /api/notificacoes/{id}
     */
    public function destroy($id)
    {
        try {
            $notificacao = Notificacao::where('id', $id)
                ->where('usuario_id', Auth::id())
                ->firstOrFail();

            $notificacao->delete();

            $totalNaoLidas = Notificacao::contarNaoLidas(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Notificação removida',
                'total_nao_lidas' => $totalNaoLidas
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao deletar notificação', [
                'error' => $e->getMessage(),
                'notificacao_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover notificação'
            ], 500);
        }
    }
}