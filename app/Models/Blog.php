<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title', 'slug', 'category_id', 'summary', 'content',
         'tags', 'views', 'featured'
    ];

    protected $casts = [
        'tags' => 'array',
        'featured' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function getFormattedTagsAttribute()
    {
        return is_array($this->tags) ? implode(',', $this->tags) : '';
    }
}
