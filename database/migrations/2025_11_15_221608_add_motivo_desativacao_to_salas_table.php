<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona as colunas de desativação na tabela salas.
     */
    public function up()
    {
        Schema::table('salas', function (Blueprint $table) {

            // motivo_desativacao
            if (!Schema::hasColumn('salas', 'motivo_desativacao')) {
                $table->text('motivo_desativacao')->nullable()->after('ativa');
            }

            // desativada_por
            if (!Schema::hasColumn('salas', 'desativada_por')) {
                $table->unsignedBigInteger('desativada_por')->nullable()->after('motivo_desativacao');
            }

            // data_desativacao
            if (!Schema::hasColumn('salas', 'data_desativacao')) {
                $table->timestamp('data_desativacao')->nullable()->after('desativada_por');
            }
        });
    }

    /**
     * Remove as colunas caso a migration seja revertida.
     */
    public function down()
    {
        Schema::table('salas', function (Blueprint $table) {

            if (Schema::hasColumn('salas', 'data_desativacao')) {
                $table->dropColumn('data_desativacao');
            }

            if (Schema::hasColumn('salas', 'desativada_por')) {
                $table->dropColumn('desativada_por');
            }

            if (Schema::hasColumn('salas', 'motivo_desativacao')) {
                $table->dropColumn('motivo_desativacao');
            }
        });
    }
};
