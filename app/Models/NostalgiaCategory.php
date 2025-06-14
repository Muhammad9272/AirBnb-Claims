<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NostalgiaCategory extends Model
{
    
    protected $fillable = [
        'name', 'slug', 'description', 
        'parent_id', 'level'
    ];

     public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function parent()
    {
        return $this->belongsTo(NostalgiaCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NostalgiaCategory::class, 'parent_id');
    }

    public function items()
    {
        return $this->hasMany(NostalgiaItem::class, 'category_id');
    }

    public function scopeMain($query)
    {
        return $query->where('level', 1);
    }

    public function scopeSub($query)
    {
        return $query->where('level', 2);
    }

    public function scopeChild($query)
    {
        return $query->where('level', 3);
    }
}
