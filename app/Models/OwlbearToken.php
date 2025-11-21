<?php
// Arquivo: app/Models/OwlbearToken.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OwlbearToken extends Model
{
    protected $table = 'owlbear_tokens';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'map_id',
        'owner',
        'name',
        'category',
        'file',
        'thumbnail',
        'width',
        'height',
        'x',
        'y',
        'rotation',
        'locked',
        'visible',
        'outline_color',
        'label',
        'statuses',
        'layer',
    ];

    protected $casts = [
        'label' => 'array',
        'statuses' => 'array',
        'locked' => 'boolean',
        'visible' => 'boolean',
        'x' => 'float',
        'y' => 'float',
        'rotation' => 'float',
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

    public function map()
    {
        return $this->belongsTo(OwlbearMap::class, 'map_id');
    }
}