<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
        'gateway_order_id',
        'payment_response',
        'paid_at',
    ];

    protected $casts = [
        'payment_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}