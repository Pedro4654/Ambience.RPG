<?php
// Arquivo: app/Http/Controllers/Api/OwlbearTokenController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OwlbearMap;
use App\Models\OwlbearToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OwlbearTokenController extends Controller
{
    /**
     * GET /api/owlbear/tokens?mapId={map_id}
     * Lista todos os tokens de um mapa
     */
    public function index(Request $request)
    {
        $mapId = $request->query('mapId');
        
        if (!$mapId) {
            return response()->json(['error' => 'mapId obrigatório'], 400);
        }

        $tokens = OwlbearToken::where('map_id', $mapId)->get();
        
        return response()->json($tokens);
    }

    /**
     * POST /api/owlbear/tokens
     * Cria um novo token
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'map_id' => 'required|exists:owlbear_maps,id',
            'name' => 'required|string',
            'category' => 'nullable|string',
            'file' => 'nullable|string',
            'width' => 'required|integer',
            'height' => 'required|integer',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
        ]);

        $token = OwlbearToken::create([
            'id' => (string) Str::uuid(),
            'owner' => auth()->id(),
            'map_id' => $validated['map_id'],
            'name' => $validated['name'],
            'category' => $validated['category'] ?? 'character',
            'file' => $validated['file'] ?? null,
            'width' => $validated['width'],
            'height' => $validated['height'],
            'x' => $validated['x'],
            'y' => $validated['y'],
            'rotation' => 0,
            'layer' => 0,
        ]);

        return response()->json($token, 201);
    }

    /**
     * PUT /api/owlbear/tokens/{id}
     * Atualiza um token (posição, rotação, etc)
     */
    public function update(Request $request, $id)
    {
        $token = OwlbearToken::findOrFail($id);

        // Jogadores podem mover apenas seus próprios tokens
        $sessao = $token->map->game->sessao;
        $isMestre = auth()->id() === $sessao->mestre_id;
        $isOwner = auth()->id() === $token->owner;

        if (!$isMestre && !$isOwner) {
            return response()->json(['error' => 'Sem permissão'], 403);
        }

        $validated = $request->validate([
            'x' => 'nullable|numeric',
            'y' => 'nullable|numeric',
            'rotation' => 'nullable|numeric',
            'locked' => 'nullable|boolean',
            'visible' => 'nullable|boolean',
        ]);

        $token->update($validated);

        return response()->json($token);
    }

    /**
     * DELETE /api/owlbear/tokens/{id}
     * Remove um token
     */
    public function destroy($id)
    {
        $token = OwlbearToken::findOrFail($id);

        $sessao = $token->map->game->sessao;
        $isMestre = auth()->id() === $sessao->mestre_id;
        $isOwner = auth()->id() === $token->owner;

        if (!$isMestre && !$isOwner) {
            return response()->json(['error' => 'Sem permissão'], 403);
        }

        $token->delete();

        return response()->json(['message' => 'Token deletado'], 200);
    }
}