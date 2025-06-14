<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'session_id', 'user_id', 'subtotal', 'tax', 
        'total', 'currency', 'coupon_code', 'discount'
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recalculateTotal()
    {
        $subtotal = $this->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $tax = $subtotal * (config('cart.tax_rate', 0.10)); // 10% tax by default
        $total = $subtotal + $tax - $this->discount;

        $this->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => max(0, $total)
        ]);
    }
}
