<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUSES = [
        'pending' => 'Pending',
        'washing' => 'Washing',
        'drying' => 'Drying',
        'ironing' => 'Ironing',
        'ready_for_pickup' => 'Ready for Pickup',
        'claimed' => 'Claimed',
    ];

    protected $fillable = [
        'order_number',
        'branch_id',
        'user_id',
        'customer_id',
        'customer_name',
        'customer_contact',
        'customer_email',
        'weight_kg',
        'price_per_kilo',
        'subtotal',
        'add_on_total',
        'total_amount',
        'paid_amount',
        'payment_status',
        'status',
        'notes',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
        'price_per_kilo' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'add_on_total' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function addOns()
    {
        return $this->belongsToMany(AddOnService::class, 'order_add_on')->withPivot('price')->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function balance(): float
    {
        return max(0, (float) $this->total_amount - (float) $this->paid_amount);
    }
}
