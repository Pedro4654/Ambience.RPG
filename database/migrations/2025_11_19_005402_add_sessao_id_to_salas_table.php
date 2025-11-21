<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->string('sessao_id')->nullable()->after('id')->unique();
        });
    }

    public function down()
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->dropColumn('sessao_id');
        });
    }
};