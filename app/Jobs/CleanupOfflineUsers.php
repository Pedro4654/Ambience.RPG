<?php
// app/Jobs/CleanupOfflineUsers.php

namespace App\Jobs;

use App\Models\Usuario;
use App\Events\UserStatusChanged;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CleanupOfflineUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Usuários inativos por mais de 5 minutos
        $offlineThreshold = Carbon::now()->subMinutes(5);
        
        $staleUsers = Usuario::where('is_online', true)
            ->where('last_seen', '<', $offlineThreshold)
            ->get();

        foreach ($staleUsers as $user) {
            $roomId = $user->current_room;
            
            // Marcar como offline
            $user->setOffline();
            
            // Broadcast mudança de status
            broadcast(new UserStatusChanged($user, 'offline', $roomId));
        }

        \Log::info('Limpeza de usuários offline concluída', [
            'users_marked_offline' => $staleUsers->count()
        ]);
    }
}
