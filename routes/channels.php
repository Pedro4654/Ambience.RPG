<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;
use App\Models\ParticipanteSala;
use App\Models\ParticipanteSessao;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// ==================== CANAL DE PRESENÇA DA SALA ====================
Broadcast::channel('sala.{salaId}', function (Usuario $user, int $salaId) {
    try {
        $pertence = ParticipanteSala::where('sala_id', $salaId)
            ->where('usuario_id', $user->id)
            ->where('ativo', true)
            ->exists();

        if (!$pertence) {
            Log::info('[Broadcasting] Acesso negado - Usuário não participa da sala', [
                'user_id' => $user->id,
                'sala_id' => $salaId
            ]);
            return false;
        }

        Log::info('[Broadcasting] Acesso concedido ao canal sala', [
            'user_id' => $user->id,
            'sala_id' => $salaId
        ]);

        return [
            'id' => (int) $user->id,
            'name' => $user->username,
            'username' => $user->username,
            'avatar' => $user->avatar,
        ];
    } catch (\Exception $e) {
        Log::error('[Broadcasting] Erro ao autenticar canal sala', [
            'error' => $e->getMessage(),
            'user_id' => $user->id,
            'sala_id' => $salaId
        ]);
        return false;
    }
});

// ==================== CANAL DE PRESENÇA DA SESSÃO ====================
Broadcast::channel('sessao.{sessaoId}', function (Usuario $user, int $sessaoId) {
    try {
        // Verificar se a sessão existe
        $sessao = \App\Models\SessaoJogo::find($sessaoId);
        
        if (!$sessao) {
            Log::warning('[Broadcasting] Sessão não encontrada', [
                'sessao_id' => $sessaoId,
                'user_id' => $user->id
            ]);
            return false;
        }

        // Verificar se o usuário participa da SALA da sessão (não da sessão em si)
        // Isso permite que todos da sala possam se conectar ao canal antes de entrar na sessão
        $participaSala = ParticipanteSala::where('sala_id', $sessao->sala_id)
            ->where('usuario_id', $user->id)
            ->where('ativo', true)
            ->exists();

        if (!$participaSala) {
            Log::info('[Broadcasting] Acesso negado - Usuário não participa da sala da sessão', [
                'user_id' => $user->id,
                'sessao_id' => $sessaoId,
                'sala_id' => $sessao->sala_id
            ]);
            return false;
        }

        Log::info('[Broadcasting] Acesso concedido ao canal sessão', [
            'user_id' => $user->id,
            'sessao_id' => $sessaoId
        ]);

        return [
            'id' => (int) $user->id,
            'name' => $user->username,
            'username' => $user->username,
            'avatar' => $user->avatar,
        ];
    } catch (\Exception $e) {
        Log::error('[Broadcasting] Erro ao autenticar canal sessão', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => $user->id,
            'sessao_id' => $sessaoId
        ]);
        return false;
    }
});

// ==================== CANAL PRIVADO DO USUÁRIO ====================
Broadcast::channel('user.{id}', function (Usuario $user, $id) {
    return (int) $user->id === (int) $id;
});