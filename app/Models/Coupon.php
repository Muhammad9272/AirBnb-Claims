<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    protected $fillable = [
        'coupon_code',
        'coupon_name',
        'discount',
        'earnings',
        'start_date',
        'end_date',
        'usage_count',
        'max_usage_count',
       
    ];
}
