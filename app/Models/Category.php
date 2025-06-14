<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable=['name','details'];
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

     public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
