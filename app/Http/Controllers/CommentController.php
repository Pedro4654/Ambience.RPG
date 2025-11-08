<?php

// ============================================================
// CONTROLLER 3: COMMENT CONTROLLER (Comentários)
// ============================================================

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\SavedPost;
use App\Models\PostFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class CommentController extends Controller {
    

    // ==================== CRIAR COMENTÁRIO ====================

    public function store(Request $request) {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'conteudo' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'post_id' => $validated['post_id'],
            'usuario_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'conteudo' => $validated['conteudo']
        ]);

        $comment->load('autor');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comentário adicionado!',
                'comment' => $comment
            ], 201);
        }

        return redirect()->back()->with('success', 'Comentário adicionado!');
    }

    // ==================== EDITAR COMENTÁRIO ====================

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

        $comment->update($validated);

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

        return response()->json([
            'success' => true,
            'message' => 'Comentário deletado!'
        ]);
    }
}
