<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $table = 'pricing';

    protected $fillable = ['price_per_kilo', 'is_active'];

    protected $casts = [
        'price_per_kilo' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
