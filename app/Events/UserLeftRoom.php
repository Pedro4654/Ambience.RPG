<?php
// app/Events/UserLeftRoom.php

namespace App\Events;

use App\Models\Usuario;
use App\Models\Sala;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLeftRoom implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $sala;

    public function __construct(Usuario $user, Sala $sala)
    {
        $this->user = $user;
        $this->sala = $sala;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('sala.' . $this->sala->id);
    }

    public function broadcastWith()
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
            ],
            'message' => $this->user->username . ' saiu da sala',
            'timestamp' => now()->toISOString()
        ];
    }

    public function broadcastAs()
    {
        return 'user.left';
    }
}
