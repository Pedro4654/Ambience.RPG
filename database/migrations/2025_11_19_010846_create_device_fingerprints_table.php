<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Verifica o tipo da coluna 'id' na tabela 'usuarios'
        $usuariosIdType = DB::select("
            SELECT DATA_TYPE, COLUMN_TYPE 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'usuarios' 
            AND COLUMN_NAME = 'id'
        ");

        $isBigInt = false;
        $isUnsigned = false;

        if (!empty($usuariosIdType)) {
            $columnType = strtolower($usuariosIdType[0]->COLUMN_TYPE);
            $isBigInt = (stripos($columnType, 'bigint') !== false);
            $isUnsigned = (stripos($columnType, 'unsigned') !== false);
        }

        Schema::create('device_fingerprints', function (Blueprint $table) use ($isBigInt, $isUnsigned) {
            $table->id();

            // Cria a coluna de usuário com tipo e sinal compatíveis com usuarios.id
            if ($isBigInt) {
                if ($isUnsigned) {
                    $table->unsignedBigInteger('usuario_id')->index();
                } else {
                    $table->bigInteger('usuario_id')->index();
                }
            } else {
                // int
                if ($isUnsigned) {
                    $table->unsignedInteger('usuario_id')->index();
                } else {
                    $table->integer('usuario_id')->index();
                }
            }

            $table->string('fingerprint', 255)->index();
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('accept_language')->nullable();
            $table->string('accept_encoding')->nullable();
            $table->boolean('conta_criada_neste_dispositivo')->default(false);
            $table->timestamp('primeiro_acesso')->nullable();
            $table->timestamp('ultimo_acesso')->nullable();
            $table->timestamps();

            // Foreign key — agora compatível
            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuarios')
                ->onDelete('cascade');

            $table->index(['fingerprint', 'usuario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_fingerprints');
    }
};
