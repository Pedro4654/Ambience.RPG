<?php

namespace App\Policies;

use App\Models\Usuario;

class UsuarioPolicy
{
    public function update(Usuario $user, Usuario $usuario)
    {
        return $user->id === $usuario->id || $user->nivel_usuario === 'admin';
    }

    public function delete(Usuario $user, Usuario $usuario)
    {
        return $user->id === $usuario->id || $user->nivel_usuario === 'admin';
    }
}