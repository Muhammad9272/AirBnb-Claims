<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorReport extends Model
{
    use HasFactory;

    protected $fillable = ['error_message', 'page_url', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
