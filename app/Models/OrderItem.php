<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'price',
        'unit_price',
        'options_price',
        'options',
        'variations'
    ];

    protected $casts = [
        'options' => 'array',
        'variations' => 'array',
        'price' => 'float',
        'unit_price' => 'float',
        'options_price' => 'float'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function createFromCartItem(CartItem $cartItem)
    {
        // Ensure variations and options are properly formatted
        $variations = is_string($cartItem->variations) ? json_decode($cartItem->variations, true) : $cartItem->variations;
        $options = is_string($cartItem->options) ? json_decode($cartItem->options, true) : $cartItem->options;

        return new static([
            'product_id' => $cartItem->product_id,
            'product_name' => $cartItem->product->name,
            'product_sku' => $cartItem->product->sku,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->price,
            'unit_price' => $cartItem->unit_price,
            'options_price' => $cartItem->options_price,
            'options' => $options,
            'variations' => $variations
        ]);
    }

    public function getFormattedVariations()
    {
        if (!$this->variations || !$this->options) {
            return null;
        }

        $variations = is_string($this->variations) ? json_decode($this->variations, true) : $this->variations;
        $options = is_string($this->options) ? json_decode($this->options, true) : $this->options;
        
        $formatted = [];
        foreach ($variations as $variation) {
            if (isset($options[$variation['option_type_id']])) {
                $formatted[] = [
                    'name' => $variation['option_type_name'],
                    'value' => $options[$variation['option_type_id']]
                ];
            }
        }
        
        return $formatted;
    }
}
