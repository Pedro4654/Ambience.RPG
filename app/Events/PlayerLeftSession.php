<?php
// Arquivo: app/Events/PlayerLeftSession.php

namespace App\Events;

use App\Models\Usuario;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerLeftSession implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $sessionId;

    public function __construct(Usuario $user, $sessionId)
    {
        $this->user = $user;
        $this->sessionId = $sessionId;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('session.' . $this->sessionId);
    }

    public function broadcastWith()
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
            ],
            'message' => $this->user->username . ' saiu da sessão',
            'timestamp' => now()->toISOString()
        ];
    }

    public function broadcastAs()
    {
        return 'player.left';
    }
}