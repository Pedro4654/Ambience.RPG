<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ADICIONA COLUNAS NA TABELA tickets (somente se não existirem)
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                if (! Schema::hasColumn('tickets', 'tipo_denuncia')) {
                    $table->string('tipo_denuncia')->nullable()->after('categoria');
                }

                if (! Schema::hasColumn('tickets', 'sala_id')) {
                    // usamos INT porque salas.id é INT
                    $table->integer('sala_id')->nullable()->after('tipo_denuncia');
                }
            });

            // Verifica se já existe uma FK que referencie salas(id) na coluna sala_id
            $fkExists = DB::selectOne("
                SELECT 1 AS exists_flag
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'tickets'
                  AND COLUMN_NAME = 'sala_id'
                  AND REFERENCED_TABLE_NAME = 'salas'
                  AND REFERENCED_COLUMN_NAME = 'id'
                LIMIT 1
            ");

            if (! $fkExists) {
                try {
                    // Adiciona a constraint explicitamente via SQL. 
                    // Nomeamos a FK como tickets_sala_id_foreign (padrão Laravel).
                    DB::statement("
                        ALTER TABLE `tickets`
                        ADD CONSTRAINT `tickets_sala_id_foreign`
                        FOREIGN KEY (`sala_id`) REFERENCES `salas`(`id`)
                        ON DELETE SET NULL
                    ");
                } catch (\Exception $e) {
                    // Ignora falha (p.ex. se outra sessão criou a FK em paralelo).
                    // Se quiser logar, descomente a linha abaixo:
                    // \Log::error('Falha ao criar FK tickets.sala_id: '.$e->getMessage());
                }
            }
        }

        // CRIA A TABELA denuncia_mensagens_anexadas se não existir
        if (! Schema::hasTable('denuncia_mensagens_anexadas')) {
            Schema::create('denuncia_mensagens_anexadas', function (Blueprint $table) {
                $table->id();

                // ticket_id assume que tickets.id é bigint unsigned (id()).
                // Se no seu caso tickets.id for INT, troque por integer().
                $table->unsignedBigInteger('ticket_id');

                // mensagem_id referencia mensagens_chat.id (bigint unsigned)
                $table->unsignedBigInteger('mensagem_id');

                $table->timestamps();

                $table->index('ticket_id');
                $table->index('mensagem_id');

                // FKs declaradas aqui — se já existirem, migration lançará, mas criamos a tabela só se não existir
                $table->foreign('ticket_id')
                      ->references('id')->on('tickets')
                      ->onDelete('cascade');

                $table->foreign('mensagem_id')
                      ->references('id')->on('mensagens_chat')
                      ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        // Drop tabela de anexos se existir
        if (Schema::hasTable('denuncia_mensagens_anexadas')) {
            Schema::dropIfExists('denuncia_mensagens_anexadas');
        }

        // Remove colunas da tabela tickets (se existirem)
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                // tenta dropar FK e coluna sala_id com try/catch
                try {
                    if (Schema::hasColumn('tickets', 'sala_id')) {
                        // tenta dropar fk pelo nome padrão
                        $table->dropForeign(['sala_id']);
                    }
                } catch (\Exception $e) {
                    // ignora
                }

                if (Schema::hasColumn('tickets', 'sala_id')) {
                    $table->dropColumn('sala_id');
                }
                if (Schema::hasColumn('tickets', 'tipo_denuncia')) {
                    $table->dropColumn('tipo_denuncia');
                }
            });
        }
    }
};
