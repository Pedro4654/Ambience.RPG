<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MensagemChatEditada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensagemId;
    public $salaId;
    public $novaMensagem;

    public function __construct($mensagemId, $salaId, $novaMensagem)
    {
        $this->mensagemId = $mensagemId;
        $this->salaId = $salaId;
        $this->novaMensagem = $novaMensagem;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('sala.' . $this->salaId);
    }

    public function broadcastAs()
    {
        return 'mensagem.editada';
    }

    public function broadcastWith()
    {
        return [
            'mensagem_id' => $this->mensagemId,
            'nova_mensagem' => $this->novaMensagem
        ];
    }
}