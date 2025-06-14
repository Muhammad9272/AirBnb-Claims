<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NostalgiaItem extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'category_id',
        'subcategory_id', 'childcategory_id',  // Changed from 'photo' to 'main_image'
         'tags', 'release_year', 'views', 'status',
        'specifications', 'external_resources'
    ];

    protected $casts = [
        'gallery' => 'array',
        'specifications' => 'array',
        'external_resources' => 'array',
        'tags' => 'array', // changed from 'string' to 'array'
        // removed tags from array casting
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function category()
    {
        return $this->belongsTo(NostalgiaCategory::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(NostalgiaCategory::class, 'subcategory_id');
    }

    public function childcategory()
    {
        return $this->belongsTo(NostalgiaCategory::class, 'childcategory_id');
    }


    // Add accessor methods for tags and checks
    public function getFormattedTagsAttribute()
    {
        if (!$this->tags) return '';
        $tags = is_string($this->tags) ? json_decode($this->tags, true) : $this->tags;
        return is_array($tags) ? implode(',', $tags) : $tags;
    }

    public function getFormattedSpecificationsAttribute()
    {
        return is_string($this->specifications) ? json_decode($this->specifications, true) : ($this->specifications ?? []);
    }

    public function getFormattedExternalResourcesAttribute()
    {
        return is_string($this->external_resources) ? json_decode($this->external_resources, true) : ($this->external_resources ?? []);
    }

    public function getFormattedResourcesAttribute()
    {
        $resources = [];
        if ($this->external_resources) {
            $data = is_string($this->external_resources) ? 
                   json_decode($this->external_resources, true) : 
                   $this->external_resources;

            foreach ($data as $key => $value) {
                $resources[$key] = is_array($value) ? implode(',', $value) : $value;
            }
        }
        return $resources;
    }
}
