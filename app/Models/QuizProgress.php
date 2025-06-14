<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizProgress extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','quizbankmanagement_id', 'quiz_id', 'is_read'];

    public function quizBank()
    {
        return $this->belongsTo(QuizBank::class,'quiz_id');
    }
}
