<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SavedPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * SavedPostController - Posts Salvos CORRIGIDO
 * Gerencia os posts salvos pelo usuário
 */
class SavedPostController extends Controller {
    
    use AuthorizesRequests;
    
    /**
     * Exibe lista de posts salvos pelo usuário autenticado
     */
    public function index() {
        // Verificar se está autenticado
        if (!Auth::check()) {
            return redirect()->route('usuarios.login')->with('error', 'Faça login primeiro');
        }
        
        // Buscar posts salvos do usuário
        $saved_posts = SavedPost::where('usuario_id', Auth::id())
            ->with(['post' => function($query) {
                $query->with(['autor:id,username,avatar_url', 'arquivos']);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        return view('comunidade.saved', [
            'saved_posts' => $saved_posts
        ]);
    }
    
    /**
     * Salva uma postagem
     */
    public function store(Request $request) {
        // Verificar autenticação
        if (!Auth::check()) {
            return redirect()->route('usuarios.login');
        }
        
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id'
        ]);
        
        // Verificar se post existe
        $post = Post::find($validated['post_id']);
        if (!$post) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Postagem não encontrada'
                ], 404);
            }
            return back()->with('error', 'Postagem não encontrada!');
        }
        
        // Verificar se já está salvo
        $ja_salvo = SavedPost::where('usuario_id', Auth::id())
            ->where('post_id', $validated['post_id'])
            ->exists();
        
        if ($ja_salvo) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta postagem já está salva'
                ], 400);
            }
            return back()->with('error', 'Postagem já salva!');
        }
        
        // Criar novo saved post
        SavedPost::create([
            'usuario_id' => Auth::id(),
            'post_id' => $validated['post_id']
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Postagem salva com sucesso!'
            ]);
        }
        
        return back()->with('success', 'Postagem salva!');
    }
    
    /**
     * Remove postagem dos salvos
     */
    public function destroy($post_id) {
        // Verificar autenticação
        if (!Auth::check()) {
            return redirect()->route('usuarios.login');
        }
        
        // Buscar e deletar
        $deleted = SavedPost::where('usuario_id', Auth::id())
            ->where('post_id', $post_id)
            ->delete();
        
        if (!$deleted) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post não encontrado'
                ], 404);
            }
            return back()->with('error', 'Erro ao remover dos salvos!');
        }
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Removido dos salvos!'
            ]);
        }
        
        return back()->with('success', 'Removido dos salvos!');
    }
}