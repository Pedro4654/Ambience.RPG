<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->text('motivo_desativacao')->nullable()->after('ativa');
            $table->unsignedBigInteger('desativada_por')->nullable()->after('motivo_desativacao');
            $table->timestamp('data_desativacao')->nullable()->after('desativada_por');
            
            // Foreign key para o staff que desativou
            $table->foreign('desativada_por')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->dropForeign(['desativada_por']);
            $table->dropColumn(['motivo_desativacao', 'desativada_por', 'data_desativacao']);
        });
    }
};