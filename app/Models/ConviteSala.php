<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Model para a tabela 'convites_sala'
 * Representa convites enviados para usuários entrarem em salas
 */
class ConviteSala extends Model
{
    use HasFactory;

    protected $table = 'convites_sala';
    protected $primaryKey = 'id';
    public $timestamps = false; // Usando data_criacao customizada

    /**
     * Campos que podem ser preenchidos via mass assignment
     */
    protected $fillable = [
        'sala_id',
        'remetente_id',
        'destinatario_id',
        'token',
        'status',
        'data_criacao',
        'data_expiracao'
    ];

    /**
     * Campos que devem ser convertidos para tipos específicos
     */
    protected $casts = [
        'data_criacao' => 'datetime',
        'data_expiracao' => 'datetime'
    ];

    /**
     * Status possíveis para convites
     */
    const STATUS = [
        'pendente' => 'pendente',
        'aceito' => 'aceito', 
        'recusado' => 'recusado',
        'expirado' => 'expirado'
    ];

    // ==================== RELACIONAMENTOS ====================

    /**
     * Relacionamento: Convite pertence a uma sala
     */
    public function sala(): BelongsTo
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }

    /**
     * Relacionamento: Convite pertence a um remetente
     */
    public function remetente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'remetente_id');
    }

    /**
     * Relacionamento: Convite pertence a um destinatário
     */
    public function destinatario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'destinatario_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope: Apenas convites pendentes
     */
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    /**
     * Scope: Apenas convites aceitos
     */
    public function scopeAceitos($query)
    {
        return $query->where('status', 'aceito');
    }

    /**
     * Scope: Apenas convites não expirados
     */
    public function scopeValidos($query)
    {
        return $query->where('data_expiracao', '>', now());
    }

    /**
     * Scope: Convites de uma sala específica
     */
    public function scopeDaSala($query, $salaId)
    {
        return $query->where('sala_id', $salaId);
    }

    /**
     * Scope: Convites para um usuário específico
     */
    public function scopeParaUsuario($query, $userId)
    {
        return $query->where('destinatario_id', $userId);
    }

    /**
     * Scope: Convites enviados por um usuário
     */
    public function scopeEnviadosPor($query, $userId)
    {
        return $query->where('remetente_id', $userId);
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Verificar se o convite ainda é válido
     */
    public function estaValido(): bool
    {
        return $this->status === 'pendente' && 
               $this->data_expiracao && 
               Carbon::parse($this->data_expiracao)->isFuture();
    }

    /**
     * Verificar se o convite expirou
     */
    public function expirou(): bool
    {
        return $this->data_expiracao && 
               Carbon::parse($this->data_expiracao)->isPast();
    }

    /**
     * Verificar se o convite foi aceito
     */
    public function foiAceito(): bool
    {
        return $this->status === 'aceito';
    }

    /**
     * Verificar se o convite foi recusado
     */
    public function foiRecusado(): bool
    {
        return $this->status === 'recusado';
    }

    /**
     * Verificar se o convite está pendente
     */
    public function estaPendente(): bool
    {
        return $this->status === 'pendente';
    }

    /**
     * Aceitar o convite
     */
    public function aceitar(): bool
    {
        if (!$this->estaValido()) {
            return false;
        }

        $this->status = 'aceito';
        return $this->save();
    }

    /**
     * Recusar o convite
     */
    public function recusar(): bool
    {
        if ($this->status !== 'pendente') {
            return false;
        }

        $this->status = 'recusado';
        return $this->save();
    }

    /**
     * Marcar como expirado
     */
    public function marcarExpirado(): bool
    {
        $this->status = 'expirado';
        return $this->save();
    }

    // ==================== MÉTODOS DE FORMATAÇÃO ====================

    /**
     * Obter nome do status formatado
     */
    public function getNomeStatus(): string
    {
        $nomes = [
            'pendente' => 'Pendente',
            'aceito' => 'Aceito',
            'recusado' => 'Recusado', 
            'expirado' => 'Expirado'
        ];

        return $nomes[$this->status] ?? 'Desconhecido';
    }

    /**
     * Obter cor do status para UI
     */
    public function getCorStatus(): string
    {
        $cores = [
            'pendente' => '#ffc107',  // Amarelo
            'aceito' => '#28a745',    // Verde
            'recusado' => '#dc3545',  // Vermelho
            'expirado' => '#6c757d'   // Cinza
        ];

        return $cores[$this->status] ?? '#6c757d';
    }

    /**
     * Obter ícone do status
     */
    public function getIconeStatus(): string
    {
        $icones = [
            'pendente' => '⏳',
            'aceito' => '✅',
            'recusado' => '❌',
            'expirado' => '⏰'
        ];

        return $icones[$this->status] ?? '❓';
    }

    /**
     * Formatar data de criação
     */
    public function getDataCriacaoFormatadaAttribute(): string
    {
        if (!$this->data_criacao) {
            return 'Data não disponível';
        }

        return Carbon::parse($this->data_criacao)->format('d/m/Y H:i');
    }

    /**
     * Formatar data de expiração
     */
    public function getDataExpiracaoFormatadaAttribute(): string
    {
        if (!$this->data_expiracao) {
            return 'Sem expiração';
        }

        return Carbon::parse($this->data_expiracao)->format('d/m/Y H:i');
    }

    /**
     * Tempo restante até expiração
     */
    public function getTempoRestanteAttribute(): string
    {
        if (!$this->data_expiracao) {
            return 'Sem limite';
        }

        $expiracao = Carbon::parse($this->data_expiracao);
        
        if ($expiracao->isPast()) {
            return 'Expirado';
        }

        return $expiracao->diffForHumans();
    }

    /**
     * Tempo restante em horas
     */
    public function getHorasRestantes(): int
    {
        if (!$this->data_expiracao) {
            return -1; // Sem limite
        }

        $expiracao = Carbon::parse($this->data_expiracao);
        
        if ($expiracao->isPast()) {
            return 0; // Expirado
        }

        return (int) now()->diffInHours($expiracao);
    }

    /**
     * Obter link de aceitação do convite
     */
    public function getLinkConvite(): string
    {
        return url("/convites/{$this->token}");
    }

    // ==================== MÉTODOS ESTÁTICOS ====================

    /**
     * Buscar convite por token
     */
    public static function buscarPorToken($token)
    {
        return static::where('token', $token)->first();
    }

    /**
     * Buscar convite válido por token
     */
    public static function buscarValidoPorToken($token)
    {
        return static::where('token', $token)
                    ->pendentes()
                    ->validos()
                    ->first();
    }

    /**
     * Obter convites recebidos por um usuário
     */
    public static function getConvitesRecebidos($userId, $limite = 10)
    {
        return static::paraUsuario($userId)
                    ->with(['sala.criador', 'remetente'])
                    ->orderBy('data_criacao', 'desc')
                    ->limit($limite)
                    ->get();
    }

    /**
     * Obter convites enviados por um usuário
     */
    public static function getConvitesEnviados($userId, $limite = 10)
    {
        return static::enviadosPor($userId)
                    ->with(['sala', 'destinatario'])
                    ->orderBy('data_criacao', 'desc')
                    ->limit($limite)
                    ->get();
    }

    /**
     * Contar convites pendentes para um usuário
     */
    public static function contarConvitesPendentes($userId): int
    {
        return static::paraUsuario($userId)
                    ->pendentes()
                    ->validos()
                    ->count();
    }

    /**
     * Verificar se já existe convite pendente entre usuário e sala
     */
    public static function existeConvitePendente($salaId, $destinatarioId): bool
    {
        return static::where('sala_id', $salaId)
                    ->where('destinatario_id', $destinatarioId)
                    ->pendentes()
                    ->validos()
                    ->exists();
    }

    /**
     * Limpar convites expirados
     */
    public static function limparExpirados(): int
    {
        $convitesExpirados = static::where('status', 'pendente')
                                  ->where('data_expiracao', '<=', now())
                                  ->get();

        $contador = 0;
        foreach ($convitesExpirados as $convite) {
            if ($convite->marcarExpirado()) {
                $contador++;
            }
        }

        return $contador;
    }

    /**
     * Estatísticas de convites
     */
    public static function getEstatisticas(): array
    {
        return [
            'total_convites' => static::count(),
            'pendentes' => static::pendentes()->count(),
            'aceitos' => static::aceitos()->count(),
            'recusados' => static::where('status', 'recusado')->count(),
            'expirados' => static::where('status', 'expirado')->count(),
            'validos' => static::pendentes()->validos()->count()
        ];
    }
}