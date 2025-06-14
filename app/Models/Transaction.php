<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'payload'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payload' => 'array'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User')->first();
    }

    public function subscription() {
        return $this->belongsTo('App\Models\Subscriptions', 'subscriptions_id')->first();
    }

    public function relateduser()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
