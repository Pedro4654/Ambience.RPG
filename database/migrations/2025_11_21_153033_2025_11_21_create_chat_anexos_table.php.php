<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_anexos', function (Blueprint $table) {
            $table->id();

            // mensagem_id referencia mensagens_chat.id (bigint unsigned) -> usar unsignedBigInteger
            $table->unsignedBigInteger('mensagem_id');

            // usuario_id referencia usuarios.id (INT) -> usar integer
            $table->integer('usuario_id');

            $table->string('nome_original');
            $table->string('nome_arquivo');
            $table->string('caminho');
            $table->string('tipo_mime');
            $table->bigInteger('tamanho');
            $table->string('hash_arquivo')->nullable();
            $table->boolean('nsfw_detectado')->default(false);
            $table->json('nsfw_scores')->nullable();
            $table->timestamps();

            // índices
            $table->index('mensagem_id');

            // chaves estrangeiras
            $table->foreign('mensagem_id')
                  ->references('id')->on('mensagens_chat')
                  ->onDelete('cascade');

            $table->foreign('usuario_id')
                  ->references('id')->on('usuarios')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_anexos');
    }
};
