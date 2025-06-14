<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public function student()
    {
      return $this->belongsTo(User::class,'student_id');
    }
    public function tutor()
    {
      return $this->belongsTo(User::class,'tutor_id');
    }
}
