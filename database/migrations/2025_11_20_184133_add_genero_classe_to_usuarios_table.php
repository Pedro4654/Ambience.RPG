<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->enum('genero', ['masculino', 'feminino'])
                  ->nullable()
                  ->after('data_de_nascimento')
                  ->comment('Gênero do usuário');
            
            $table->enum('classe_personagem', [
                'ladino', 
                'barbaro', 
                'paladino', 
                'arqueiro', 
                'bardo', 
                'mago'
            ])->nullable()
              ->after('genero')
              ->comment('Classe de personagem escolhida no cadastro');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['genero', 'classe_personagem']);
        });
    }
};