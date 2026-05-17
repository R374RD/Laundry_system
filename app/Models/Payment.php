<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'amount', 'method', 'remarks', 'received_by'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
