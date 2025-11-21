<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notificacao extends Model
{
    protected $table = 'notificacoes';

    protected $fillable = [
        'usuario_id',
        'remetente_id',
        'tipo',
        'mensagem',
        'icone',
        'cor',
        'link',
        'dados',
        'lida',
        'lida_em'
    ];

    protected $casts = [
        'dados' => 'array',
        'lida' => 'boolean',
        'lida_em' => 'datetime',
        'created_at' => 'datetime'
    ];

    // ==================== RELACIONAMENTOS ====================

    /**
     * Usuário que recebe a notificação
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Usuário que causou a notificação
     */
    public function remetente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'remetente_id');
    }

    // ==================== SCOPES ====================

    /**
     * Apenas notificações não lidas
     */
    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }

    /**
     * Notificações de um usuário
     */
    public function scopeDoUsuario($query, $userId)
    {
        return $query->where('usuario_id', $userId);
    }

    /**
     * Notificações recentes (últimos 30 dias)
     */
    public function scopeRecentes($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays(30));
    }

    // ==================== MÉTODOS ESTÁTICOS ====================

    /**
     * Criar notificação de novo seguidor
     */
    public static function notificarNovoSeguidor($seguidorId, $seguidoId)
    {
        $seguidor = Usuario::find($seguidorId);
        
        return self::create([
            'usuario_id' => $seguidoId,
            'remetente_id' => $seguidorId,
            'tipo' => 'follow',
            'mensagem' => "{$seguidor->username} começou a seguir você",
            'icone' => 'user-plus',
            'cor' => 'blue',
            'link' => route('perfil.show', $seguidor->username),
            'dados' => ['seguidor_id' => $seguidorId]
        ]);
    }

    /**
     * Criar notificação de curtida em post
     */
    public static function notificarCurtida($userId, $postId, $autorPostId)
    {
        // Não notificar se curtiu próprio post
        if ($userId == $autorPostId) return null;

        $usuario = Usuario::find($userId);
        $post = Post::find($postId);
        
        return self::create([
            'usuario_id' => $autorPostId,
            'remetente_id' => $userId,
            'tipo' => 'like',
            'mensagem' => "{$usuario->username} curtiu sua publicação",
            'icone' => 'heart',
            'cor' => 'red',
            'link' => route('comunidade.post.show', $post->slug),
            'dados' => ['post_id' => $postId]
        ]);
    }

    /**
     * Criar notificação de comentário
     */
    public static function notificarComentario($userId, $postId, $comentario, $autorPostId)
    {
        // Não notificar se comentou próprio post
        if ($userId == $autorPostId) return null;

        $usuario = Usuario::find($userId);
        $post = Post::find($postId);
        
        $preview = strlen($comentario) > 50 
            ? substr($comentario, 0, 50) . '...' 
            : $comentario;
        
        return self::create([
            'usuario_id' => $autorPostId,
            'remetente_id' => $userId,
            'tipo' => 'comment',
            'mensagem' => "{$usuario->username} comentou: \"{$preview}\"",
            'icone' => 'message-circle',
            'cor' => 'green',
            'link' => route('comunidade.post.show', $post->slug),
            'dados' => ['post_id' => $postId, 'comentario' => $comentario]
        ]);
    }

    /**
     * Criar notificação de convite para sala
     */
    public static function notificarConviteSala($remetenteId, $destinatarioId, $salaId, $salaNome)
    {
        $remetente = Usuario::find($remetenteId);
        
        return self::create([
            'usuario_id' => $destinatarioId,
            'remetente_id' => $remetenteId,
            'tipo' => 'sala_convite',
            'mensagem' => "{$remetente->username} convidou você para a sala \"{$salaNome}\"",
            'icone' => 'mail',
            'cor' => 'yellow',
            'link' => route('salas.show', $salaId),
            'dados' => ['sala_id' => $salaId, 'sala_nome' => $salaNome]
        ]);
    }

    /**
     * Criar notificação de menção em comentário
     */
    public static function notificarMencao($autorId, $mencionadoId, $postId, $comentario)
    {
        if ($autorId == $mencionadoId) return null;

        $autor = Usuario::find($autorId);
        $post = Post::find($postId);
        
        return self::create([
            'usuario_id' => $mencionadoId,
            'remetente_id' => $autorId,
            'tipo' => 'mention',
            'mensagem' => "{$autor->username} mencionou você em um comentário",
            'icone' => 'at-sign',
            'cor' => 'purple',
            'link' => route('comunidade.post.show', $post->slug),
            'dados' => ['post_id' => $postId, 'comentario' => $comentario]
        ]);
    }

    /**
     * Criar notificação de resposta em ticket
     */
    public static function notificarRespostaTicket($staffId, $usuarioId, $ticketId, $numeroTicket)
    {
        $staff = Usuario::find($staffId);
        
        return self::create([
            'usuario_id' => $usuarioId,
            'remetente_id' => $staffId,
            'tipo' => 'ticket_resposta',
            'mensagem' => "{$staff->username} respondeu seu ticket #{$numeroTicket}",
            'icone' => 'message-square',
            'cor' => 'blue',
            'link' => route('suporte.show', $ticketId),
            'dados' => ['ticket_id' => $ticketId, 'numero_ticket' => $numeroTicket]
        ]);
    }

    // ==================== MÉTODOS DE INSTÂNCIA ====================

    /**
     * Marcar como lida
     */
    public function marcarComoLida()
    {
        if (!$this->lida) {
            $this->update([
                'lida' => true,
                'lida_em' => now()
            ]);
        }
    }

    /**
     * Tempo desde criação (para exibição)
     */
    public function getTempoDecorridoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Ícone formatado para Lucide React
     */
    public function getIconeFormatadoAttribute()
    {
        $icones = [
            'follow' => 'UserPlus',
            'like' => 'Heart',
            'comment' => 'MessageCircle',
            'mention' => 'AtSign',
            'sala_convite' => 'Mail',
            'ticket_resposta' => 'MessageSquare',
        ];

        return $icones[$this->tipo] ?? 'Bell';
    }

    /**
     * Cor em formato Tailwind
     */
    public function getCorTailwindAttribute()
    {
        $cores = [
            'blue' => 'text-blue-500 bg-blue-500/10',
            'green' => 'text-green-500 bg-green-500/10',
            'red' => 'text-red-500 bg-red-500/10',
            'yellow' => 'text-yellow-500 bg-yellow-500/10',
            'purple' => 'text-purple-500 bg-purple-500/10',
        ];

        return $cores[$this->cor] ?? 'text-gray-500 bg-gray-500/10';
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Contar notificações não lidas de um usuário
     */
    public static function contarNaoLidas($userId)
    {
        return self::where('usuario_id', $userId)
            ->where('lida', false)
            ->count();
    }

    /**
     * Marcar todas como lidas
     */
    public static function marcarTodasComoLidas($userId)
    {
        return self::where('usuario_id', $userId)
            ->where('lida', false)
            ->update([
                'lida' => true,
                'lida_em' => now()
            ]);
    }

    /**
     * Limpar notificações antigas (mais de 30 dias e lidas)
     */
    public static function limparAntigas()
    {
        return self::where('lida', true)
            ->where('created_at', '<', Carbon::now()->subDays(30))
            ->delete();
    }
}