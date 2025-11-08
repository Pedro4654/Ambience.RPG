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
        // ==================== 1. ADICIONAR COLUNAS NA TABELA USUARIOS ====================
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                // Adicionar coluna bio se não existir
                if (!Schema::hasColumn('usuarios', 'bio')) {
                    $table->text('bio')->nullable()->after('avatar_url');
                }

                // Adicionar coluna website se não existir
                if (!Schema::hasColumn('usuarios', 'website')) {
                    $table->string('website')->nullable()->after('bio');
                }

                // Adicionar coluna privacidade_perfil se não existir
                if (!Schema::hasColumn('usuarios', 'privacidade_perfil')) {
                    $table->enum('privacidade_perfil', ['publico', 'privado'])->default('publico')->after('website');
                }

                // Adicionar coluna total_curtidas_recebidas se não existir
                if (!Schema::hasColumn('usuarios', 'total_curtidas_recebidas')) {
                    $table->integer('total_curtidas_recebidas')->default(0)->after('privacidade_perfil');
                }

                // Adicionar coluna total_seguidores se não existir
                if (!Schema::hasColumn('usuarios', 'total_seguidores')) {
                    $table->integer('total_seguidores')->default(0)->after('total_curtidas_recebidas');
                }

                // Adicionar coluna total_postagens se não existir
                if (!Schema::hasColumn('usuarios', 'total_postagens')) {
                    $table->integer('total_postagens')->default(0)->after('total_seguidores');
                }
            });
        }

        // ==================== 2. CRIAR TABELA POSTS ====================
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                // CORRIGIDO: Usar integer() sem unsigned para compatibilidade com usuarios.id
                $table->integer('usuario_id');
                $table->string('titulo');
                $table->longText('conteudo');
                $table->string('slug')->unique();
                $table->enum('tipo_conteudo', ['texto', 'imagem', 'video', 'modelo_3d', 'ficha', 'outros'])->default('texto');
                $table->integer('visualizacoes')->default(0);
                $table->timestamps();
                $table->softDeletes();

                // Chave estrangeira com tipo int (compatível com usuarios.id)
                $table->foreign('usuario_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

                $table->index('usuario_id');
                $table->index('created_at');
            });
        }

        // ==================== 3. CRIAR TABELA POST FILES ====================
        if (!Schema::hasTable('post_files')) {
            Schema::create('post_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->string('nome_arquivo');
                $table->string('caminho_arquivo');
                $table->string('tipo_mime');
                $table->integer('tamanho');
                $table->enum('tipo', ['imagem', 'video', 'modelo_3d', 'arquivo'])->default('arquivo');
                $table->integer('ordem')->default(0);
                $table->integer('downloads')->default(0);
                $table->timestamps();

                $table->foreign('post_id')
                    ->references('id')
                    ->on('posts')
                    ->onDelete('cascade');

                $table->index('post_id');
            });
        }

        // ==================== 4. CRIAR TABELA LIKES ====================
        if (!Schema::hasTable('likes')) {
            Schema::create('likes', function (Blueprint $table) {
                $table->id();
                // CORRIGIDO: Usar integer() sem unsigned
                $table->integer('usuario_id');
                $table->unsignedBigInteger('post_id');
                $table->timestamps();

                // Chave estrangeira com tipo int (compatível com usuarios.id)
                $table->foreign('usuario_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

                $table->foreign('post_id')
                    ->references('id')
                    ->on('posts')
                    ->onDelete('cascade');

                $table->unique(['usuario_id', 'post_id']);
                $table->index('post_id');
            });
        }

        // ==================== 5. CRIAR TABELA COMMENTS ====================
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                // CORRIGIDO: Usar integer() sem unsigned
                $table->integer('usuario_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->longText('conteudo');
                $table->integer('likes')->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('post_id')
                    ->references('id')
                    ->on('posts')
                    ->onDelete('cascade');

                // Chave estrangeira com tipo int (compatível com usuarios.id)
                $table->foreign('usuario_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

                $table->foreign('parent_id')
                    ->references('id')
                    ->on('comments')
                    ->onDelete('cascade');

                $table->index('post_id');
                $table->index('usuario_id');
                $table->index('parent_id');
            });
        }

        // ==================== 6. CRIAR TABELA SAVED POSTS ====================
        if (!Schema::hasTable('saved_posts')) {
            Schema::create('saved_posts', function (Blueprint $table) {
                $table->id();
                // CORRIGIDO: Usar integer() sem unsigned
                $table->integer('usuario_id');
                $table->unsignedBigInteger('post_id');
                $table->timestamps();

                // Chave estrangeira com tipo int (compatível com usuarios.id)
                $table->foreign('usuario_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

                $table->foreign('post_id')
                    ->references('id')
                    ->on('posts')
                    ->onDelete('cascade');

                $table->unique(['usuario_id', 'post_id']);
                $table->index('usuario_id');
            });
        }

        // ==================== 7. CRIAR TABELA USER FOLLOWERS ====================
        if (!Schema::hasTable('user_followers')) {
            Schema::create('user_followers', function (Blueprint $table) {
                $table->id();
                // CORRIGIDO: Usar integer() sem unsigned
                $table->integer('seguidor_id');
                $table->integer('seguido_id');
                $table->timestamps();

                // Chaves estrangeiras com tipo int (compatível com usuarios.id)
                $table->foreign('seguidor_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

                $table->foreign('seguido_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

                $table->unique(['seguidor_id', 'seguido_id']);
                $table->index('seguido_id');
            });
        }

        // ==================== 8. CRIAR TABELA GROUPS ====================
        if (!Schema::hasTable('groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->id();
                // CORRIGIDO: Usar integer() sem unsigned
                $table->integer('criador_id');
                $table->string('nome');
                $table->text('descricao')->nullable();
                $table->string('imagem_url')->nullable();
                $table->enum('privacidade', ['publico', 'privado', 'moderado'])->default('publico');
                $table->integer('membros_count')->default(1);
                $table->timestamps();
                $table->softDeletes();

                // Chave estrangeira com tipo int (compatível com usuarios.id)
                $table->foreign('criador_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

                $table->index('criador_id');
            });
        }

        // ==================== 9. CRIAR TABELA GROUP MEMBERS ====================
        if (!Schema::hasTable('group_members')) {
            Schema::create('group_members', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('group_id');
                // CORRIGIDO: Usar integer() sem unsigned
                $table->integer('usuario_id');
                $table->enum('funcao', ['membro', 'moderador', 'admin'])->default('membro');
                $table->timestamps();

                $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
                    ->onDelete('cascade');

                // Chave estrangeira com tipo int (compatível com usuarios.id)
                $table->foreign('usuario_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

                $table->unique(['group_id', 'usuario_id']);
                $table->index('usuario_id');
            });
        }

        // ==================== 10. CRIAR TABELA ACTIVITY FEED ====================
        if (!Schema::hasTable('activity_feed')) {
            Schema::create('activity_feed', function (Blueprint $table) {
                $table->id();
                // CORRIGIDO: Usar integer() sem unsigned
                $table->integer('usuario_id');
                $table->string('tipo_atividade');
                $table->string('descricao')->nullable();
                $table->string('referencia_tipo')->nullable();
                $table->unsignedBigInteger('referencia_id')->nullable();
                $table->timestamps();

                // Chave estrangeira com tipo int (compatível com usuarios.id)
                $table->foreign('usuario_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

                $table->index('usuario_id');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_feed');
        Schema::dropIfExists('group_members');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('user_followers');
        Schema::dropIfExists('saved_posts');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('post_files');
        Schema::dropIfExists('posts');

        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                if (Schema::hasColumn('usuarios', 'bio')) {
                    $table->dropColumn('bio');
                }

                if (Schema::hasColumn('usuarios', 'website')) {
                    $table->dropColumn('website');
                }

                if (Schema::hasColumn('usuarios', 'privacidade_perfil')) {
                    $table->dropColumn('privacidade_perfil');
                }

                if (Schema::hasColumn('usuarios', 'total_curtidas_recebidas')) {
                    $table->dropColumn('total_curtidas_recebidas');
                }

                if (Schema::hasColumn('usuarios', 'total_seguidores')) {
                    $table->dropColumn('total_seguidores');
                }

                if (Schema::hasColumn('usuarios', 'total_postagens')) {
                    $table->dropColumn('total_postagens');
                }
            });
        }
    }
};