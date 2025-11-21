<?php
// Arquivo: app/Models/OwlbearGame.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OwlbearGame extends Model
{
    protected $table = 'owlbear_games';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'sessao_id',
        'password',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function sessao()
    {
        return $this->belongsTo(Sessao::class, 'sessao_id');
    }

    public function maps()
    {
        return $this->hasMany(OwlbearMap::class, 'game_id');
    }

    public function tokens()
    {
        return $this->hasManyThrough(OwlbearToken::class, OwlbearMap::class, 'game_id', 'map_id');
    }
}
