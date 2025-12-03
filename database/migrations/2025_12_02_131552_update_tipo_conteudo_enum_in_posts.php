<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE posts 
            MODIFY tipo_conteudo 
            ENUM(
                'texto',
                'imagem',
                'video',
                'ficha',
                'ficha_rpg',
                'link',
                'outro'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE posts 
            MODIFY tipo_conteudo 
            ENUM(
                'texto',
                'imagem',
                'video',
                'ficha',
                'link',
                'outro'
            ) NOT NULL
        ");
    }
};
