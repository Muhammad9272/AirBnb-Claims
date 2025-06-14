<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'option_type_id', 'status'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function optionType()
    {
        return $this->belongsTo(OptionType::class);
    }

    public function values()
    {
        return $this->hasMany(ProductOptionValue::class);
    }
}
