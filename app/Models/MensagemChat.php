<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MensagemChat extends Model
{
    use HasFactory;

    protected $table = 'mensagens_chat';

    protected $fillable = [
        'sala_id',
        'usuario_id',
        'mensagem',
        'mensagem_original',
        'censurada',
        'flags_detectadas',
        'motivo_censura',
        'editada',
        'editada_em',
        'deletada',
        'deletada_em',
        'deletada_por',
        'ip_address'
    ];

    protected $casts = [
        'censurada' => 'boolean',
        'flags_detectadas' => 'array',
        'editada' => 'boolean',
        'editada_em' => 'datetime',
        'deletada' => 'boolean',
        'deletada_em' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $hidden = [
        'mensagem_original', // Esconder mensagem original censurada
        'ip_address'
    ];

    // Relacionamentos
    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function anexos()
    {
        return $this->hasMany(ChatAnexo::class, 'mensagem_id');
    }

    public function deletadaPor()
    {
        return $this->belongsTo(Usuario::class, 'deletada_por');
    }

    // Scopes
    public function scopeAtivas($query)
    {
        return $query->where('deletada', false);
    }

    public function scopeDaSala($query, $salaId)
    {
        return $query->where('sala_id', $salaId);
    }

    public function scopeDoUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    public function scopeCensuradas($query)
    {
        return $query->where('censurada', true);
    }

    // Métodos auxiliares
    
    /**
 * Verificar se mensagem deve ser censurada para um usuário específico
 */
public function deveCensurarPara(Usuario $usuario)
{
    if (!$this->censurada) {
        return false;
    }

    $flags = $this->flags_detectadas ?? [];
    if (empty($flags)) {
        return false;
    }

    $idade = $usuario->data_de_nascimento 
        ? Carbon::parse($usuario->data_de_nascimento)->age 
        : 18;

    // ✅ CORRIGIR: Menor de 15 censura PROFANITY e SEXUAL
    if ($idade < 15) {
        return in_array('profanity', $flags) || in_array('sexual', $flags);
    }

    // ✅ 15-17: censura apenas sexual
    if ($idade >= 15 && $idade < 18) {
        return $this->temConteudoSexual();
    }

    // ✅ 18+: censura apenas sexual (mas pode ver depois)
    if ($idade >= 18) {
        return $this->temConteudoSexual();
    }


    return false;
}

/**
 * Verificar se contém conteúdo sexual
 */
public function temConteudoSexual()
{
    $flags = $this->flags_detectadas ?? [];
    return !empty(array_intersect($flags, ['sexual', 'porn']));
}


    /**
 * Obter mensagem formatada para um usuário
 */
public function getMensagemParaUsuario(Usuario $usuario)
{
    if ($this->deletada) {
        return '[Mensagem deletada]';
    }

    $idade = $usuario->data_de_nascimento 
        ? Carbon::parse($usuario->data_de_nascimento)->age 
        : 18;
    
    $flags = $this->flags_detectadas ?? [];
    
    // ✅ CORRIGIR: Menor de 15 vê censurado PROFANITY + SEXUAL
    if ($idade < 15 && $this->censurada) {
        $temProfanity = in_array('profanity', $flags);
        $temSexual = $this->temConteudoSexual();
        
        if ($temProfanity || $temSexual) {
            return $this->mensagem; // ← Retorna versão censurada (com ***)
        }
    }
    
    // ✅ 15-17: mostra palavrões, oculta sexual
    if ($idade >= 15 && $idade < 18) {
        if ($this->temConteudoSexual()) {
            return '[Mensagem oculta – violou as regras de conteúdo]';
        }
        return $this->mensagem_original ?? $this->mensagem;
    }
    
    // ✅ 18+: mostra palavrões, oculta sexual (mas pode ver depois)
    if ($idade >= 18) {
        if ($this->temConteudoSexual()) {
            return '[Mensagem oculta – violou as regras de conteúdo]';
        }
        return $this->mensagem_original ?? $this->mensagem;
    }

    return $this->mensagem;
}

    /**
 * Verificar se usuário pode ver conteúdo censurado
 */
public function usuarioPodeVerCensurado(Usuario $usuario)
{
    if (!$this->censurada) {
        return true;
    }

    $idade = $usuario->data_de_nascimento 
        ? Carbon::parse($usuario->data_de_nascimento)->age 
        : 18;

    // ✅ Apenas 18+ pode ver conteúdo sexual censurado
    return $idade >= 18 && $this->temConteudoSexual();
}

    /**
     * Marcar como deletada
     */
    public function marcarDeletada(Usuario $usuario)
    {
        $this->update([
            'deletada' => true,
            'deletada_em' => now(),
            'deletada_por' => $usuario->id
        ]);
    }

    /**
     * Formatar timestamp para exibição
     */
    public function getTimestampFormatado()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Obter flags como badges HTML
     */
    public function getFlagsBadges()
    {
        if (!$this->censurada || empty($this->flags_detectadas)) {
            return '';
        }

        $badges = [];
        $cores = [
            'swear' => 'bg-yellow-100 text-yellow-800',
            'sexual' => 'bg-red-100 text-red-800',
            'porn' => 'bg-red-200 text-red-900',
            'harassment' => 'bg-orange-100 text-orange-800',
            'profanity' => 'bg-yellow-100 text-yellow-800'
        ];

        foreach ($this->flags_detectadas as $flag) {
            $cor = $cores[$flag] ?? 'bg-gray-100 text-gray-800';
            $badges[] = "<span class='inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {$cor}'>{$flag}</span>";
        }

        return implode(' ', $badges);
    }
}