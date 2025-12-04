<?php
// Arquivo: app/Events/PlayerJoinedSession.php

namespace App\Events;

use App\Models\Usuario;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerJoinedSession implements ShouldBroadcast
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
                'nickname' => $this->user->nickname,
                'avatar_url' => $this->user->avatar_url,
            ],
            'message' => $this->user->username . ' entrou na sessão',
            'timestamp' => now()->toISOString()
        ];
    }

    public function broadcastAs()
    {
        return 'player.joined';
    }
}