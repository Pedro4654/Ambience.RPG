<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UsuarioDigitando implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $username;
    public $salaId;
    public $isTyping; // true = começou a digitar, false = parou

    public function __construct($userId, $username, $salaId, $isTyping)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->salaId = $salaId;
        $this->isTyping = $isTyping;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('sala.' . $this->salaId);
    }

    public function broadcastAs()
    {
        return 'usuario.digitando';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->userId,
            'username' => $this->username,
            'is_typing' => $this->isTyping
        ];
    }
}