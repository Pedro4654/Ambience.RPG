<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Usuario;
use App\Models\ParticipanteSala;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal de presença para salas específicas
Broadcast::channel('sala.{id}', function (Usuario $user, $id) {
    // Verificar se o usuário participa da sala
    $participante = ParticipanteSala::where('sala_id', $id)
        ->where('usuario_id', $user->id)
        ->where('ativo', true)
        ->first();
    
    if ($participante) {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'avatar' => $user->avatar,
            'papel' => $participante->papel,
            'data_entrada' => $participante->data_entrada,
            'is_online' => true
        ];
    }
    
    return false;
});

// Canal geral para status de usuários
Broadcast::channel('user-status', function (Usuario $user) {
    return [
        'id' => $user->id,
        'username' => $user->username,
        'is_online' => $user->is_online
    ];
});

// Canal privado para notificações do usuário
Broadcast::channel('user.{id}', function (Usuario $user, $id) {
    return (int) $user->id === (int) $id;
});
