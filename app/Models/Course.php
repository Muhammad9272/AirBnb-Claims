<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
     public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    public function modules($value='')
    {
        return $this->hasMany('App\Models\Module','course_id');
    }
}
