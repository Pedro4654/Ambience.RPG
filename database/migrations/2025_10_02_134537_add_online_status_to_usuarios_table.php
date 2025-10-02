<?php
// database/migrations/xxxx_add_online_status_to_usuarios_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen')->nullable();
            $table->string('current_room')->nullable(); // Sala atual do usuário
        });
    }

    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'last_seen', 'current_room']);
        });
    }
};
