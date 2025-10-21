<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipanteSessao extends Model
{
    protected $table = 'participantes_sessao';
    public $timestamps = false;

    protected $fillable = [
        'sessao_id',
        'usuario_id',
        'ficha_personagem_id',
        'posicao_x',
        'posicao_y',
        'data_entrada',
        'ativo'
    ];

    protected $casts = [
        'data_entrada' => 'datetime',
        'ativo' => 'boolean',
        'posicao_x' => 'integer',
        'posicao_y' => 'integer'
    ];

    // ==================== RELACIONAMENTOS ====================

    public function sessao(): BelongsTo
    {
        return $this->belongsTo(SessaoJogo::class, 'sessao_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function fichaPersonagem(): BelongsTo
    {
        return $this->belongsTo(FichaPersonagem::class, 'ficha_personagem_id');
    }
}
