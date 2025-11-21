<?php
// Arquivo: app/Http/Controllers/Api/OwlbearMapController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OwlbearGame;
use App\Models\OwlbearMap;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OwlbearMapController extends Controller
{
    /**
     * GET /api/owlbear/maps?gameId={game_id}
     * Lista todos os mapas de um jogo
     */
    public function index(Request $request)
    {
        $gameId = $request->query('gameId');
        
        if (!$gameId) {
            return response()->json(['error' => 'gameId obrigatório'], 400);
        }

        $maps = OwlbearMap::where('game_id', $gameId)->get();
        
        return response()->json($maps);
    }

    /**
     * POST /api/owlbear/maps
     * Cria um novo mapa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:owlbear_games,id',
            'name' => 'required|string',
            'width' => 'required|integer',
            'height' => 'required|integer',
            'thumbnail' => 'nullable|string',
            'grid_type' => 'nullable|string',
            'show_grid' => 'nullable|boolean',
            'snap_to_grid' => 'nullable|boolean',
        ]);

        $map = OwlbearMap::create([
            'id' => (string) Str::uuid(),
            'owner' => auth()->id(),
            'game_id' => $validated['game_id'],
            'name' => $validated['name'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'thumbnail' => $validated['thumbnail'] ?? null,
            'grid_type' => $validated['grid_type'] ?? 'square',
            'show_grid' => $validated['show_grid'] ?? true,
            'snap_to_grid' => $validated['snap_to_grid'] ?? true,
        ]);

        return response()->json($map, 201);
    }

    /**
     * PUT /api/owlbear/maps/{id}
     * Atualiza um mapa existente
     */
    public function update(Request $request, $id)
    {
        $map = OwlbearMap::findOrFail($id);

        // Apenas o dono ou o mestre pode editar
        $sessao = $map->game->sessao;
        if (auth()->id() !== $map->owner && auth()->id() !== $sessao->mestre_id) {
            return response()->json(['error' => 'Sem permissão'], 403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string',
            'fog' => 'nullable|array',
            'grid' => 'nullable|array',
        ]);

        $map->update($validated);

        return response()->json($map);
    }

    /**
     * DELETE /api/owlbear/maps/{id}
     * Remove um mapa
     */
    public function destroy($id)
    {
        $map = OwlbearMap::findOrFail($id);

        $sessao = $map->game->sessao;
        if (auth()->id() !== $sessao->mestre_id) {
            return response()->json(['error' => 'Apenas o mestre pode deletar'], 403);
        }

        $map->delete();

        return response()->json(['message' => 'Mapa deletado'], 200);
    }
}