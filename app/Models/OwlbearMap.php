<?php
// Arquivo: app/Models/OwlbearMap.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OwlbearMap extends Model
{
    protected $table = 'owlbear_maps';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'game_id',
        'owner',
        'name',
        'thumbnail',
        'width',
        'height',
        'grid_x',
        'grid_y',
        'grid_type',
        'grid',
        'show_grid',
        'snap_to_grid',
        'fog',
        'quality',
    ];

    protected $casts = [
        'grid' => 'array',
        'fog' => 'array',
        'quality' => 'array',
        'show_grid' => 'boolean',
        'snap_to_grid' => 'boolean',
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

    public function game()
    {
        return $this->belongsTo(OwlbearGame::class, 'game_id');
    }

    public function tokens()
    {
        return $this->hasMany(OwlbearToken::class, 'map_id');
    }
}