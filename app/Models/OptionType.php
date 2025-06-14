<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'status'
    ];
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }
}
