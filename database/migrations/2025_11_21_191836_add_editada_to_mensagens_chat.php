<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mensagens_chat', function (Blueprint $table) {
            // Verificar se a coluna já existe antes de adicionar
            if (!Schema::hasColumn('mensagens_chat', 'editada')) {
                $table->boolean('editada')->default(false)->after('mensagem_original');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mensagens_chat', function (Blueprint $table) {
            if (Schema::hasColumn('mensagens_chat', 'editada')) {
                $table->dropColumn('editada');
            }
        });
    }
};