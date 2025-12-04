<?php
// Arquivo: app/Http/Controllers/Api/OwlbearGameController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sessao;
use App\Models\OwlbearGame;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OwlbearGameController extends Controller
{
    /**
     * GET /api/owlbear/session?sessionId={sessao_id}
     * Retorna ou cria o jogo do Owlbear vinculado à sessão Laravel
     */
    public function show(Request $request)
    {
        $sessaoId = $request->query('sessionId');
        
        if (!$sessaoId) {
            return response()->json(['error' => 'sessionId obrigatório'], 400);
        }

        // Verifica se a sessão existe
        $sessao = Sessao::find($sessaoId);
        if (!$sessao) {
            return response()->json(['error' => 'Sessão não encontrada'], 404);
        }

        // Busca ou cria o jogo do Owlbear
        $game = OwlbearGame::firstOrCreate(
            ['sessao_id' => $sessaoId],
            [
                'id' => (string) Str::uuid(),
                'password' => null,
            ]
        );

        return response()->json([
            'id' => $game->id,
            'sessao_id' => $game->sessao_id,
            'created_at' => $game->created_at,
            'updated_at' => $game->updated_at,
        ]);
    }

    /**
     * POST /api/owlbear/session
     * Cria um novo jogo (caso não exista)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sessao_id' => 'required|exists:sessoes_jogo,id',
            'password' => 'nullable|string',
        ]);

        $game = OwlbearGame::create([
            'id' => (string) Str::uuid(),
            'sessao_id' => $validated['sessao_id'],
            'password' => $validated['password'] ?? null,
        ]);

        return response()->json($game, 201);
    }
}