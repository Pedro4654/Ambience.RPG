<?php

namespace App\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\SessaoJogo;

class UserJoinedSession implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sessao;

    public $usuarioId;

    public $connection = 'sync';
    
    public function __construct(SessaoJogo $sessao, $usuarioId)
    {
        $this->sessao = $sessao;
        $this->usuarioId = $usuarioId;
    }

    public function broadcastOn()
    {
        return new Channel('sala.' . $this->sessao->sala_id);
    }

    public function broadcastAs()
    {
        return 'user.joined.session';
    }

    public function broadcastWith()
    {
        // Buscar dados do usuário
        $usuario = \App\Models\Usuario::find($this->usuarioId);
        
        return [
            'sessao_id' => $this->sessao->id,
            'usuario_id' => $this->usuarioId,
            'username' => $usuario ? $usuario->username : 'Unknown',
            'sala_id' => $this->sessao->sala_id
        ];
    }
}
