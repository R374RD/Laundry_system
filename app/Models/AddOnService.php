<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddOnService extends Model
{
    protected $fillable = ['name', 'price', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_add_on')->withPivot('price')->withTimestamps();
    }
}
