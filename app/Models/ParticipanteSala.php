<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Model para a tabela 'participantes_sala'
 * Representa a relação entre usuários e salas com papéis específicos
 */
class ParticipanteSala extends Model
{
    use HasFactory;

    protected $table = 'participantes_sala';
    protected $primaryKey = 'id';
    public $timestamps = false; // Usando data_entrada customizada

    /**
     * Campos que podem ser preenchidos via mass assignment
     */
    protected $fillable = [
        'sala_id',
        'usuario_id', 
        'papel',
        'data_entrada',
        'ativo'
    ];

    /**
     * Campos que devem ser convertidos para tipos específicos
     */
    protected $casts = [
        'data_entrada' => 'datetime',
        'ativo' => 'boolean'
    ];

    /**
     * Valores possíveis para o campo 'papel'
     */
    const PAPEIS = [
        'membro' => 'membro',
        'mestre' => 'mestre', 
        'admin_sala' => 'admin_sala'
    ];

    // ==================== RELACIONAMENTOS ====================

    /**
     * Relacionamento: Participante pertence a uma sala
     */
    public function sala(): BelongsTo
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }

    /**
     * Relacionamento: Participante pertence a um usuário
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope: Apenas participantes ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope: Apenas mestres
     */
    public function scopeMestres($query)
    {
        return $query->where('papel', 'mestre');
    }

    /**
     * Scope: Apenas membros
     */
    public function scopeMembros($query)
    {
        return $query->where('papel', 'membro');
    }

    /**
     * Scope: Por sala específica
     */
    public function scopeDaSala($query, $salaId)
    {
        return $query->where('sala_id', $salaId);
    }

    /**
     * Scope: Por usuário específico
     */
    public function scopeDoUsuario($query, $userId)
    {
        return $query->where('usuario_id', $userId);
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Verificar se o participante é mestre
     */
    public function eMestre(): bool
    {
        return $this->papel === 'mestre';
    }

    /**
     * Verificar se o participante é admin da sala
     */
    public function eAdmin(): bool
    {
        return $this->papel === 'admin_sala';
    }

    /**
     * Verificar se o participante é apenas membro
     */
    public function eMembro(): bool
    {
        return $this->papel === 'membro';
    }

    /**
     * Verificar se tem privilégios administrativos
     */
    public function temPrivilegiosAdmin(): bool
    {
        return in_array($this->papel, ['mestre', 'admin_sala']);
    }

    /**
     * Obter nome do papel formatado
     */
    public function getNomePapel(): string
    {
        $nomes = [
            'membro' => 'Membro',
            'mestre' => 'Mestre',
            'admin_sala' => 'Administrador'
        ];

        return $nomes[$this->papel] ?? 'Desconhecido';
    }

    /**
     * Obter cor do papel para UI
     */
    public function getCorPapel(): string
    {
        $cores = [
            'membro' => '#6c757d',    // Cinza
            'mestre' => '#dc3545',    // Vermelho
            'admin_sala' => '#ffc107' // Amarelo
        ];

        return $cores[$this->papel] ?? '#6c757d';
    }

    /**
     * Obter ícone do papel
     */
    public function getIconePapel(): string
    {
        $icones = [
            'membro' => '👤',
            'mestre' => '⚔️',
            'admin_sala' => '⚡'
        ];

        return $icones[$this->papel] ?? '👤';
    }

    // ==================== MÉTODOS DE FORMATAÇÃO ====================

    /**
     * Formatar data de entrada
     */
    public function getDataEntradaFormatadaAttribute(): string
    {
        if (!$this->data_entrada) {
            return 'Data não disponível';
        }

        return Carbon::parse($this->data_entrada)->format('d/m/Y H:i');
    }

    /**
     * Calcular tempo na sala
     */
    public function getTempoNaSalaAttribute(): string
    {
        if (!$this->data_entrada) {
            return 'Desconhecido';
        }

        return Carbon::parse($this->data_entrada)->diffForHumans();
    }

    /**
     * Status do participante
     */
    public function getStatusAttribute(): string
    {
        if (!$this->ativo) {
            return 'Inativo';
        }

        return 'Ativo';
    }

    // ==================== MÉTODOS ESTÁTICOS ====================

    /**
     * Buscar participação de usuário em sala específica
     */
    public static function buscarParticipacao($userId, $salaId)
    {
        return static::where('usuario_id', $userId)
                    ->where('sala_id', $salaId)
                    ->where('ativo', true)
                    ->first();
    }

    /**
     * Verificar se usuário participa de uma sala
     */
    public static function usuarioParticipaDaSala($userId, $salaId): bool
    {
        return static::where('usuario_id', $userId)
                    ->where('sala_id', $salaId)
                    ->where('ativo', true)
                    ->exists();
    }

    /**
     * Contar participantes ativos de uma sala
     */
    public static function contarParticipantesDaSala($salaId): int
    {
        return static::where('sala_id', $salaId)
                    ->where('ativo', true)
                    ->count();
    }

    /**
     * Obter mestres de uma sala
     */
    public static function getMestresDaSala($salaId)
    {
        return static::where('sala_id', $salaId)
                    ->where('papel', 'mestre')
                    ->where('ativo', true)
                    ->with('usuario')
                    ->get();
    }

    /**
     * Obter todas as salas que um usuário participa
     */
    public static function getSalasDoUsuario($userId)
    {
        return static::where('usuario_id', $userId)
                    ->where('ativo', true)
                    ->with(['sala.criador', 'sala.participantes.usuario'])
                    ->orderBy('data_entrada', 'desc')
                    ->get();
    }

    /**
     * Promover usuário para um papel superior
     */
    public function promover(): bool
    {
        $hierarquia = [
            'membro' => 'admin_sala',
            'admin_sala' => 'mestre'
        ];

        if (isset($hierarquia[$this->papel])) {
            $this->papel = $hierarquia[$this->papel];
            return $this->save();
        }

        return false; // Já é mestre ou papel inválido
    }

    /**
     * Rebaixar usuário para um papel inferior
     */
    public function rebaixar(): bool
    {
        $hierarquia = [
            'mestre' => 'admin_sala',
            'admin_sala' => 'membro'
        ];

        if (isset($hierarquia[$this->papel])) {
            $this->papel = $hierarquia[$this->papel];
            return $this->save();
        }

        return false; // Já é membro ou papel inválido
    }

    /**
     * Remover participante da sala (desativar)
     */
    public function sairDaSala(): bool
    {
        $this->ativo = false;
        return $this->save();
    }

    /**
     * Reativar participação na sala
     */
    public function reativar(): bool
    {
        $this->ativo = true;
        return $this->save();
    }

    // ==================== VALIDAÇÕES ====================

    /**
     * Verificar se o papel é válido
     */
    public static function papelValido($papel): bool
    {
        return array_key_exists($papel, self::PAPEIS);
    }

    /**
     * Obter lista de papéis disponíveis
     */
    public static function getPapeisDisponiveis(): array
    {
        return [
            'membro' => 'Membro',
            'admin_sala' => 'Administrador',
            'mestre' => 'Mestre'
        ];
    }
}