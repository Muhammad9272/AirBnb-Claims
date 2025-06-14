<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favoriteable_type',
        'favoriteable_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function favoriteable()
    {
        return $this->morphTo();
    }
}
