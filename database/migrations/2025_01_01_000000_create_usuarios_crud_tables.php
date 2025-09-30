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
        // ============ TABELA USUARIOS PRINCIPAL ============
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id();
                $table->string('username', 50)->unique();
                $table->string('nickname', 50)->nullable();
                $table->string('email', 100)->unique();
                $table->string('senha_hash');
                $table->string('avatar_url')->nullable();
                $table->text('bio')->nullable();
                $table->date('data_de_nascimento');
                $table->timestamp('data_criacao')->useCurrent();
                $table->enum('status', ['ativo', 'inativo'])->default('ativo');
                $table->enum('nivel_usuario', ['usuario', 'admin'])->default('usuario');
                $table->boolean('verificado')->default(false);
                $table->integer('pontos_reputacao')->default(0);
                $table->integer('ranking_posicao')->default(0);
                
                // ============ CAMPOS PARA RECUPERAÇÃO COM TOKEN DE 6 DÍGITOS ============
                $table->string('reset_token', 6)->nullable()->comment('Token numérico de 6 dígitos');
                $table->timestamp('reset_token_expires_at')->nullable()->comment('Expiração do token de reset');
                $table->integer('reset_attempts')->default(0)->comment('Tentativas de reset (máx 5)');
                $table->timestamp('reset_attempts_reset_at')->nullable()->comment('Reset do contador de tentativas');
                
                // Índices para performance
                $table->index('email');
                $table->index('status');
                $table->index(['reset_token', 'reset_token_expires_at']); // Para busca rápida do token
            });
        }

        // ============ ADICIONAR COLUNAS QUE PODEM ESTAR FALTANDO ============
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                // Remover campos antigos se existirem
                if (Schema::hasColumn('usuarios', 'password_reset_token')) {
                    $table->dropColumn('password_reset_token');
                }
                if (Schema::hasColumn('usuarios', 'password_reset_expires_at')) {
                    $table->dropColumn('password_reset_expires_at');
                }
                
                // Adicionar novos campos para token de 6 dígitos
                if (!Schema::hasColumn('usuarios', 'reset_token')) {
                    $table->string('reset_token', 6)->nullable()->after('ranking_posicao')->comment('Token numérico de 6 dígitos');
                }
                if (!Schema::hasColumn('usuarios', 'reset_token_expires_at')) {
                    $table->timestamp('reset_token_expires_at')->nullable()->after('reset_token')->comment('Expiração do token de reset');
                }
                if (!Schema::hasColumn('usuarios', 'reset_attempts')) {
                    $table->integer('reset_attempts')->default(0)->after('reset_token_expires_at')->comment('Tentativas de reset (máx 5)');
                }
                if (!Schema::hasColumn('usuarios', 'reset_attempts_reset_at')) {
                    $table->timestamp('reset_attempts_reset_at')->nullable()->after('reset_attempts')->comment('Reset do contador de tentativas');
                }
                
                // Adicionar avatar se não existir
                if (!Schema::hasColumn('usuarios', 'avatar_url')) {
                    $table->string('avatar_url')->nullable()->after('senha_hash');
                }
            });
        }

        // ============ TABELA SESSIONS - SÓ CRIA SE NÃO EXISTIR ============
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // ============ TABELA PASSWORD_RESET_TOKENS (REMOVER - NÃO MAIS NECESSÁRIA) ============
        if (Schema::hasTable('password_reset_tokens')) {
            Schema::dropIfExists('password_reset_tokens');
        }

        // ============ TABELA MIGRATIONS - SÓ CRIA SE NÃO EXISTIR ============
        if (!Schema::hasTable('migrations')) {
            Schema::create('migrations', function (Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->integer('batch');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover apenas as colunas que foram adicionadas
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                if (Schema::hasColumn('usuarios', 'reset_token')) {
                    $table->dropColumn('reset_token');
                }
                if (Schema::hasColumn('usuarios', 'reset_token_expires_at')) {
                    $table->dropColumn('reset_token_expires_at');
                }
                if (Schema::hasColumn('usuarios', 'reset_attempts')) {
                    $table->dropColumn('reset_attempts');
                }
                if (Schema::hasColumn('usuarios', 'reset_attempts_reset_at')) {
                    $table->dropColumn('reset_attempts_reset_at');
                }
            });
        }
        
        // Dropar tabelas criadas
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('migrations');
    }
};
