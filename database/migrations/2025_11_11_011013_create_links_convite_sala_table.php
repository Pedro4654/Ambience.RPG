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
        Schema::create('links_convite_sala', function (Blueprint $table) {
            // ID como INT AUTO_INCREMENT (signed, para combinar com o dump existente)
            $table->integer('id', true);
            
            // Usar integer() (signed) para compatibilidade com as tabelas existentes
            $table->integer('sala_id');
            $table->integer('criador_id');
            
            $table->string('codigo', 16)->unique(); // Código único do link (ex: AbC123XyZ)
            $table->integer('max_usos')->nullable(); // Null = ilimitado
            $table->integer('usos_atual')->default(0);
            $table->timestamp('data_criacao')->useCurrent();
            $table->timestamp('data_expiracao')->nullable(); // Null = nunca expira
            $table->boolean('ativo')->default(true);
            
            // Foreign keys
            $table->foreign('sala_id')
                ->references('id')
                ->on('salas')
                ->onDelete('cascade');
                
            $table->foreign('criador_id')
                ->references('id')
                ->on('usuarios')
                ->onDelete('cascade');
            
            // Índices
            $table->index('codigo');
            $table->index(['sala_id', 'ativo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links_convite_sala');
    }
};
