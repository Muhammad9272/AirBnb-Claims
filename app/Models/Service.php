<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title', 'slug', 'category_id', 'summary', 'content',
        'price', 'items', 'gallery', 'status'
    ];

    protected $casts = [
        'items' => 'array',
        'gallery' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
