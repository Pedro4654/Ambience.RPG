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
        Schema::table('salas', function (Blueprint $table) {
            // Adiciona foto de perfil DEPOIS do banner_color
            $table->string('profile_photo_url', 255)->nullable()->after('banner_color');
            
            // Cor de fallback para foto de perfil (quando não houver imagem)
            $table->string('profile_photo_color', 7)->nullable()->after('profile_photo_url'); // ex: #3b82f6
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->dropColumn(['profile_photo_url', 'profile_photo_color']);
        });
    }
};
