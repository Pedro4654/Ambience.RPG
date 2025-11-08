<?php

// ============================================================
// POLICIES - Autorização
// ============================================================

// Crie um arquivo: app/Policies/PostPolicy.php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\Post;

class PostPolicy {
    
    public function update(Usuario $user, Post $post): bool {
        return $user->id === $post->usuario_id;
    }

    public function delete(Usuario $user, Post $post): bool {
        return $user->id === $post->usuario_id;
    }

    public function restore(Usuario $user, Post $post): bool {
        return $user->id === $post->usuario_id;
    }

    public function forceDelete(Usuario $user, Post $post): bool {
        return $user->id === $post->usuario_id;
    }
}

?>