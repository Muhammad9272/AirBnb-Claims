<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerEvent extends Model
{
    use HasFactory;
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    protected $fillable = [
        'name',
        'host_name',
        'meeting_id',
        'details',
        'price',
        'event_type',
    ];

    public function CareerEventRegistration()
    {
      return $this->hasMany(CareerEventRegistration::class,'event_id');
    }
}
