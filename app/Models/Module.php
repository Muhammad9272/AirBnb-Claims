<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
     public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    public function course($value='')
    {
        return $this->belongsTo('App\Models\Course','course_id');
    }

    public function gallery($value='')
    {
        return $this->hasMany('App\Models\CourseGallery','module_id');
    }
}
