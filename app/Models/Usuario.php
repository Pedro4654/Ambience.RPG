<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        'data_criacao'
        // REMOVIDO: data_ultimo_login (conforme solicitado)
    ];

    protected $hidden = [
        'senha_hash',
        'remember_token',
    ];

    protected $casts = [
        'data_criacao' => 'datetime',
        'data_atualizacao' => 'datetime',
        'data_de_nascimento' => 'date',
        'verificado' => 'boolean',
        // REMOVIDO: data_ultimo_login (conforme solicitado)
    ];

    public function getAuthPassword()
    {
        return $this->senha_hash;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['senha_hash'] = Hash::make($password);
    }

    // MÉTODO CORRIGIDO - Método para obter URL completa do avatar
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

    // MÉTODO CORRIGIDO - Método para deletar avatar antigo
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
}
