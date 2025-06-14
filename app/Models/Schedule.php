<?php

namespace App\Models;

use App\Models\AvailableDays;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

     public function availableDays()
    {
        return $this->hasMany(AvailableDays::class,'schedule_id');
    }
}
