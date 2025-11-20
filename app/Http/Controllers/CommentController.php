<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\SavedPost;
use App\Models\PostFile;
use Illuminate\Http\Request;
use App\Models\Notificacao; // ✅ NOVO
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller {
    
    // ==================== CRIAR COMENTÁRIO (COM MODERAÇÃO) ====================

    public function store(Request $request) {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'conteudo' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        // ========== MODERAÇÃO DE TEXTO ==========
        try {
            $moderacao = $this->moderarTexto($validated['conteudo']);
            
            if ($moderacao && $moderacao['inappropriate']) {
                Log::warning('Comentário bloqueado: conteúdo inapropriado', [
                    'usuario_id' => Auth::id(),
                    'post_id' => $validated['post_id'],
                    'conteudo_preview' => substr($validated['conteudo'], 0, 50)
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'O comentário contém conteúdo inapropriado e foi bloqueado.'
                    ], 422);
                }
                
                return back()->withErrors([
                    'conteudo' => 'O comentário contém conteúdo inapropriado.'
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Erro na moderação de comentário', [
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);
            // Continua mesmo se a moderação falhar
        }

        $comment = Comment::create([
            'post_id' => $validated['post_id'],
            'usuario_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'conteudo' => $validated['conteudo']
        ]);

        $comment->load('autor');

        $post = Post::find($validated['post_id']);

        // ✅ CRIAR NOTIFICAÇÃO
        try {
            Notificacao::notificarComentario(
                Auth::id(), 
                $post->id, 
                $validated['conteudo'],
                $post->usuario_id
            );
            Log::info('Notificação de comentário criada', [
                'usuario_id' => Auth::id(),
                'post_id' => $post->id,
                'autor_id' => $post->usuario_id
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação de comentário', [
                'error' => $e->getMessage()
            ]);
        }


        Log::info('Comentário criado', [
            'comment_id' => $comment->id,
            'post_id' => $validated['post_id'],
            'usuario_id' => Auth::id()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comentário adicionado!',
                'comment' => $comment
            ], 201);
        }

        return redirect()->back()->with('success', 'Comentário adicionado!');
    }

    // ==================== EDITAR COMENTÁRIO (COM MODERAÇÃO) ====================

    public function update(Request $request, $id) {
        $comment = Comment::findOrFail($id);

        if ($comment->usuario_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autorizado'
            ], 403);
        }

        $validated = $request->validate([
            'conteudo' => 'required|string|max:1000'
        ]);

        // ========== MODERAÇÃO DE TEXTO ==========
        try {
            $moderacao = $this->moderarTexto($validated['conteudo']);
            
            if ($moderacao && $moderacao['inappropriate']) {
                Log::warning('Edição de comentário bloqueada: conteúdo inapropriado', [
                    'comment_id' => $id,
                    'usuario_id' => Auth::id()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'O comentário contém conteúdo inapropriado.'
                ], 422);
            }
            
        } catch (\Exception $e) {
            Log::error('Erro na moderação ao editar comentário', ['error' => $e->getMessage()]);
        }

        $comment->update($validated);

        Log::info('Comentário atualizado', [
            'comment_id' => $id,
            'usuario_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comentário atualizado!',
            'comment' => $comment
        ]);
    }

    // ==================== DELETAR COMENTÁRIO ====================

    public function destroy($id) {
        $comment = Comment::findOrFail($id);

        if ($comment->usuario_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autorizado'
            ], 403);
        }

        $comment->delete();

        Log::info('Comentário deletado', [
            'comment_id' => $id,
            'usuario_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comentário deletado!'
        ]);
    }

    /**
     * Moderar texto via endpoint /moderate
     */
    private function moderarTexto($texto) {
        if (empty($texto)) return null;
        
        try {
            $response = \Illuminate\Support\Facades\Http::post(url('/moderate'), [
                'text' => $texto
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            Log::warning('Moderação de comentário falhou', ['status' => $response->status()]);
            return null;
            
        } catch (\Exception $e) {
            Log::error('Erro ao chamar endpoint de moderação para comentário', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}