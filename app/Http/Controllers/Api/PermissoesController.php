<?php
// app/Http/Controllers/Api/PermissoesController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PermissaoSala;
use App\Models\SessaoJogo;

class PermissoesController extends Controller
{
    public function show($sessao_id, $usuario_id)
    {
        // Busca sessao e pega a sala_id dela
        $sessao = SessaoJogo::find($sessao_id);

        if (!$sessao) {
            return response()->json([
                'pode_criar_conteudo' => false,
                'pode_editar_grid' => false,
                'pode_iniciar_sessao' => false,
                'pode_moderar_chat' => false,
                'pode_convidar_usuarios' => false,
                'is_mestre' => false
            ]);
        }

        // MESTRE SEMPRE TEM TODAS AS PERMISSÕES
        if ($sessao->mestre_id == $usuario_id) {
            return response()->json([
                'pode_criar_conteudo' => true,
                'pode_editar_grid' => true,
                'pode_iniciar_sessao' => true,
                'pode_moderar_chat' => true,
                'pode_convidar_usuarios' => true,
                'is_mestre' => true
            ]);
        }

        $permissoes = PermissaoSala::where('sala_id', $sessao->sala_id)
            ->where('usuario_id', $usuario_id)
            ->first();

        if (!$permissoes) {
            return response()->json([
                'pode_criar_conteudo' => false,
                'pode_editar_grid' => false,
                'pode_iniciar_sessao' => false,
                'pode_moderar_chat' => false,
                'pode_convidar_usuarios' => false,
                'is_mestre' => false
            ]);
        }

        return response()->json([
            'pode_criar_conteudo' => (bool) $permissoes->pode_criar_conteudo,
            'pode_editar_grid' => (bool) $permissoes->pode_editar_grid,
            'pode_iniciar_sessao' => (bool) $permissoes->pode_iniciar_sessao,
            'pode_moderar_chat' => (bool) $permissoes->pode_moderar_chat,
            'pode_convidar_usuarios' => (bool) $permissoes->pode_convidar_usuarios,
            'is_mestre' => false
        ]);
    }
}