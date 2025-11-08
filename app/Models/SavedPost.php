<?php

// ============================================================
// MODEL 5: SAVED POST (Postagens Salvas)
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class SavedPost extends Model {
    protected $table = 'saved_posts';
    protected $fillable = [
        'usuario_id',
        'post_id'
    ];

    public $timestamps = true;
    const UPDATED_AT = null;

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }
}