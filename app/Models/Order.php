<?php

namespace App\Models;

use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'tax',
        'shipping',
        'discount',
        'bits_discount',
        'bits_used',
        'total',
        'currency',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zipcode',
        'shipping_country',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zipcode',
        'billing_country',
        'notes',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'discount' => 'decimal:2',
        'bits_discount' => 'decimal:2',
        'total' => 'decimal:2',
        'bits_used' => 'integer',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class,'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    // Update transaction relationship to hasOne since each order has only one transaction
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
    
    // Keep the original transactions relationship for backward compatibility
    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class);
    // }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD-';
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())
            ->latest()
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'order_number';
    }

    // Add a method to calculate grand total with both discounts
    public function getGrandTotalAttribute() 
    {
        // Calculate total after all discounts
        $totalAfterDiscount = $this->subtotal + $this->tax + $this->shipping - $this->discount - $this->bits_discount;
        
        // Ensure the total isn't negative
        return max(0, $totalAfterDiscount);
    }
}
