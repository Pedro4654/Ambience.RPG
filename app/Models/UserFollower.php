<?php

// ============================================================
// MODEL 6: USER FOLLOWER (Seguidores)
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UserFollower extends Model {
    protected $table = 'user_followers';

    protected $fillable = [
        'seguidor_id',
        'seguido_id'
    ];

    public $timestamps = true;
    const UPDATED_AT = null;

    public function seguidor() {
        return $this->belongsTo(Usuario::class, 'seguidor_id');
    }

    public function seguido() {
        return $this->belongsTo(Usuario::class, 'seguido_id');
    }

    // ==================== SCOPES ====================

    public function scopeSeguindo($query, $usuario_id) {
        return $query->where('seguidor_id', $usuario_id);
    }

    public function scopeSeguidores($query, $usuario_id) {
        return $query->where('seguido_id', $usuario_id);
    }
}