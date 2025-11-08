<?php


// ============================================================
// CONTROLLER 2: LIKE CONTROLLER (Curtidas)
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


class LikeController extends Controller {

    // ==================== ADICIONAR CURTIDA ====================

    public function store(Request $request) {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id'
        ]);

        $post = Post::findOrFail($validated['post_id']);

        // Verificar se já curtiu
        $ja_curtido = Like::where('usuario_id', Auth::id())
            ->where('post_id', $post->id)
            ->exists();

        if ($ja_curtido) {
            return response()->json([
                'success' => false,
                'message' => 'Você já curtiu esta postagem'
            ], 400);
        }

        Like::create([
            'usuario_id' => Auth::id(),
            'post_id' => $post->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Curtido com sucesso!',
            'total_curtidas' => $post->curtidas()->count()
        ]);
    }

    // ==================== REMOVER CURTIDA ====================

    public function destroy($post_id) {
        $post = Post::findOrFail($post_id);

        Like::where('usuario_id', Auth::id())
            ->where('post_id', $post->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Curtida removida!',
            'total_curtidas' => $post->curtidas()->count()
        ]);
    }
}
