<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MODEL: Ficha RPG
 * Armazena dados de fichas de personagem RPG
 */
class FichaRpg extends Model {
    
    protected $table = 'fichas_rpg';
    
    protected $fillable = [
        'post_id',
        'nome',
        'nivel',
        'raca',
        'classe',
        'foto_url',
        'forca',
        'agilidade',
        'vigor',
        'inteligencia',
        'sabedoria',
        'carisma',
        'habilidades',
        'historico',
    ];
    
    protected $casts = [
        'nivel' => 'integer',
        'forca' => 'integer',
        'agilidade' => 'integer',
        'vigor' => 'integer',
        'inteligencia' => 'integer',
        'sabedoria' => 'integer',
        'carisma' => 'integer',
    ];
    
    /**
     * Relacionamento com Post
     */
    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }
    
    /**
     * Accessor: URL completa da foto
     */
    public function getFotoUrlAttribute($value) {
        if (!$value) return null;
        return asset('storage/' . $value);
    }
    
    /**
     * Calcular total de atributos
     */
    public function getTotalAtributosAttribute() {
        return $this->forca + 
               $this->agilidade + 
               $this->vigor + 
               $this->inteligencia + 
               $this->sabedoria + 
               $this->carisma;
    }
    
    /**
     * Calcular modificador de atributo (D&D style)
     */
    public static function calcularModificador($valor) {
        return floor(($valor - 10) / 2);
    }
}