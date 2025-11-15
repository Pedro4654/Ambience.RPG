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
        // Tabela principal de tickets
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ticket', 20)->unique()->comment('Número único do ticket (ex: TICK-2024-001)');
            
            // Relacionamentos
            $table->integer('usuario_id');
            $table->integer('atribuido_a')->nullable();
            $table->integer('usuario_denunciado_id')->nullable();
            
            // Informações do ticket
            $table->enum('categoria', ['duvida', 'denuncia', 'problema_tecnico', 'sugestao', 'outro'])->default('duvida');
            $table->string('assunto', 255);
            $table->text('descricao');
            
            // Status e prioridade
            $table->enum('status', ['novo', 'pendente', 'em_analise', 'aguardando_resposta', 'resolvido', 'fechado', 'spam'])->default('novo');
            $table->enum('prioridade', ['baixa', 'normal', 'alta', 'urgente'])->default('normal');
            
            // Metadados
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('visivel_usuario')->default(true)->comment('Se false, apenas staff vê');
            $table->integer('visualizacoes')->default(0);
            
            // Timestamps
            $table->timestamp('data_abertura')->useCurrent();
            $table->timestamp('data_fechamento')->nullable();
            $table->timestamp('ultima_resposta_staff')->nullable();
            $table->timestamp('ultima_resposta_usuario')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['usuario_id', 'status']);
            $table->index(['atribuido_a', 'status']);
            $table->index('categoria');
            $table->index('status');
            $table->index('prioridade');
            $table->index('data_abertura');

            // Foreign keys (compatíveis com usuarios.id sendo INT assin)
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('atribuido_a')->references('id')->on('usuarios')->onDelete('set null');
            $table->foreign('usuario_denunciado_id')->references('id')->on('usuarios')->onDelete('set null');
        });

        // Respostas/comentários nos tickets
        Schema::create('ticket_respostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->integer('usuario_id');
            
            $table->text('mensagem');
            $table->boolean('interno')->default(false)->comment('Se true, apenas staff vê');
            $table->boolean('editado')->default(false);
            
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['ticket_id', 'created_at']);

            // FK para usuarios
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });

        // Anexos dos tickets
        Schema::create('ticket_anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->integer('usuario_id');
            $table->foreignId('resposta_id')->nullable()->constrained('ticket_respostas')->onDelete('cascade');
            
            $table->string('nome_original', 255);
            $table->string('nome_arquivo', 255)->unique();
            $table->string('caminho', 500);
            $table->string('tipo_mime', 100);
            $table->integer('tamanho')->comment('Tamanho em bytes');
            $table->string('hash_arquivo', 64)->comment('SHA256 do arquivo');
            
            $table->timestamps();
            
            $table->index(['ticket_id', 'created_at']);

            // FK para usuarios
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });

        // Histórico de ações nos tickets (auditoria)
        Schema::create('ticket_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->integer('usuario_id')->nullable();
            
            $table->string('acao', 100)->comment('ex: criado, atribuido, status_alterado, prioridade_alterada');
            $table->json('dados_anteriores')->nullable();
            $table->json('dados_novos')->nullable();
            $table->text('observacao')->nullable();
            
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('data_acao')->useCurrent();
            
            $table->index(['ticket_id', 'data_acao']);
            $table->index('acao');

            // FK para usuarios (nullable)
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('set null');
        });

        // Controle de rate-limit (anti-spam)
        Schema::create('ticket_rate_limits', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->string('ip_address', 45);
            $table->integer('tentativas')->default(1);
            $table->timestamp('primeira_tentativa')->useCurrent();
            $table->timestamp('ultima_tentativa')->useCurrent();
            $table->timestamp('liberado_em')->nullable();
            
            $table->unique(['usuario_id', 'ip_address']);
            $table->index('liberado_em');

            // FK para usuarios
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });

        // Notificações de tickets
        Schema::create('ticket_notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->integer('usuario_id');
            
            $table->enum('tipo', ['nova_resposta', 'status_alterado', 'ticket_atribuido', 'ticket_fechado', 'mencao'])->default('nova_resposta');
            $table->text('mensagem');
            $table->boolean('lida')->default(false);
            $table->timestamp('lida_em')->nullable();
            
            $table->timestamps();
            
            $table->index(['usuario_id', 'lida']);
            $table->index(['ticket_id', 'created_at']);

            // FK para usuarios
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });

        // Configurações do sistema de suporte
        Schema::create('suporte_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('chave', 100)->unique();
            $table->text('valor')->nullable();
            $table->string('tipo', 50)->default('string')->comment('string, int, boolean, json');
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        // Inserir configurações padrão
        DB::table('suporte_configuracoes')->insert([
            [
                'chave' => 'tickets_por_hora',
                'valor' => '3',
                'tipo' => 'int',
                'descricao' => 'Máximo de tickets que um usuário pode criar por hora',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'chave' => 'max_anexos_por_ticket',
                'valor' => '5',
                'tipo' => 'int',
                'descricao' => 'Máximo de anexos permitidos por ticket',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'chave' => 'tamanho_max_anexo_mb',
                'valor' => '10',
                'tipo' => 'int',
                'descricao' => 'Tamanho máximo de cada anexo em MB',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'chave' => 'tipos_anexos_permitidos',
                'valor' => '["jpg","jpeg","png","gif","pdf","doc","docx","txt","zip"]',
                'tipo' => 'json',
                'descricao' => 'Extensões de arquivo permitidas',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'chave' => 'tempo_bloqueio_spam_minutos',
                'valor' => '60',
                'tipo' => 'int',
                'descricao' => 'Tempo de bloqueio ao exceder o rate limit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'chave' => 'notificar_email',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descricao' => 'Enviar notificações por email',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_notificacoes');
        Schema::dropIfExists('suporte_configuracoes');
        Schema::dropIfExists('ticket_rate_limits');
        Schema::dropIfExists('ticket_historico');
        Schema::dropIfExists('ticket_anexos');
        Schema::dropIfExists('ticket_respostas');
        Schema::dropIfExists('tickets');
    }
};
