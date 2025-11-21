<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se usuário está autenticado
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado. Faça login primeiro.'
                ], 401);
            }

            return redirect()->route('usuarios.login')
                ->with('error', 'Você precisa estar logado para acessar esta área.');
        }

        $usuario = auth()->user();

        // Verificar se é staff (moderador ou admin)
        if (!$usuario->isStaff()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado. Apenas staff pode acessar esta área.'
                ], 403);
            }

            return redirect()->route('suporte.index')
                ->with('error', 'Você não tem permissão para acessar o painel de moderação.');
        }

        return $next($request);
    }
}