<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';
    public $timestamps = false;
    // Configurar os nomes dos timestamps da sua tabela
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';

    protected $fillable = [
    'username', 'nickname', 'email', 'senha_hash', 'nome_completo',
    'avatar_url', 'bio', 'data_de_nascimento', 'status', 'nivel_usuario',
    'data_criacao'
    ];

    protected $hidden = [
        'senha_hash', 'remember_token',
    ];

    protected $casts = [
        'data_criacao' => 'datetime',
        'data_atualizacao' => 'datetime',
        'data_ultimo_login' => 'datetime',
        'data_de_nascimento' => 'date',
        'verificado' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->senha_hash;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['senha_hash'] = Hash::make($password);
    }
}