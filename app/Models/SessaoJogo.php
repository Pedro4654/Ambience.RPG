<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class SessaoJogo extends Model
{
    protected $table = 'sessoes_jogo';
    public $timestamps = false;

    protected $fillable = [
        'sala_id',
        'grid_id',
        'nome_sessao',
        'mestre_id',
        'data_inicio',
        'data_fim',
        'status',
        'configuracoes',
        'backup_grid'
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'configuracoes' => 'array',
        'backup_grid' => 'array'
    ];

    // ==================== RELACIONAMENTOS ====================

    /**
     * Relacionamento: Sessão pertence a uma sala
     */
    public function sala(): BelongsTo
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }

    /**
     * Relacionamento: Sessão tem um mestre
     */
    public function mestre(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'mestre_id');
    }

    /**
     * Relacionamento: Sessão tem muitos participantes
     */
    public function participantes(): HasMany
    {
        return $this->hasMany(ParticipanteSessao::class, 'sessao_id')
            ->where('ativo', true);
    }

    /**
     * Relacionamento: Sessão tem muitos objetos no grid
     */
    public function objetosGrid(): HasMany
    {
        return $this->hasMany(ObjetoGrid::class, 'sessao_id');
    }

    /**
     * Relacionamento: Sessão tem muitas mensagens de chat
     */
    public function mensagensChat(): HasMany
    {
        return $this->hasMany(MensagemChat::class, 'sala_id', 'sala_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope: Apenas sessões ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('status', 'ativa');
    }

    /**
     * Scope: Sessões de uma sala específica
     */
    public function scopeDaSala($query, $salaId)
    {
        return $query->where('sala_id', $salaId);
    }

    /**
     * Scope: Sessões mestradas por um usuário
     */
    public function scopeMestradasPor($query, $userId)
    {
        return $query->where('mestre_id', $userId);
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Verificar se a sessão está ativa
     */
    public function estaAtiva(): bool
    {
        return $this->status === 'ativa';
    }

    /**
     * Verificar se usuário é o mestre da sessão
     */
    public function usuarioEMestre($userId): bool
    {
        return $this->mestre_id == $userId;
    }

    /**
     * Obter duração da sessão em minutos
     */
    public function getDuracaoMinutos(): ?int
    {
        if (!$this->data_inicio) {
            return null;
        }

        $fim = $this->data_fim ?? now();
        return $this->data_inicio->diffInMinutes($fim);
    }

    /**
     * Finalizar sessão
     */
    public function finalizar(): bool
    {
        $this->status = 'finalizada';
        $this->data_fim = now();
        return $this->save();
    }

    /**
     * Pausar sessão
     */
    public function pausar(): bool
    {
        if ($this->status !== 'ativa') {
            return false;
        }
        
        $this->status = 'pausada';
        return $this->save();
    }

    /**
     * Retomar sessão pausada
     */
    public function retomar(): bool
    {
        if ($this->status !== 'pausada') {
            return false;
        }
        
        $this->status = 'ativa';
        return $this->save();
    }

    /**
     * Contar participantes ativos
     */
    public function contarParticipantes(): int
    {
        return $this->participantes()->count();
    }
}
