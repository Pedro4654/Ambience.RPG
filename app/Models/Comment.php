<?php

// ============================================================
// MODEL 4: COMMENT (Comentários)
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Comment extends Model {
    use SoftDeletes;

    protected $fillable = [
        'post_id',
        'usuario_id',
        'parent_id',
        'conteudo',
        'likes'
    ];

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function autor() {
        return $this->belongsTo(Usuario::class, 'usuario_id')->select([
            'id', 'username', 'avatar_url'
        ]);
    }

    public function respostas() {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with('autor')
            ->orderBy('created_at', 'asc');
    }

    public function comentario_pai() {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // ==================== SCOPES ====================

    public function scopePrincipais($query) {
        return $query->whereNull('parent_id');
    }

    public function scopeDoPrincipal($query, $comment_id) {
        return $query->where('parent_id', $comment_id);
    }
}