<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseGallery extends Model
{
    use HasFactory;
     public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    public function module($value='')
    {
        return $this->belongsTo('App\Models\Module','module_id');
    }
}
