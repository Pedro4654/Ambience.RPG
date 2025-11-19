<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';
    public $timestamps = false;

    // Configurar os nomes dos timestamps da sua tabela
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';

    protected $fillable = [
        'username',
        'nickname',
        'email',
        'senha_hash',
        'avatar_url',
        'bio',
        'data_de_nascimento',
        'status',
        'nivel_usuario',
        'data_criacao',
        'pontos_reputacao',
        'ranking_posicao',
        'verificado',
        // Recuperação de senha
        'reset_token',
        'reset_token_expires_at',
        'reset_attempts',
        'reset_attempts_reset_at',
        // Status online
        'is_online',
        'last_seen',
        'current_room',
        // Campos de moderação
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
    ];

    protected $hidden = [
        'senha_hash',
        'remember_token',
        'reset_token',
    ];

    protected $casts = [
        'data_criacao' => 'datetime',
        'data_atualizacao' => 'datetime',
        'data_de_nascimento' => 'date',
        'verificado' => 'boolean',
        'reset_token_expires_at' => 'datetime',
        'reset_attempts_reset_at' => 'datetime',
        'reset_attempts' => 'integer',
        'is_online' => 'boolean',
        'last_seen' => 'datetime',
        // Casts para moderação
        'warning_ativo' => 'boolean',
        'warning_data' => 'datetime',
        'ban_inicio' => 'datetime',
        'ban_fim' => 'datetime',
        'ip_ban_ativo' => 'boolean',
        'ip_ban_data' => 'datetime',
        'account_deleted_at' => 'datetime',
        'account_hard_delete_at' => 'datetime'
    ];

    public function getAuthPassword()
    {
        return $this->senha_hash;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['senha_hash'] = Hash::make($password);
    }

    // ============================================================
    // MÉTODOS DE STATUS ONLINE
    // ============================================================

    public function setOnline($roomId = null)
    {
        $this->update([
            'is_online' => true,
            'last_seen' => now(),
            'current_room' => $roomId
        ]);
    }

    public function setOffline()
    {
        $this->update([
            'is_online' => false,
            'last_seen' => now(),
            'current_room' => null
        ]);
    }

    public function isInRoom($roomId)
    {
        return $this->current_room == $roomId && $this->is_online;
    }

    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    public function scopeOnlineInRoom($query, $roomId)
    {
        return $query->where('is_online', true)
            ->where('current_room', $roomId);
    }

    // ============================================================
    // MÉTODOS DE AVATAR
    // ============================================================

    public function getAvatarUrlAttribute($value)
    {
        Log::info('Verificando avatar_url', ['value' => $value, 'user_id' => $this->id]);

        if ($value) {
            $fullPath = storage_path('app/public/' . $value);
            Log::info('Caminho completo do avatar', ['path' => $fullPath, 'exists' => file_exists($fullPath)]);

            if (file_exists($fullPath)) {
                $url = asset('storage/' . $value);
                Log::info('URL do avatar gerada', ['url' => $url]);
                return $url;
            } else {
                Log::warning('Arquivo de avatar não encontrado', ['path' => $fullPath]);
            }
        }

        $defaultUrl = asset('images/default-avatar.png');
        Log::info('Usando avatar padrão', ['url' => $defaultUrl]);
        return $defaultUrl;
    }

    public function deleteOldAvatar()
    {
        $originalAvatarUrl = $this->getOriginal('avatar_url');
        Log::info('Tentando deletar avatar antigo', ['avatar_url' => $originalAvatarUrl]);

        if ($originalAvatarUrl) {
            $fullPath = storage_path('app/public/' . $originalAvatarUrl);
            Log::info('Caminho para deletar', ['path' => $fullPath, 'exists' => file_exists($fullPath)]);

            if (file_exists($fullPath)) {
                $deleted = unlink($fullPath);
                Log::info('Resultado da deleção', ['deleted' => $deleted, 'path' => $fullPath]);
                return $deleted;
            } else {
                Log::warning('Arquivo não encontrado para deletar', ['path' => $fullPath]);
            }
        } else {
            Log::info('Nenhum avatar para deletar');
        }

        return false;
    }

    // ============================================================
    // MÉTODOS DE RECUPERAÇÃO DE SENHA COM TOKEN DE 6 DÍGITOS
    // ============================================================

    public function generateResetToken(): string
    {
        do {
            $token = sprintf('%06d', mt_rand(0, 999999));
        } while (
            self::where('reset_token', $token)
            ->where('reset_token_expires_at', '>', Carbon::now())
            ->where('id', '!=', $this->id)
            ->exists()
        );

        Log::info('Token de 6 dígitos gerado', [
            'user_id' => $this->id,
            'token' => $token,
            'email' => $this->email
        ]);

        return $token;
    }

    public function setResetToken(): string
    {
        $token = $this->generateResetToken();

        $this->update([
            'reset_token' => $token,
            'reset_token_expires_at' => Carbon::now()->addMinutes(15),
            'reset_attempts' => 0,
            'reset_attempts_reset_at' => Carbon::now()->addHour(),
        ]);

        Log::info('Token de reset definido', [
            'user_id' => $this->id,
            'email' => $this->email,
            'expires_at' => $this->reset_token_expires_at
        ]);

        return $token;
    }

    public function isValidResetToken(string $token): bool
    {
        $isValid = $this->reset_token === $token
            && $this->reset_token_expires_at
            && $this->reset_token_expires_at->isFuture();

        Log::info('Verificação de token', [
            'user_id' => $this->id,
            'token_provided' => $token,
            'token_stored' => $this->reset_token,
            'expires_at' => $this->reset_token_expires_at,
            'is_valid' => $isValid
        ]);

        return $isValid;
    }

    public function canAttemptReset(): bool
    {
        if ($this->reset_attempts_reset_at && $this->reset_attempts_reset_at->isPast()) {
            $this->update([
                'reset_attempts' => 0,
                'reset_attempts_reset_at' => Carbon::now()->addHour()
            ]);

            Log::info('Contador de tentativas resetado', ['user_id' => $this->id]);
            return true;
        }

        $canAttempt = $this->reset_attempts < 5;

        Log::info('Verificação de tentativas de reset', [
            'user_id' => $this->id,
            'current_attempts' => $this->reset_attempts,
            'can_attempt' => $canAttempt,
            'reset_at' => $this->reset_attempts_reset_at
        ]);

        return $canAttempt;
    }

    public function incrementResetAttempts(): void
    {
        $this->increment('reset_attempts');

        if (!$this->reset_attempts_reset_at) {
            $this->update(['reset_attempts_reset_at' => Carbon::now()->addHour()]);
        }

        Log::info('Tentativas de reset incrementadas', [
            'user_id' => $this->id,
            'new_attempts' => $this->reset_attempts + 1,
            'reset_at' => $this->reset_attempts_reset_at
        ]);
    }

    public function clearResetData(): void
    {
        $this->update([
            'reset_token' => null,
            'reset_token_expires_at' => null,
            'reset_attempts' => 0,
            'reset_attempts_reset_at' => null,
        ]);

        Log::info('Dados de reset limpos', ['user_id' => $this->id]);
    }

    public static function isEmailBlocked(string $email): bool
    {
        $user = self::where('email', $email)->first();
        if (!$user) return false;

        $isBlocked = !$user->canAttemptReset();

        Log::info('Verificação de bloqueio de email', [
            'email' => $email,
            'user_id' => $user->id,
            'is_blocked' => $isBlocked
        ]);

        return $isBlocked;
    }

    public function getBlockTimeRemaining(): ?int
    {
        if (!$this->reset_attempts_reset_at) return null;

        $minutesRemaining = $this->reset_attempts_reset_at->diffInMinutes(Carbon::now());

        Log::info('Tempo restante de bloqueio', [
            'user_id' => $this->id,
            'minutes_remaining' => $minutesRemaining
        ]);

        return $minutesRemaining;
    }

    // ============================================================
    // MÉTODOS DE VERIFICAÇÃO DE PERMISSÕES (MODERAÇÃO)
    // ============================================================

    /**
     * Verificar se usuário é staff (moderador ou admin)
     */
    public function isStaff()
    {
        return in_array($this->nivel_usuario, ['moderador', 'admin']);
    }

    /**
     * Verificar se é moderador
     */
    public function isModerador()
    {
        return $this->nivel_usuario === 'moderador';
    }

    /**
     * Verificar se é admin
     */
    public function isAdmin()
    {
        return $this->nivel_usuario === 'admin';
    }

    /**
     * Verificar se pode moderar tickets
     */
    public function podeModerar()
    {
        return $this->isStaff();
    }

    // ============================================================
    // RELACIONAMENTOS - MODERAÇÃO
    // ============================================================

    /**
     * 🆕 Relacionamento com device fingerprints
     */
    public function deviceFingerprints()
    {
        return $this->hasMany(DeviceFingerprint::class, 'usuario_id');
    }

    /**
     * 🆕 Moderador que aplicou warning
     */
    public function warningAplicadoPor()
    {
        return $this->belongsTo(Usuario::class, 'warning_aplicado_por');
    }

    /**
     * 🆕 Moderador que aplicou ban
     */
    public function banAplicadoPor()
    {
        return $this->belongsTo(Usuario::class, 'ban_aplicado_por');
    }

    /**
     * 🆕 Admin que aplicou IP ban
     */
    public function ipBanAplicadoPor()
    {
        return $this->belongsTo(Usuario::class, 'ip_ban_aplicado_por');
    }

    /**
     * 🆕 Moderador que deletou a conta
     */
    public function accountDeletedPor()
    {
        return $this->belongsTo(Usuario::class, 'account_deleted_por');
    }

    /**
     * 🆕 Warnings aplicados por este usuário (se for staff)
     */
    public function warningsAplicados()
    {
        return $this->hasMany(Usuario::class, 'warning_aplicado_por');
    }

    /**
     * 🆕 Bans aplicados por este usuário (se for staff)
     */
    public function bansAplicados()
    {
        return $this->hasMany(Usuario::class, 'ban_aplicado_por');
    }

    // ============================================================
    // RELACIONAMENTOS - COMUNIDADE
    // ============================================================

    public function posts()
    {
        return $this->hasMany(Post::class, 'usuario_id', 'id');
    }

    public function saved_posts()
    {
        return $this->hasMany(SavedPost::class, 'usuario_id', 'id')
            ->with('post');
    }

    public function curtidas()
    {
        return $this->hasMany(Like::class, 'usuario_id', 'id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comment::class, 'usuario_id', 'id');
    }

    public function seguidores()
    {
        return $this->hasMany(UserFollower::class, 'seguido_id', 'id')
            ->with('seguidor');
    }

    public function seguindo()
    {
        return $this->hasMany(UserFollower::class, 'seguidor_id', 'id')
            ->with('seguido');
    }

    public function posts_salvos()
    {
        return $this->belongsToMany(
            Post::class,
            'saved_posts',
            'usuario_id',
            'post_id'
        )->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(
            Usuario::class,
            'user_followers',
            'seguido_id',
            'seguidor_id',
            'id',
            'id'
        )->as('follower')
         ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(
            Usuario::class,
            'user_followers',
            'seguidor_id',
            'seguido_id',
            'id',
            'id'
        )->as('following')
         ->withTimestamps();
    }

    // ============================================================
    // RELACIONAMENTOS - SUPORTE
    // ============================================================

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'usuario_id');
    }

    public function ticketsAtribuidos()
    {
        return $this->hasMany(Ticket::class, 'atribuido_a');
    }

    public function denunciasRecebidas()
    {
        return $this->hasMany(Ticket::class, 'usuario_denunciado_id')
            ->where('categoria', 'denuncia');
    }

    public function notificacoesTickets()
    {
        return $this->hasMany(TicketNotificacao::class, 'usuario_id');
    }

    public function notificacoesTicketsNaoLidas()
    {
        return $this->notificacoesTickets()->where('lida', false);
    }

    public function getTotalNotificacoesNaoLidas()
    {
        return $this->notificacoesTicketsNaoLidas()->count();
    }

    // ============================================================
    // ATRIBUTOS COMPUTADOS
    // ============================================================

    public function getNameAttribute()
    {
        return $this->nickname ?: $this->username;
    }
}