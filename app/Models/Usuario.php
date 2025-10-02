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
        // ============ NOVOS CAMPOS PARA RECUPERAÇÃO COM TOKEN DE 6 DÍGITOS ============
        'reset_token',
        'reset_token_expires_at',
        'reset_attempts',
        'reset_attempts_reset_at',
        'is_online',
        'last_seen',
        'current_room'
    ];

    protected $hidden = [
        'senha_hash',
        'remember_token',
        'reset_token', // ← Importante: nunca expor o token de 6 dígitos
    ];

    protected $casts = [
        'data_criacao' => 'datetime',
        'data_atualizacao' => 'datetime',
        'data_de_nascimento' => 'date',
        'verificado' => 'boolean',
        // ============ NOVOS CASTS PARA RECUPERAÇÃO DE SENHA ============
        'reset_token_expires_at' => 'datetime',
        'reset_attempts_reset_at' => 'datetime',
        'reset_attempts' => 'integer',
        'is_online' => 'boolean',
        'last_seen' => 'datetime'
    ];

    public function getAuthPassword()
    {
        return $this->senha_hash;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['senha_hash'] = Hash::make($password);
    }

    // Métodos para status online
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

    // Scope para usuários online
    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    // Scope para usuários online em uma sala específica
    public function scopeOnlineInRoom($query, $roomId)
    {
        return $query->where('is_online', true)
            ->where('current_room', $roomId);
    }

    // ============ MÉTODOS DE AVATAR (MANTIDOS DO SEU ORIGINAL) ============

    /**
     * MÉTODO CORRIGIDO - Método para obter URL completa do avatar
     */
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

        // Avatar padrão caso não tenha foto
        $defaultUrl = asset('images/default-avatar.png');
        Log::info('Usando avatar padrão', ['url' => $defaultUrl]);
        return $defaultUrl;
    }

    /**
     * MÉTODO CORRIGIDO - Método para deletar avatar antigo
     */
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

    // ============ NOVOS MÉTODOS PARA RECUPERAÇÃO DE SENHA COM TOKEN DE 6 DÍGITOS ============

    /**
     * Gerar token de 6 dígitos numéricos único
     */
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

    /**
     * Definir token de recuperação
     */
    public function setResetToken(): string
    {
        $token = $this->generateResetToken();

        $this->update([
            'reset_token' => $token,
            'reset_token_expires_at' => Carbon::now()->addMinutes(15), // 15 minutos de validade
            'reset_attempts' => 0, // Resetar contador de tentativas
            'reset_attempts_reset_at' => Carbon::now()->addHour(), // Reset contador após 1 hora
        ]);

        Log::info('Token de reset definido', [
            'user_id' => $this->id,
            'email' => $this->email,
            'expires_at' => $this->reset_token_expires_at
        ]);

        return $token;
    }

    /**
     * Verificar se token é válido
     */
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

    /**
     * Verificar se pode tentar reset (máximo 5 tentativas por hora)
     */
    public function canAttemptReset(): bool
    {
        // Se já passou 1 hora, resetar contador
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

    /**
     * Incrementar tentativas de reset
     */
    public function incrementResetAttempts(): void
    {
        $this->increment('reset_attempts');

        // Definir tempo de reset se não existe
        if (!$this->reset_attempts_reset_at) {
            $this->update(['reset_attempts_reset_at' => Carbon::now()->addHour()]);
        }

        Log::info('Tentativas de reset incrementadas', [
            'user_id' => $this->id,
            'new_attempts' => $this->reset_attempts + 1,
            'reset_at' => $this->reset_attempts_reset_at
        ]);
    }

    /**
     * Limpar dados de reset após sucesso
     */
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

    /**
     * Verificar se email tem bloqueio temporário
     */
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

    /**
     * Obter tempo restante do bloqueio
     */
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

    // ============ MÉTODO PARA COMPATIBILIDADE COM LARAVEL AUTH ============

    /**
     * Get the name that can be displayed to represent the user.
     */
    public function getNameAttribute()
    {
        return $this->nickname ?: $this->username;
    }
}
