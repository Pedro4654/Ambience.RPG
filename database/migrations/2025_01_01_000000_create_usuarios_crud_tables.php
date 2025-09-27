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
        // ============ TABELA USUARIOS - SÓ CRIA SE NÃO EXISTIR ============
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
                $table->string('password_reset_token')->nullable();
                $table->timestamp('password_reset_expires_at')->nullable();
                
                // Índices para performance
                $table->index('email');
                $table->index('status');
            });
        }

        // ============ ADICIONAR COLUNAS QUE PODEM ESTAR FALTANDO ============
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                // Só adiciona se não existir
                if (!Schema::hasColumn('usuarios', 'password_reset_token')) {
                    $table->string('password_reset_token')->nullable()->after('ranking_posicao');
                }
                if (!Schema::hasColumn('usuarios', 'password_reset_expires_at')) {
                    $table->timestamp('password_reset_expires_at')->nullable()->after('password_reset_token');
                }
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

        // ============ TABELA PASSWORD_RESET_TOKENS - SÓ CRIA SE NÃO EXISTIR ============
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
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
        // Apenas dropar se foram criadas por esta migration
        Schema::dropIfExists('migrations');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        
        // Remover apenas as colunas que foram adicionadas
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                if (Schema::hasColumn('usuarios', 'password_reset_token')) {
                    $table->dropColumn('password_reset_token');
                }
                if (Schema::hasColumn('usuarios', 'password_reset_expires_at')) {
                    $table->dropColumn('password_reset_expires_at');
                }
            });
        }
    }
};
