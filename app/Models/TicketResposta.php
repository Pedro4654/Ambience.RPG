<?php

// ========== TicketResposta.php ==========

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketResposta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ticket_respostas';

    protected $fillable = [
        'ticket_id',
        'usuario_id',
        'mensagem',
        'interno',
        'editado',
        'ip_address'
    ];

    protected $casts = [
        'interno' => 'boolean',
        'editado' => 'boolean'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function anexos()
    {
        return $this->hasMany(TicketAnexo::class, 'resposta_id');
    }

    /**
     * Verificar se é resposta interna (apenas staff)
     */
    public function ehInterna()
    {
        return $this->interno === true;
    }

    /**
     * Verificar se foi editada
     */
    public function foiEditada()
    {
        return $this->editado === true;
    }
}