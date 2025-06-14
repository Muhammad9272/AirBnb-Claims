<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id', 
        'product_id', 
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

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function beforeSave()
    {
        $this->unit_price = $this->product->discount_price ?? $this->product->price;
        $this->options_price = $this->calculateOptionsPrice();
        $this->price = $this->unit_price + $this->options_price;
        
        return $this;
    }

    protected function calculateOptionsPrice()
    {
        if (!$this->options || !$this->variations) {
            return 0;
        }

        $variations = is_string($this->variations) ? json_decode($this->variations, true) : $this->variations;
        $options = is_string($this->options) ? json_decode($this->options, true) : $this->options;
        
        $additionalPrice = 0;
        foreach ($variations as $variation) {
            if (isset($options[$variation['option_type_id']])) {
                foreach ($variation['values'] as $value) {
                    if ($value['value'] == $options[$variation['option_type_id']]) {
                        $additionalPrice += floatval($value['additional_price']);
                    }
                }
            }
        }

        return $additionalPrice;
    }
}
