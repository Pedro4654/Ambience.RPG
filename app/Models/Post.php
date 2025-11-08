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
        'visualizacoes',
        'total_curtidas',
        'total_comentarios'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ============================================================
    // RELATIONSHIPS - COMUNIDADE
    // ============================================================
    
    /**
     * Autor do post
     */
    public function autor() {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
    
    /**
     * Arquivos do post
     */
    public function arquivos() {
        return $this->hasMany(PostFile::class, 'post_id', 'id')
            ->orderBy('ordem', 'asc');
    }
    
    /**
     * Curtidas do post
     */
    public function curtidas() {
        return $this->hasMany(Like::class, 'post_id', 'id');
    }
    
    /**
     * Comentários do post
     */
    public function comentarios() {
        return $this->hasMany(Comment::class, 'post_id', 'id')
            ->with('autor');
    }
    
    /**
     * Posts salvos - Quem salvou este post
     */
    public function salvos() {
        return $this->hasMany(SavedPost::class, 'post_id', 'id')
            ->with('usuario');
    }
    
    /**
     * Relação many-to-many com usuários que salvaram
     */
    public function salvos_por() {
        return $this->belongsToMany(
            Usuario::class,
            'saved_posts',
            'post_id',
            'usuario_id'
        )->withTimestamps();
    }

    // ============================================================
    // MÉTODOS DE VALIDAÇÃO E HELPER
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
    
    /**
     * Incrementa visualizações
     */
    public function incrementarVisualizacoes() {
        $this->increment('visualizacoes');
    }

    // ============================================================
    // SCOPES
    // ============================================================
    
    /**
     * Filtrar por tipo
     */
    public function scopeDoTipo($query, $tipo) {
        return $query->where('tipo_conteudo', $tipo);
    }
    
    /**
     * Filtrar por usuário
     */
    public function scopeDoUsuario($query, $usuario_id) {
        return $query->where('usuario_id', $usuario_id);
    }
    
    /**
     * Mais populares primeiro
     */
    public function scopePopulares($query) {
        return $query->orderBy('total_curtidas', 'desc');
    }
    
    /**
     * Mais novos primeiro
     */
    public function scopeNovos($query) {
        return $query->orderBy('created_at', 'desc');
    }
    
    /**
     * Mais visualizados
     */
    public function scopeMaisVistos($query) {
        return $query->orderBy('visualizacoes', 'desc');
    }

    // ============================================================
    // MUTATORS & ACCESSORS
    // ============================================================
    
    /**
     * Gera slug automaticamente
     */
    protected static function boot() {
        parent::boot();
        
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->titulo) . '-' . uniqid();
            }
        });
    }
    
    /**
     * Retorna URL do post
     */
    public function getUrlAttribute() {
        return route('comunidade.post.show', $this->slug);
    }
    
    /**
     * Retorna data formatada
     */
    public function getDataFormatadaAttribute() {
        return $this->created_at->format('d/m/Y H:i');
    }
}