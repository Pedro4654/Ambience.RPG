<?php

// ============================================================
// MODEL 2: POST FILE (Arquivos das Postagens)
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PostFile extends Model {
    protected $table = 'post_files';

    protected $fillable = [
        'post_id',
        'nome_arquivo',
        'caminho_arquivo',
        'tipo_mime',
        'tamanho',
        'tipo',
        'ordem',
        'downloads'
    ];

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function getUrlAttribute() {
        return asset('storage/' . $this->caminho_arquivo);
    }

    public function getTamanhoFormatadoAttribute() {
        $bytes = $this->tamanho;
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
