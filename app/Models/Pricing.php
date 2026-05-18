<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $table = 'pricing';

    protected $fillable = [
        'price_per_load',
        'max_kilo_per_load',
        'is_active',
    ];

    protected $casts = [
        'price_per_load' => 'decimal:2',
        'max_kilo_per_load' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}