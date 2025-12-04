<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sessao extends Model
{
    protected $table = 'sessoes_jogo';
    
    protected $fillable = [
        'sala_id',
        'grid_id',
        'nome_sessao',
        'mestre_id',
        'data_inicio',
        'data_fim',
        'status',
        'configuracoes',
        'backup_grid',
    ];

    protected $casts = [
        'configuracoes' => 'array',
        'backup_grid' => 'array',
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
    ];

    public function mestre()
    {
        return $this->belongsTo(User::class, 'mestre_id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }

    public function grid()
    {
        return $this->belongsTo(Grid::class, 'grid_id');
    }

    public function owlbearGame()
    {
        return $this->hasOne(OwlbearGame::class, 'sessao_id');
    }
}
