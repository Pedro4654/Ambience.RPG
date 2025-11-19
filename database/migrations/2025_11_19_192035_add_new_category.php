<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adicionar nova categoria 'recurso_ip_ban' ao ENUM
        DB::statement("ALTER TABLE tickets MODIFY COLUMN categoria ENUM('duvida', 'denuncia', 'problema_tecnico', 'sugestao', 'outro', 'recurso_ip_ban') NOT NULL");
        
        // Log da alteração
        \Log::info('Migration executada: categoria recurso_ip_ban adicionada aos tickets');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover categoria 'recurso_ip_ban'
        DB::statement("ALTER TABLE tickets MODIFY COLUMN categoria ENUM('duvida', 'denuncia', 'problema_tecnico', 'sugestao', 'outro') NOT NULL");
    }
};