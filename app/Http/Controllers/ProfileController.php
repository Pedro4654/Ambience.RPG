<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Post;
use App\Models\UserFollower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacao; // ✅ NOVO
use Illuminate\Support\Facades\Log; // ✅ NOVO
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * ProfileController - Gerenciamento de Perfil ATUALIZADO
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
        
        // Estatísticas
        $num_seguidores = UserFollower::where('seguido_id', $usuario->id)->count();
        $num_seguindo = UserFollower::where('seguidor_id', $usuario->id)->count();
        
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
            'num_seguidores' => $num_seguidores,
            'num_seguindo' => $num_seguindo,
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
     * Atualiza informações do perfil
     */
    public function update(Request $request) {
        $usuario = Auth::user();
        
        $validated = $request->validate([
            'bio' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'privacidade_perfil' => 'required|in:publico,privado'
        ]);
        
        $usuario->update($validated);
        
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
        
        // Atualizar contador
        $usuario->increment('total_seguidores');
        
        // ✅ NOVA NOTIFICAÇÃO
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
        
        // Atualizar contador
        $usuario->decrement('total_seguidores');
        
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