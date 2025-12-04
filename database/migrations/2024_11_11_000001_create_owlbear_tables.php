<?php
// Arquivo: database/migrations/2024_11_11_000001_create_owlbear_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabela de jogos/sessões do Owlbear
        Schema::create('owlbear_games', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->integer('sessao_id'); // INT sem unsigned (compatível com sessoes_jogo.id)
            $table->string('password')->nullable();
            $table->timestamps();
            
            // Foreign key com tipo compatível
            $table->foreign('sessao_id')
                  ->references('id')
                  ->on('sessoes_jogo')
                  ->onDelete('cascade');
            
            $table->index('sessao_id');
        });

        // Tabela de mapas/grids
        Schema::create('owlbear_maps', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->string('game_id', 36); // FK para owlbear_games
            $table->integer('owner'); // INT (compatível com usuarios.id)
            $table->string('name');
            $table->text('thumbnail')->nullable();
            $table->integer('width');
            $table->integer('height');
            $table->integer('grid_x')->default(0);
            $table->integer('grid_y')->default(0);
            $table->string('grid_type')->default('square');
            $table->json('grid')->nullable();
            $table->boolean('show_grid')->default(true);
            $table->boolean('snap_to_grid')->default(true);
            $table->json('fog')->nullable();
            $table->json('quality')->nullable();
            $table->timestamps();
            
            $table->foreign('game_id')
                  ->references('id')
                  ->on('owlbear_games')
                  ->onDelete('cascade');
            
            $table->index('game_id');
        });

        // Tabela de tokens
        Schema::create('owlbear_tokens', function (Blueprint $table) {
            $table->string('id', 36)->primary(); // UUID
            $table->string('map_id', 36); // FK para owlbear_maps
            $table->integer('owner'); // INT (compatível com usuarios.id)
            $table->string('name');
            $table->string('category')->default('character');
            $table->text('file')->nullable();
            $table->text('thumbnail')->nullable();
            $table->integer('width');
            $table->integer('height');
            $table->decimal('x', 10, 2);
            $table->decimal('y', 10, 2);
            $table->decimal('rotation', 10, 2)->default(0);
            $table->boolean('locked')->default(false);
            $table->boolean('visible')->default(true);
            $table->string('outline_color')->nullable();
            $table->json('label')->nullable();
            $table->json('statuses')->nullable();
            $table->integer('layer')->default(0);
            $table->timestamps();
            
            $table->foreign('map_id')
                  ->references('id')
                  ->on('owlbear_maps')
                  ->onDelete('cascade');
            
            $table->index('map_id');
        });

        // Tabela de estados (para sincronização em tempo real)
        Schema::create('owlbear_states', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('game_id', 36);
            $table->string('map_id', 36);
            $table->integer('user_id'); // INT
            $table->json('data');
            $table->timestamp('updated_at');
            
            $table->foreign('game_id')
                  ->references('id')
                  ->on('owlbear_games')
                  ->onDelete('cascade');
            
            $table->index(['game_id', 'user_id']);
        });

        // Tabela de assets (imagens enviadas pelos usuários)
        Schema::create('owlbear_assets', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('game_id', 36);
            $table->integer('owner'); // INT
            $table->string('name');
            $table->string('mime_type');
            $table->text('file');
            $table->text('thumbnail')->nullable();
            $table->integer('width');
            $table->integer('height');
            $table->timestamps();
            
            $table->foreign('game_id')
                  ->references('id')
                  ->on('owlbear_games')
                  ->onDelete('cascade');
            
            $table->index('game_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('owlbear_assets');
        Schema::dropIfExists('owlbear_states');
        Schema::dropIfExists('owlbear_tokens');
        Schema::dropIfExists('owlbear_maps');
        Schema::dropIfExists('owlbear_games');
    }
};