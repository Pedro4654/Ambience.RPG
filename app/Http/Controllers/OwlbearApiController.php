<?php
namespace App\Http\Controllers;

use App\Models\SessaoJogo;
use App\Models\Grid;
use App\Models\Token;
use Illuminate\Http\Request;

class OwlbearApiController extends Controller
{
    // Retorna dados da sessão + grid primário
    public function getSession(Request $request)
    {
        $sessaoId = $request->query('id');

        $sessao = SessaoJogo::with(['grids' => function ($q) {
            $q->orderByDesc('pivot_is_primary');
        }])->findOrFail($sessaoId);

        return [
            'id' => $sessao->id,
            'nome' => $sessao->nome,
            'mestre_id' => $sessao->mestre_id,
            'grids' => $sessao->grids->map(fn ($g) => [
                'id' => $g->id,
                'nome' => $g->nome_grid,
                'grid_data' => $g->grid_data,
                'is_primary' => (bool) $g->pivot->is_primary
            ])
        ];
    }

    // Retorna os grids de uma sessão
    public function getGrids(Request $request)
    {
        $sessaoId = $request->query('id');

        $sessao = SessaoJogo::findOrFail($sessaoId);

        return $sessao->grids()->get()->map(fn ($g) => [
            'id' => $g->id,
            'nome' => $g->nome_grid,
            'grid_data' => $g->grid_data
        ]);
    }

    // Salva o grid (mapa)
    public function saveGrid(Request $request)
    {
        $grid = Grid::findOrFail($request->grid_id);

        $grid->update([
            'grid_data' => $request->grid_data,
        ]);

        return ['success' => true];
    }

    // Retorna tokens do grid
    public function getTokens(Request $request)
    {
        $gridId = $request->query('grid_id');

        return Token::where('grid_id', $gridId)->get();
    }

    public function saveToken(Request $request)
    {
        $token = Token::updateOrCreate(
            ['id' => $request->id],
            $request->only([
                'name',
                'color',
                'size',
                'image_url',
                'grid_id',
                'created_by',
                'x',
                'y',
                'rotation',
                'scale',
                'layer'
            ])
        );

        return $token;
    }

    public function deleteToken($id)
    {
        Token::where('id', $id)->delete();

        return ['success' => true];
    }
}
