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
Broadcast::channel('sala.{salaId}', function (Usuario $user, int $salaId) {
    $pertence = ParticipanteSala::where('sala_id', $salaId)
        ->where('usuario_id', $user->id)
        ->where('ativo', true)
        ->exists();

    if (! $pertence) {
        return false;
    }

    return [
        'id' => (int) $user->id,
        'name' => $user->username,       // adiciona name para casar com o front
        'username' => $user->username,   // mantém username se for útil
        'avatar' => $user->avatar,
    ];
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
