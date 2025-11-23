<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MensagemChatDeletada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensagemId;
    public $salaId;

    public function __construct($mensagemId, $salaId)
    {
        $this->mensagemId = $mensagemId;
        $this->salaId = $salaId;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('sala.' . $this->salaId);
    }

    public function broadcastAs()
    {
        return 'mensagem.deletada';
    }

    public function broadcastWith()
    {
        return [
            'mensagem_id' => $this->mensagemId
        ];
    }
}