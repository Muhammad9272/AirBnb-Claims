<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_option_id', 'value', 'additional_price', 'status'
    ];

    public function productOption()
    {
        return $this->belongsTo(ProductOption::class);
    }
}
