<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Middleware para verificar se usuário está autenticado
 * Redireciona para login caso não esteja logado
 * Específico para sistema de salas RPG
 */
class VerificarAutenticacao
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            Log::info('Acesso negado - usuário não autenticado', [
                'url' => $request->url(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Se for requisição AJAX/API, retornar JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não autenticado. Faça login para continuar.',
                    'redirect_to' => route('usuarios.login')
                ], 401);
            }

            // Para requisições web normais, redirecionar para login
            return redirect()->route('usuarios.login')
                           ->with('error', 'Faça login para acessar esta área.');
        }

        // Verificar se o usuário está ativo
        $usuario = Auth::user();
        if ($usuario->status !== 'ativo') {
            Log::warning('Tentativa de acesso com usuário inativo', [
                'user_id' => $usuario->id,
                'status' => $usuario->status,
                'url' => $request->url()
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conta inativa. Entre em contato com o suporte.',
                    'redirect_to' => route('usuarios.login')
                ], 403);
            }

            return redirect()->route('usuarios.login')
                           ->with('error', 'Sua conta está inativa. Entre em contato com o suporte.');
        }

        // Log de acesso autorizado (apenas para rotas importantes)
        if ($request->is('salas*') || $request->is('api/salas*')) {
            Log::info('Acesso autorizado ao sistema de salas', [
                'user_id' => $usuario->id,
                'username' => $usuario->username,
                'url' => $request->url(),
                'method' => $request->method()
            ]);
        }

        return $next($request);
    }
}