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
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment
            $table->integer('usuario_id')->comment('Usuário que vai receber a notificação');
            $table->integer('remetente_id')->nullable()->comment('Usuário que causou a notificação');
            $table->string('tipo', 50)->comment('Tipo: follow, like, comment, mention, sala_convite, etc');
            $table->text('mensagem')->comment('Mensagem da notificação');
            $table->string('icone', 50)->nullable()->comment('Ícone da notificação');
            $table->string('cor', 20)->default('blue')->comment('Cor: blue, green, red, yellow');
            $table->string('link', 255)->nullable()->comment('Link de destino ao clicar');
            $table->json('dados')->nullable()->comment('Dados extras (post_id, sala_id, etc)');
            $table->boolean('lida')->default(false)->comment('Se foi lida');
            $table->timestamp('lida_em')->nullable()->comment('Quando foi lida');
            $table->timestamps();

            // Indexes
            $table->index('usuario_id');
            $table->index(['usuario_id', 'lida']);
            $table->index('tipo');
            $table->index('created_at');

            // Foreign keys
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('remetente_id')->references('id')->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notificacoes', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropForeign(['remetente_id']);
        });

        Schema::dropIfExists('notificacoes');
    }
};
