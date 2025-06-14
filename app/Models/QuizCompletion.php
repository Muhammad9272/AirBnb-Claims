<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizCompletion extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'quizbankmanagement_id',
        'quiz_id',
        'time_spent',
        'quiz_group',
    ];

}
