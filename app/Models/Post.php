<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Post extends Model {
    
    use HasFactory;
    
    protected $table = 'posts';
    
    protected $fillable = [
        'usuario_id',
        'titulo',
        'conteudo',
        'tipo_conteudo',
        'slug',
        'visualizacoes'
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    
    public function autor() {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
    
    public function arquivos() {
        return $this->hasMany(PostFile::class, 'post_id', 'id')
            ->orderBy('ordem', 'asc');
    }
    
    public function curtidas() {
        return $this->hasMany(Like::class, 'post_id', 'id');
    }
    
    public function comentarios() {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
    
    public function salvos() {
        return $this->hasMany(SavedPost::class, 'post_id', 'id');
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================
    
    /**
     * Verifica se post foi curtido por um usuário
     */
    public function curtido_por_usuario($usuario_id) {
        return $this->curtidas()
            ->where('usuario_id', $usuario_id)
            ->exists();
    }
    
    /**
     * Verifica se post foi salvo por um usuário
     */
    public function salvo_por_usuario($usuario_id) {
        return $this->salvos()
            ->where('usuario_id', $usuario_id)
            ->exists();
    }

    // ============================================================
    // BOOT - AUTO-SLUG
    // ============================================================
    
    protected static function boot() {
        parent::boot();
        
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->titulo) . '-' . uniqid();
            }
        });
    }
}