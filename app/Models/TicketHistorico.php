<?php

// ========== TicketHistorico.php ==========

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHistorico extends Model
{
    use HasFactory;

    protected $table = 'ticket_historico';

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'usuario_id',
        'acao',
        'dados_anteriores',
        'dados_novos',
        'observacao',
        'ip_address',
        'data_acao'
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
        'data_acao' => 'datetime'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Obter descrição legível da ação
     */
    public function getDescricaoAcao()
    {
        $descricoes = [
            'criado' => 'Ticket criado',
            'atribuido' => 'Ticket atribuído',
            'status_alterado' => 'Status alterado',
            'prioridade_alterada' => 'Prioridade alterada',
            'resposta_adicionada' => 'Nova resposta adicionada',
            'anexo_adicionado' => 'Anexo adicionado',
            'fechado' => 'Ticket fechado',
            'reaberto' => 'Ticket reaberto'
        ];

        return $descricoes[$this->acao] ?? $this->acao;
    }
}