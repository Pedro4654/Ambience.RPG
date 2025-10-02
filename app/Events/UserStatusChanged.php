<?php
// app/Events/UserStatusChanged.php

namespace App\Events;

use App\Models\Usuario;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $status;
    public $roomId;

    public function __construct(Usuario $user, $status, $roomId = null)
    {
        $this->user = $user;
        $this->status = $status; // 'online' ou 'offline'
        $this->roomId = $roomId;
    }

    public function broadcastOn()
    {
        $channels = [];
        
        // Broadcast para a sala específica se o usuário estiver em uma
        if ($this->roomId) {
            $channels[] = new PresenceChannel('sala.' . $this->roomId);
        }
        
        // Broadcast geral para dashboard
        $channels[] = new Channel('user-status');
        
        return $channels;
    }

    public function broadcastWith()
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar,
            ],
            'status' => $this->status,
            'room_id' => $this->roomId,
            'timestamp' => now()->toISOString()
        ];
    }

    public function broadcastAs()
    {
        return 'user.status.changed';
    }
}
