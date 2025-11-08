<?php

// ============================================================
// MODEL 3: LIKE (Curtidas)
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Like extends Model {
    protected $fillable = [
        'usuario_id',
        'post_id'
    ];

    public $timestamps = true;
    const UPDATED_AT = null; // Likes não precisam ser atualizados

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }
}