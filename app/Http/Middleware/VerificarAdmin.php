<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarAdmin
{
    /**
     * Handle an incoming request.
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

        // Verificar se é admin
        if (!$usuario->isAdmin()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado. Apenas administradores podem realizar esta ação.'
                ], 403);
            }

            return redirect()->route('home')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}