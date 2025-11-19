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
        Schema::table('usuarios', function (Blueprint $table) {
            // ============ WARNING ============
            $table->boolean('warning_ativo')->default(false)->after('nivel_usuario');
            $table->text('warning_motivo')->nullable()->after('warning_ativo');
            $table->timestamp('warning_data')->nullable()->after('warning_motivo');
            $table->integer('warning_aplicado_por')->nullable()->after('warning_data');
            
            // ============ BAN TEMPORÁRIO/PERMANENTE ============
            $table->enum('ban_tipo', ['temporario', 'permanente'])->nullable()->after('warning_aplicado_por');
            $table->text('ban_motivo')->nullable()->after('ban_tipo');
            $table->timestamp('ban_inicio')->nullable()->after('ban_motivo');
            $table->timestamp('ban_fim')->nullable()->after('ban_inicio');
            $table->integer('ban_aplicado_por')->nullable()->after('ban_fim');
            
            // ============ IP BAN ============
            $table->boolean('ip_ban_ativo')->default(false)->after('ban_aplicado_por');
            $table->text('ip_ban_fingerprint')->nullable()->after('ip_ban_ativo');
            $table->text('ip_ban_motivo')->nullable()->after('ip_ban_fingerprint');
            $table->timestamp('ip_ban_data')->nullable()->after('ip_ban_motivo');
            $table->integer('ip_ban_aplicado_por')->nullable()->after('ip_ban_data');
            
            // ============ ACCOUNT DELETED ============
            $table->timestamp('account_deleted_at')->nullable()->after('ip_ban_aplicado_por');
            $table->text('account_deleted_motivo')->nullable()->after('account_deleted_at');
            $table->integer('account_deleted_por')->nullable()->after('account_deleted_motivo');
            $table->timestamp('account_hard_delete_at')->nullable()->after('account_deleted_por');
            
            // Índices para performance
            $table->index('warning_ativo');
            $table->index('ban_tipo');
            $table->index('ip_ban_ativo');
            $table->index('account_deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn([
                'warning_ativo',
                'warning_motivo',
                'warning_data',
                'warning_aplicado_por',
                'ban_tipo',
                'ban_motivo',
                'ban_inicio',
                'ban_fim',
                'ban_aplicado_por',
                'ip_ban_ativo',
                'ip_ban_fingerprint',
                'ip_ban_motivo',
                'ip_ban_data',
                'ip_ban_aplicado_por',
                'account_deleted_at',
                'account_deleted_motivo',
                'account_deleted_por',
                'account_hard_delete_at'
            ]);
        });
    }
};