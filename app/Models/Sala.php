<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * Model para a tabela 'salas'
 * Representa uma sala de RPG no sistema
 */
class Sala extends Model
{
    use HasFactory;

    protected $table = 'salas';
    protected $primaryKey = 'id';
    public $timestamps = false; // Usando data_criacao customizada

    /**
     * Campos que podem ser preenchidos via mass assignment
     */
    protected $fillable = [
        'nome',
        'descricao',
        'criador_id',
        'imagem_url',
        'tipo',
        'senha_hash',
        'max_participantes',
        'data_criacao',
        'ativa',
        'profile_photo_url',      
        'profile_photo_color',    
        // campos do banner
        'banner_url',
        'banner_color',
        // NOVOS CAMPOS
    'motivo_desativacao',
    'desativada_por',
    'data_desativacao'
    ];

    /**
     * Campos que devem ser convertidos para tipos específicos
     */
    protected $casts = [
        'data_criacao' => 'datetime',
        'ativa' => 'boolean',
        'max_participantes' => 'integer',
        'data_desativacao' => 'datetime' // NOVO
    ];

    /**
     * Campos ocultos na serialização JSON
     */
    protected $hidden = [
        'senha_hash'
    ];

    // ==================== RELACIONAMENTOS ====================

    /**
     * Relacionamento: Sala pertence a um criador (usuário)
     */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'criador_id');
    }

    /**
     * Relacionamento: Sala tem muitos participantes
     */
    public function participantes(): HasMany
    {
        return $this->hasMany(ParticipanteSala::class, 'sala_id')
            ->where('ativo', true);
    }

    /**
 * Relacionamento: Sala foi desativada por um staff
 */
public function desativadaPor(): BelongsTo
{
    return $this->belongsTo(Usuario::class, 'desativada_por');
}

    /**
     * Relacionamento: Sala tem muitos participantes (incluindo inativos)
     */
    public function todosParticipantes(): HasMany
    {
        return $this->hasMany(ParticipanteSala::class, 'sala_id');
    }

    /**
     * Relacionamento: Sala tem muitas permissões específicas
     */
    public function permissoes(): HasMany
    {
        return $this->hasMany(PermissaoSala::class, 'sala_id');
    }

    /**
     * Relacionamento: Sala tem muitos convites
     */
    public function convites(): HasMany
    {
        return $this->hasMany(ConviteSala::class, 'sala_id');
    }

    /**
     * Relacionamento: Sala tem muitos conteúdos
     */
    public function conteudos(): HasMany
    {
        return $this->hasMany(Conteudo::class, 'sala_id');
    }

    /**
     * Relacionamento: Sala tem muitas sessões de jogo
     */
    public function sessoes(): HasMany
    {
        return $this->hasMany(SessaoJogo::class, 'sala_id');
    }

    // ==================== SCOPES DE BUSCA ====================

    /**
     * Scope: Apenas salas ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativa', true);
    }

    /**
     * Scope: Apenas salas públicas
     */
    public function scopePublicas($query)
    {
        return $query->where('tipo', 'publica');
    }

    /**
     * Scope: Salas que um usuário participa
     */
    public function scopeComParticipante($query, $userId)
    {
        return $query->whereHas('participantes', function ($q) use ($userId) {
            $q->where('usuario_id', $userId)->where('ativo', true);
        });
    }

    /**
     * Scope: Salas criadas por um usuário
     */
    public function scopeCriadasPor($query, $userId)
    {
        return $query->where('criador_id', $userId);
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Verificar se a sala está lotada
     */
    public function estaLotada(): bool
    {
        return $this->getNumeroParticipantesAtivos() >= $this->max_participantes;
    }

    /**
     * Contar participantes ativos
     */
    public function getNumeroParticipantesAtivos(): int
    {
        return $this->participantes()->count();
    }

    /**
     * Verificar se um usuário é participante da sala
     */
    public function temParticipante($userId): bool
    {
        return $this->participantes()
            ->where('usuario_id', $userId)
            ->exists();
    }

    /**
     * Verificar se um usuário é o criador da sala
     */
    public function eCriador($userId): bool
    {
        return $this->criador_id == $userId;
    }

    /**
     * Obter papel do usuário na sala
     */
    public function getPapelUsuario($userId): ?string
    {
        $participante = $this->participantes()
            ->where('usuario_id', $userId)
            ->first();

        return $participante ? $participante->papel : null;
    }

    /**
     * Verificar se usuário é mestre da sala
     */
    public function usuarioEMestre($userId): bool
    {
        return $this->getPapelUsuario($userId) === 'mestre';
    }

    /**
     * Verificar se a sala precisa de senha
     */
    public function precisaSenha(): bool
    {
        return $this->tipo === 'privada' && !empty($this->senha_hash);
    }

    /**
     * Verificar se é sala apenas por convite
     */
    public function apenasConvite(): bool
    {
        return $this->tipo === 'apenas_convite';
    }

    /**
     * Obter permissões de um usuário na sala
     */
    public function getPermissoesUsuario($userId): ?PermissaoSala
    {
        return $this->permissoes()
            ->where('usuario_id', $userId)
            ->first();
    }

    /**
     * Verificar se usuário tem permissão específica
     */
    public function usuarioTemPermissao($userId, $permissao): bool
    {
        $permissoes = $this->getPermissoesUsuario($userId);

        if (!$permissoes) {
            return false;
        }

        return $permissoes->{$permissao} ?? false;
    }

    // ==================== MÉTODOS DE FORMATAÇÃO ====================

    /**
     * Obter URL da imagem ou imagem padrão
     */
    public function getImagemUrlAttribute($value): string
    {
        if ($value && filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Retornar imagem padrão baseada no tipo da sala
        $imagensPadrao = [
            'publica' => asset('images/sala-publica-default.png'),
            'privada' => asset('images/sala-privada-default.png'),
            'apenas_convite' => asset('images/sala-convite-default.png')
        ];

        return $imagensPadrao[$this->tipo] ?? asset('images/sala-default.png');
    }

    /**
     * Formatar data de criação para exibição
     */
    public function getDataCriacaoFormatadaAttribute(): string
    {
        if (!$this->data_criacao) {
            return 'Data não disponível';
        }

        return Carbon::parse($this->data_criacao)->format('d/m/Y H:i');
    }

    /**
     * Calcular tempo desde a criação
     */
    public function getIdadeAttribute(): string
    {
        if (!$this->data_criacao) {
            return 'Desconhecido';
        }

        return Carbon::parse($this->data_criacao)->diffForHumans();
    }

    /**
     * Status da sala em texto amigável
     */
    public function getStatusTextoAttribute(): string
    {
        if (!$this->ativa) {
            return 'Inativa';
        }

        if ($this->estaLotada()) {
            return 'Lotada';
        }

        return 'Disponível';
    }

    /**
     * Obter ícone baseado no tipo da sala
     */
    public function getIconeAttribute(): string
    {
        $icones = [
            'publica' => '🌍',
            'privada' => '🔒',
            'apenas_convite' => '📧'
        ];

        return $icones[$this->tipo] ?? '🏠';
    }

    // ==================== MÉTODOS ESTÁTICOS ====================

    /**
     * Buscar salas disponíveis para um usuário (que não participa)
     */
    public static function disponiveisPara($userId, $limite = 10)
    {
        return static::ativas()
            ->publicas()
            ->whereDoesntHave('participantes', function ($query) use ($userId) {
                $query->where('usuario_id', $userId)->where('ativo', true);
            })
            ->with(['criador', 'participantes.usuario'])
            ->orderBy('data_criacao', 'desc')
            ->limit($limite)
            ->get();
    }

    /**
     * Buscar salas de um usuário (criadas ou participando)
     */
    public static function doUsuario($userId)
    {
        return static::ativas()
            ->comParticipante($userId)
            ->with(['criador', 'participantes.usuario'])
            ->orderBy('data_criacao', 'desc')
            ->get();
    }

    /**
     * Estatísticas gerais das salas
     */
    public static function getEstatisticas(): array
    {
        return [
            'total_salas' => static::count(),
            'salas_ativas' => static::ativas()->count(),
            'salas_publicas' => static::publicas()->count(),
            'salas_privadas' => static::where('tipo', 'privada')->count(),
            'salas_convite' => static::where('tipo', 'apenas_convite')->count()
        ];
    }
    public function getSessaoAtiva()
    {
        return $this->sessoes()
            ->whereIn('status', ['ativa', 'pausada'])
            ->first();
    }

    /**
     * Verificar se há sessão ativa
     */
    public function temSessaoAtiva(): bool
    {
        return $this->getSessaoAtiva() !== null;
    }
}
