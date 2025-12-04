<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * MIGRATION: Tabela fichas_rpg
 * Armazena dados de fichas de personagem RPG vinculadas a posts
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fichas_rpg', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento com post
            $table->foreignId('post_id')
                  ->constrained('posts')
                  ->onDelete('cascade');
            
            // Informações básicas do personagem
            $table->string('nome', 100);
            $table->integer('nivel')->default(1);
            $table->string('raca', 50)->nullable();
            $table->string('classe', 50)->nullable();
            $table->string('foto_url')->nullable();
            
            // Atributos principais (1-20)
            $table->integer('forca')->default(10);
            $table->integer('agilidade')->default(10);
            $table->integer('vigor')->default(10);
            $table->integer('inteligencia')->default(10);
            $table->integer('sabedoria')->default(10);
            $table->integer('carisma')->default(10);
            
            // Informações adicionais
            $table->text('habilidades')->nullable();
            $table->text('historico')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index('post_id');
            $table->index('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichas_rpg');
    }
};