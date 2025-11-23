<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mensagens_chat', function (Blueprint $table) {
            // PK da própria tabela (bigint unsigned) — ok manter
            $table->id();

            // sala_id -> INT (compatível com salas.id que é INT)
            $table->integer('sala_id');

            // usuario_id -> INT (compatível com usuarios.id que é INT)
            $table->integer('usuario_id');

            $table->text('mensagem');
            $table->text('mensagem_original')->nullable(); // Mensagem antes da censura
            $table->boolean('censurada')->default(false);
            $table->json('flags_detectadas')->nullable(); // ["swear", "sexual"]
            $table->string('motivo_censura')->nullable(); // "age_restriction", "content_policy"
            $table->boolean('editada')->default(false);
            $table->timestamp('editada_em')->nullable();
            $table->boolean('deletada')->default(false);
            $table->timestamp('deletada_em')->nullable();

            // deletada_por -> INT nullable (referencia usuarios.id)
            $table->integer('deletada_por')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            // índices úteis
            $table->index(['sala_id', 'created_at']);
            $table->index(['usuario_id', 'created_at']);

            // chaves estrangeiras (declaradas explicitamente para garantir tipos corretos)
            $table->foreign('sala_id')
                  ->references('id')->on('salas')
                  ->onDelete('cascade');

            $table->foreign('usuario_id')
                  ->references('id')->on('usuarios')
                  ->onDelete('cascade');

            // deletada_por é opcional — sem cascade para evitar efeitos colaterais
            $table->foreign('deletada_por')
                  ->references('id')->on('usuarios')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mensagens_chat');
    }
};
