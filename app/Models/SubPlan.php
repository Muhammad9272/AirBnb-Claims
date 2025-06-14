<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPlan extends Model
{
    use HasFactory;
     protected $fillable=['name','price','interval','details','features','is_featured','free_trial'];
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
}
