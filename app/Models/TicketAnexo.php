<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TicketAnexo extends Model
{
    use HasFactory;

    protected $table = 'ticket_anexos';

    protected $fillable = [
        'ticket_id',
        'usuario_id',
        'resposta_id',
        'nome_original',
        'nome_arquivo',
        'caminho',
        'tipo_mime',
        'tamanho',
        'hash_arquivo'
    ];

    protected $casts = [
        'tamanho' => 'integer'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function resposta()
    {
        return $this->belongsTo(TicketResposta::class, 'resposta_id');
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
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Obter extensão do arquivo
     */
    public function getExtensao()
    {
        return strtolower(pathinfo($this->nome_original, PATHINFO_EXTENSION));
    }

    /**
     * Verificar se é imagem
     */
    public function ehImagem()
    {
        $extensao = $this->getExtensao();
        return in_array($extensao, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
    }

    /**
     * Verificar se é vídeo
     */
    public function ehVideo()
    {
        $extensao = $this->getExtensao();
        return in_array($extensao, ['mp4', 'webm', 'mov', 'avi', 'mkv', 'flv']);
    }

    /**
     * Verificar se é modelo 3D (GLB)
     */
    public function ehModelo3D()
    {
        $extensao = $this->getExtensao();
        return $extensao === 'glb';
    }

    /**
     * Verificar se é PDF
     */
    public function ehPDF()
    {
        $extensao = $this->getExtensao();
        return $extensao === 'pdf';
    }

    /**
     * Verificar se é documento do Word
     */
    public function ehWord()
    {
        $extensao = $this->getExtensao();
        return in_array($extensao, ['doc', 'docx']);
    }

    /**
     * Verificar se é planilha
     */
    public function ehPlanilha()
    {
        $extensao = $this->getExtensao();
        return in_array($extensao, ['xls', 'xlsx', 'csv']);
    }

    /**
     * Verificar se é apresentação
     */
    public function ehApresentacao()
    {
        $extensao = $this->getExtensao();
        return in_array($extensao, ['ppt', 'pptx']);
    }

    /**
     * Verificar se é arquivo de texto
     */
    public function ehTexto()
    {
        $extensao = $this->getExtensao();
        return in_array($extensao, ['txt', 'log', 'md']);
    }

    /**
     * Verificar se é arquivo compactado
     */
    public function ehCompactado()
    {
        $extensao = $this->getExtensao();
        return in_array($extensao, ['zip', 'rar', '7z', 'tar', 'gz']);
    }

    /**
     * Obter ícone baseado no tipo de arquivo
     */
    public function getIcone()
    {
        if ($this->ehImagem()) return '🖼️';
        if ($this->ehVideo()) return '🎥';
        if ($this->ehModelo3D()) return '🎲';
        if ($this->ehPDF()) return '📄';
        if ($this->ehWord()) return '📝';
        if ($this->ehPlanilha()) return '📊';
        if ($this->ehApresentacao()) return '📽️';
        if ($this->ehTexto()) return '📃';
        if ($this->ehCompactado()) return '🗜️';
        
        return '📎';
    }

    /**
     * Obter tipo legível do arquivo
     */
    public function getTipoLegivel()
    {
        if ($this->ehImagem()) return 'Imagem';
        if ($this->ehVideo()) return 'Vídeo';
        if ($this->ehModelo3D()) return 'Modelo 3D';
        if ($this->ehPDF()) return 'PDF';
        if ($this->ehWord()) return 'Documento Word';
        if ($this->ehPlanilha()) return 'Planilha';
        if ($this->ehApresentacao()) return 'Apresentação';
        if ($this->ehTexto()) return 'Texto';
        if ($this->ehCompactado()) return 'Arquivo Compactado';
        
        return 'Arquivo';
    }

    /**
     * Obter cor do tipo de arquivo (para badges)
     */
    public function getCorTipo()
    {
        if ($this->ehImagem()) return '#3b82f6'; // Azul
        if ($this->ehVideo()) return '#8b5cf6'; // Roxo
        if ($this->ehModelo3D()) return '#06b6d4'; // Cyan
        if ($this->ehPDF()) return '#ef4444'; // Vermelho
        if ($this->ehWord()) return '#2563eb'; // Azul escuro
        if ($this->ehPlanilha()) return '#10b981'; // Verde
        if ($this->ehApresentacao()) return '#f59e0b'; // Laranja
        if ($this->ehTexto()) return '#6b7280'; // Cinza
        if ($this->ehCompactado()) return '#7c3aed'; // Roxo escuro
        
        return '#9ca3af'; // Cinza padrão
    }

    /**
     * Verificar se pode ter preview inline
     */
    public function podePreview()
    {
        return $this->ehImagem() || $this->ehVideo() || $this->ehModelo3D() || $this->ehPDF() || $this->ehTexto();
    }

    /**
     * Deletar arquivo do storage ao deletar registro
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