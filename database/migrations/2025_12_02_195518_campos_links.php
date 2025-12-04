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
        Schema::table('usuarios', function (Blueprint $table) {
            // Adicionar campos sociais se não existirem
            if (!Schema::hasColumn('usuarios', 'discord_url')) {
                $table->string('discord_url', 255)->nullable()->after('website');
            }
            if (!Schema::hasColumn('usuarios', 'youtube_url')) {
                $table->string('youtube_url', 255)->nullable()->after('discord_url');
            }
            if (!Schema::hasColumn('usuarios', 'twitch_url')) {
                $table->string('twitch_url', 255)->nullable()->after('youtube_url');
            }
            if (!Schema::hasColumn('usuarios', 'privacidade_perfil')) {
                $table->enum('privacidade_perfil', ['publico', 'privado'])->default('publico')->after('twitch_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['discord_url', 'youtube_url', 'twitch_url', 'privacidade_perfil']);
        });
    }
};