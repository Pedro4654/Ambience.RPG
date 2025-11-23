<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ChatAnexo extends Model
{
    use HasFactory;

    protected $table = 'chat_anexos';

    protected $fillable = [
        'mensagem_id',
        'usuario_id',
        'nome_original',
        'nome_arquivo',
        'caminho',
        'tipo_mime',
        'tamanho',
        'hash_arquivo',
        'nsfw_detectado',
        'nsfw_scores'
    ];

    protected $casts = [
        'tamanho' => 'integer',
        'nsfw_detectado' => 'boolean',
        'nsfw_scores' => 'array'
    ];

    public function mensagem()
    {
        return $this->belongsTo(MensagemChat::class, 'mensagem_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Obter URL completa do arquivo
     */
    public function getUrl()
    {
        return Storage::url($this->caminho);
    }

    /**
     * Obter tamanho formatado
     */
    public function getTamanhoFormatado()
    {
        $bytes = $this->tamanho;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }

    /**
     * Verificar se é imagem
     */
    public function ehImagem()
    {
        $extensao = strtolower(pathinfo($this->nome_original, PATHINFO_EXTENSION));
        return in_array($extensao, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
    }

    /**
     * Deletar arquivo ao deletar registro
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($anexo) {
            if (Storage::disk('public')->exists($anexo->caminho)) {
                Storage::disk('public')->delete($anexo->caminho);
            }
        });
    }
}