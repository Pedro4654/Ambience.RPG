<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\CleanupOfflineUsers;

class Kernel extends ConsoleKernel
{
    /**
     * Define os comandos agendados do aplicativo.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Agendamento para execução periódica do job que limpa usuários offline
        $schedule->job(new CleanupOfflineUsers())
                 ->everyTwoMinutes()
                 ->withoutOverlapping()
                 ->runInBackground();
    }

    /**
     * Registra os comandos Artisan fornecidos pelo aplicativo.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
