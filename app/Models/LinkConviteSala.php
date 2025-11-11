<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Model para links de convite genéricos de sala (estilo Discord)
 */
class LinkConviteSala extends Model
{
    use HasFactory;

    protected $table = 'links_convite_sala';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'sala_id',
        'criador_id',
        'codigo',
        'max_usos',
        'usos_atual',
        'data_criacao',
        'data_expiracao',
        'ativo'
    ];

    protected $casts = [
        'data_criacao' => 'datetime',
        'data_expiracao' => 'datetime',
        'ativo' => 'boolean',
        'max_usos' => 'integer',
        'usos_atual' => 'integer'
    ];

    // ==================== RELACIONAMENTOS ====================

    public function sala(): BelongsTo
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }

    public function criador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'criador_id');
    }

    // ==================== SCOPES ====================

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeValidos($query)
    {
        return $query->where('ativo', true)
            ->where(function ($q) {
                $q->whereNull('data_expiracao')
                  ->orWhere('data_expiracao', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('max_usos')
                  ->orWhereRaw('usos_atual < max_usos');
            });
    }

    public function scopeDaSala($query, $salaId)
    {
        return $query->where('sala_id', $salaId);
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Verificar se o link ainda é válido
     */
    public function estaValido(): bool
    {
        if (!$this->ativo) {
            return false;
        }

        // Verificar expiração por data
        if ($this->data_expiracao && Carbon::parse($this->data_expiracao)->isPast()) {
            return false;
        }

        // Verificar limite de usos
        if ($this->max_usos !== null && $this->usos_atual >= $this->max_usos) {
            return false;
        }

        return true;
    }

    /**
     * Incrementar contador de usos
     */
    public function incrementarUso(): bool
    {
        if (!$this->estaValido()) {
            return false;
        }

        $this->usos_atual++;
        
        // Se atingiu o máximo, desativar
        if ($this->max_usos !== null && $this->usos_atual >= $this->max_usos) {
            $this->ativo = false;
        }

        return $this->save();
    }

    /**
     * Revogar link
     */
    public function revogar(): bool
    {
        $this->ativo = false;
        return $this->save();
    }

    /**
     * Verificar se expirou
     */
    public function expirou(): bool
    {
        return $this->data_expiracao && Carbon::parse($this->data_expiracao)->isPast();
    }

    /**
     * Obter link completo
     */
    public function getLinkCompleto(): string
    {
        return url("/convite/{$this->codigo}");
    }

    /**
     * Tempo restante até expiração
     */
    public function getTempoRestante(): ?string
    {
        if (!$this->data_expiracao) {
            return 'Nunca expira';
        }

        $expiracao = Carbon::parse($this->data_expiracao);
        
        if ($expiracao->isPast()) {
            return 'Expirado';
        }

        return $expiracao->diffForHumans();
    }

    /**
     * Usos restantes
     */
    public function getUsosRestantes(): ?int
    {
        if ($this->max_usos === null) {
            return null; // Ilimitado
        }

        return max(0, $this->max_usos - $this->usos_atual);
    }

    // ==================== MÉTODOS ESTÁTICOS ====================

    /**
     * Gerar código único
     */
    public static function gerarCodigoUnico(): string
    {
        do {
            // Gera código alfanumérico de 10 caracteres
            $codigo = Str::random(10);
        } while (static::where('codigo', $codigo)->exists());

        return $codigo;
    }

    /**
     * Buscar por código
     */
    public static function buscarPorCodigo($codigo)
    {
        return static::where('codigo', $codigo)->first();
    }

    /**
     * Buscar link válido por código
     */
    public static function buscarValidoPorCodigo($codigo)
    {
        return static::where('codigo', $codigo)->validos()->first();
    }

    /**
     * Limpar links expirados
     */
    public static function limparExpirados(): int
    {
        $expirados = static::where('ativo', true)
            ->where('data_expiracao', '<=', now())
            ->get();

        $contador = 0;
        foreach ($expirados as $link) {
            if ($link->revogar()) {
                $contador++;
            }
        }

        return $contador;
    }

    /**
     * Limpar links com usos esgotados
     */
    public static function limparUsosEsgotados(): int
    {
        $esgotados = static::where('ativo', true)
            ->whereNotNull('max_usos')
            ->whereRaw('usos_atual >= max_usos')
            ->get();

        $contador = 0;
        foreach ($esgotados as $link) {
            if ($link->revogar()) {
                $contador++;
            }
        }

        return $contador;
    }
}