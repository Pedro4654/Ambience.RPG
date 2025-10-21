<?php

namespace App\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\SessaoJogo;

class SessionEnd implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $connection = 'sync'; // Dispara imediatamente
    
    public $sessao;
    
    public function __construct(SessaoJogo $sessao)
    {
        $this->sessao = $sessao;
    }

    public function broadcastOn()
    {
        // ========== CORRIGIR AQUI: Usar PresenceChannel ==========
        return new PresenceChannel('sala.' . $this->sessao->sala_id);
        // ========================================================
    }

    public function broadcastAs()
    {
        return 'session.end';
    }

    public function broadcastWith()
    {
        return [
            'sessao_id' => $this->sessao->id,
            'sala_id' => $this->sessao->sala_id,
            'redirect_to' => route('salas.show', ['id' => $this->sessao->sala_id])
        ];
    }
}
