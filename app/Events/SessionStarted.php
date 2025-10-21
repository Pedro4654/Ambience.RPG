<?php

namespace App\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\SessaoJogo;
use App\Models\Sala;

class SessionStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sessao;

    public $sala;

    public $connection = 'sync';
    
    public function __construct(SessaoJogo $sessao, Sala $sala)
    {
        $this->sessao = $sessao;
        $this->sala = $sala;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('sala.' . $this->sala->id); // ← USAR PRESENCE CHANNEL
    }

    public function broadcastAs()
    {
        return 'session.started';
    }

    public function broadcastWith()
    {
        return [
            'sessao_id' => $this->sessao->id,
            'sala_id' => $this->sala->id,
            'nome_sessao' => $this->sessao->nome_sessao,
            'mestre_id' => $this->sessao->mestre_id,
            'redirect_to' => route('sessoes.show', ['id' => $this->sessao->id])
        ];
    }
}
