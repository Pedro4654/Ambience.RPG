<?php

// ========== TicketNotificacao.php ==========

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketNotificacao extends Model
{
    use HasFactory;

    protected $table = 'ticket_notificacoes';

    protected $fillable = [
        'ticket_id',
        'usuario_id',
        'tipo',
        'mensagem',
        'lida',
        'lida_em'
    ];

    protected $casts = [
        'lida' => 'boolean',
        'lida_em' => 'datetime'
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
     * Marcar como lida
     */
    public function marcarComoLida()
    {
        $this->update([
            'lida' => true,
            'lida_em' => now()
        ]);
    }

    /**
     * Scope para notificações não lidas
     */
    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }
}