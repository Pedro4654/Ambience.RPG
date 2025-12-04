<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Post;
use App\Models\UserFollower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacao;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * ProfileController - Gerenciamento de Perfil COMPLETO
 */
class ProfileController extends Controller {
    
    use AuthorizesRequests;
    
    /**
     * Exibe perfil de um usuário específico
     */
    public function show($username) {
        $usuario = Usuario::where('username', $username)->firstOrFail();
        
        // Verificar privacidade
        if ($usuario->privacidade_perfil === 'privado' && Auth::id() !== $usuario->id) {
            // Verificar se está seguindo
            $esta_seguindo = false;
            if (Auth::check()) {
                $esta_seguindo = UserFollower::where('seguidor_id', Auth::id())
                    ->where('seguido_id', $usuario->id)
                    ->exists();
            }
            
            if (!$esta_seguindo && Auth::id() !== $usuario->id) {
                abort(403, 'Este perfil é privado');
            }
        }
        
        // Posts do usuário
        $posts = Post::where('usuario_id', $usuario->id)
            ->with(['autor', 'arquivos'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        // Verificar se está seguindo
        $esta_seguindo = false;
        if (Auth::check()) {
            $esta_seguindo = UserFollower::where('seguidor_id', Auth::id())
                ->where('seguido_id', $usuario->id)
                ->exists();
        }
        
        return view('perfil.show', [
            'usuario' => $usuario,
            'posts' => $posts,
            'esta_seguindo' => $esta_seguindo
        ]);
    }
    
    /**
     * Redireciona para o perfil do usuário autenticado
     */
    public function meu_perfil() {
        if (!Auth::check()) {
            return redirect()->route('usuarios.login');
        }
        
        return redirect()->route('perfil.show', Auth::user()->username);
    }
    
    /**
     * Exibe formulário de edição de perfil
     */
    public function editarPerfil() {
        return view('perfil.edit', [
            'usuario' => Auth::user()
        ]);
    }
    
    /**
     * ✅ ATUALIZADO: Agora aceita todos os campos sociais
     */
    public function update(Request $request) {
        $usuario = Auth::user();
        
        // Validação completa
        $validated = $request->validate([
            'bio' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'discord_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'twitch_url' => 'nullable|url|max:255',
            'privacidade_perfil' => 'nullable|in:publico,privado'
        ], [
            'bio.max' => 'A bio deve ter no máximo 500 caracteres.',
            'website.url' => 'Digite uma URL válida para o website.',
            'discord_url.url' => 'Digite uma URL válida para o Discord.',
            'youtube_url.url' => 'Digite uma URL válida para o YouTube.',
            'twitch_url.url' => 'Digite uma URL válida para a Twitch.',
        ]);
        
        // Atualizar usuário
        $usuario->update($validated);
        
        Log::info('Perfil atualizado com sucesso', [
            'user_id' => $usuario->id,
            'campos_atualizados' => array_keys($validated)
        ]);
        
        // Retornar com mensagem de sucesso
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Perfil atualizado com sucesso!',
                'redirect' => route('perfil.show', $usuario->username)
            ]);
        }
        
        return redirect()->route('perfil.show', $usuario->username)
            ->with('success', 'Perfil atualizado com sucesso!');
    }
    
    /**
     * Seguir usuário
     */
    public function seguir($usuario_id) {
        if (Auth::id() == $usuario_id) {
            return back()->with('error', 'Você não pode seguir a si mesmo');
        }
        
        $usuario = Usuario::findOrFail($usuario_id);
        
        $ja_segue = UserFollower::where('seguidor_id', Auth::id())
            ->where('seguido_id', $usuario_id)
            ->exists();
        
        if ($ja_segue) {
            return back()->with('error', 'Você já segue este usuário');
        }
        
        UserFollower::create([
            'seguidor_id' => Auth::id(),
            'seguido_id' => $usuario_id
        ]);
        
        // Criar notificação
        try {
            Notificacao::notificarNovoSeguidor(Auth::id(), $usuario_id);
            Log::info('Notificação de seguidor criada', [
                'seguidor_id' => Auth::id(),
                'seguido_id' => $usuario_id
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação de seguidor', [
                'error' => $e->getMessage()
            ]);
        }
        
        return back()->with('success', 'Agora você está seguindo ' . $usuario->username);
    }
    
    /**
     * Deixar de seguir
     */
    public function deixar_de_seguir($usuario_id) {
        $usuario = Usuario::findOrFail($usuario_id);
        
        UserFollower::where('seguidor_id', Auth::id())
            ->where('seguido_id', $usuario_id)
            ->delete();
        
        return back()->with('success', 'Você deixou de seguir ' . $usuario->username);
    }
    
    /**
     * Lista de seguidores
     */
    public function seguidores($usuario_id) {
        $usuario = Usuario::findOrFail($usuario_id);
        
        $seguidores = UserFollower::where('seguido_id', $usuario_id)
            ->with('seguidor')
            ->paginate(20);
        
        return view('perfil.seguidores', [
            'usuario' => $usuario,
            'seguidores' => $seguidores
        ]);
    }
    
    /**
     * Lista de usuários seguindo
     */
    public function seguindo($usuario_id) {
        $usuario = Usuario::findOrFail($usuario_id);
        
        $seguindo = UserFollower::where('seguidor_id', $usuario_id)
            ->with('seguido')
            ->paginate(20);
        
        return view('perfil.seguindo', [
            'usuario' => $usuario,
            'seguindo' => $seguindo
        ]);
    }
}