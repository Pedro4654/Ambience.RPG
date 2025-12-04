<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SessionPlayersController extends Controller
{
    public function index($sessao_id)
{
    try {
        $sala = DB::table('salas')
            ->where('sessao_id', $sessao_id)
            ->orWhere('id', $sessao_id)
            ->first();

        if (!$sala) {
            \Log::warning("Sala não encontrada: {$sessao_id}");
            return response()->json(['players' => []]);
        }

        $participantes = DB::table('participantes_sala')
            ->join('usuarios', 'participantes_sala.usuario_id', '=', 'usuarios.id')
            ->where('participantes_sala.sala_id', $sala->id)
            ->where('participantes_sala.ativo', true)
            ->select(
                'usuarios.id',
                'usuarios.username',
                'usuarios.nickname',
                'usuarios.avatar_url',
                'usuarios.current_session_id',
                'participantes_sala.papel',
                DB::raw("CASE WHEN participantes_sala.papel = 'mestre' THEN 1 ELSE 0 END as is_master"),
                // CORRIJA AQUI: Compare com ID numérico E UUID
                DB::raw("CASE WHEN usuarios.current_session_id = '{$sala->id}' OR usuarios.current_session_id = '{$sala->sessao_id}' THEN 1 ELSE 0 END as is_online"),
                'usuarios.last_seen'
            )
            ->get();

        return response()->json(['players' => $participantes]);

    } catch (\Exception $e) {
        \Log::error('Erro SessionPlayersController: ' . $e->getMessage());
        return response()->json(['players' => [], 'error' => $e->getMessage()], 500);
    }
}

    public function join(Request $request, $sessao_id)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => true]);
            }
            
            $user = Auth::user();
            
            DB::table('usuarios')
                ->where('id', $user->id)
                ->update([
                    'current_session_id' => $sessao_id,
                    'is_online' => true,
                    'last_seen' => now()
                ]);

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            \Log::error('Erro join: ' . $e->getMessage());
            return response()->json(['success' => true]);
        }
    }

    public function leave(Request $request, $sessao_id)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => true]);
            }
            
            $user = Auth::user();
            
            DB::table('usuarios')
                ->where('id', $user->id)
                ->update([
                    'current_session_id' => null,
                    'is_online' => false,
                    'last_seen' => now()
                ]);

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => true]);
        }
    }

    public function heartbeat(Request $request, $sessao_id)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => true]);
            }
            
            $user = Auth::user();
            
            DB::table('usuarios')
                ->where('id', $user->id)
                ->update(['last_seen' => now()]);

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => true]);
        }
    }
}