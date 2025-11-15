<?php

// ========== SuporteConfiguracao.php ==========

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SuporteConfiguracao extends Model
{
    use HasFactory;

    protected $table = 'suporte_configuracoes';

    protected $fillable = [
        'chave',
        'valor',
        'tipo',
        'descricao'
    ];

    /**
     * Obter valor de configuração
     */
    public static function getValor($chave, $padrao = null)
    {
        $config = Cache::remember("suporte_config_{$chave}", 3600, function () use ($chave) {
            return self::where('chave', $chave)->first();
        });

        if (!$config) {
            return $padrao;
        }

        return self::converterTipo($config->valor, $config->tipo);
    }

    /**
     * Definir valor de configuração
     */
    public static function setValor($chave, $valor, $tipo = 'string')
    {
        $config = self::updateOrCreate(
            ['chave' => $chave],
            ['valor' => $valor, 'tipo' => $tipo]
        );

        Cache::forget("suporte_config_{$chave}");

        return $config;
    }

    /**
     * Converter valor para o tipo correto
     */
    private static function converterTipo($valor, $tipo)
    {
        switch ($tipo) {
            case 'int':
            case 'integer':
                return (int) $valor;
            
            case 'boolean':
            case 'bool':
                return filter_var($valor, FILTER_VALIDATE_BOOLEAN);
            
            case 'json':
            case 'array':
                return json_decode($valor, true);
            
            case 'float':
            case 'double':
                return (float) $valor;
            
            default:
                return $valor;
        }
    }
}