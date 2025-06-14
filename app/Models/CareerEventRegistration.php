<?php

namespace App\Models;

use App\Models\CareerEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerEventRegistration extends Model
{
    use HasFactory;

    public function careerEvent()
    {
      return $this->belongsTo(CareerEvent::class,'event_id');
    }

    public function user()
    {
      return $this->belongsTo(User::class,'user_id');
    }
}
