<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HardDeleteExpiredAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usuarios:hard-delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove permanentemente contas marcadas para exclusão após 30 dias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando processo de hard delete de contas expiradas...');

        // Buscar contas que já passaram do prazo de 30 dias
        $contasExpiradas = Usuario::whereNotNull('account_deleted_at')
            ->whereNotNull('account_hard_delete_at')
            ->where('account_hard_delete_at', '<=', Carbon::now())
            ->get();

        if ($contasExpiradas->isEmpty()) {
            $this->info('Nenhuma conta expirada encontrada.');
            return Command::SUCCESS;
        }

        $this->info("Encontradas {$contasExpiradas->count()} contas para deletar permanentemente.");

        $deletadas = 0;

        foreach ($contasExpiradas as $usuario) {
            try {
                $this->line("Deletando conta: {$usuario->username} (ID: {$usuario->id})");

                // Deletar avatar se existir
                if ($usuario->avatar_url) {
                    $usuario->deleteOldAvatar();
                }

                // Hard delete
                $usuario->forceDelete();
                $deletadas++;

                Log::info('Hard delete automático executado', [
                    'usuario_id' => $usuario->id,
                    'username' => $usuario->username,
                    'account_deleted_at' => $usuario->account_deleted_at
                ]);

                $this->info("✓ Conta {$usuario->username} deletada permanentemente.");

            } catch (\Exception $e) {
                $this->error("✗ Erro ao deletar {$usuario->username}: {$e->getMessage()}");
                
                Log::error('Erro no hard delete automático', [
                    'usuario_id' => $usuario->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("\n✓ Processo concluído: {$deletadas} conta(s) deletada(s).");
        
        return Command::SUCCESS;
    }
}