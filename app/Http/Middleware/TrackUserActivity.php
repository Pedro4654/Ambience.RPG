<?php
// app/Http/Middleware/TrackUserActivity.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\UserStatusChanged;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $wasOffline = !$user->is_online;
            
            // Atualizar último acesso
            $user->update([
                'is_online' => true,
                'last_seen' => now()
            ]);
            
            // Se o usuário estava offline, broadcastar mudança de status
            if ($wasOffline) {
                broadcast(new UserStatusChanged($user, 'online', $user->current_room));
            }
        }

        return $next($request);
    }
}
