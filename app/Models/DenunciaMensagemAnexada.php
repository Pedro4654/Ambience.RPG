<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DenunciaMensagemAnexada extends Model
{
    use HasFactory;

    protected $table = 'denuncia_mensagens_anexadas';

    protected $fillable = [
        'ticket_id',
        'mensagem_id'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function mensagem()
    {
        return $this->belongsTo(MensagemChat::class, 'mensagem_id');
    }
}