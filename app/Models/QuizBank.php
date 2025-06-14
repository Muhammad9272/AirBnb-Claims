<?php

namespace App\Models;

use App\Models\QuizBankManagement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizBank extends Model
{
    use HasFactory;
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    protected $fillable = [
        'title',
        'quizbankmanagement_id',
        'quiz_group',
        'slug',
        'question_type',
        'game_id',
        'free_demo_pages',
        // 'resourses',
        // 'media',
        'details',
        // 'options',
        'suggested_answer',
        'prepare_time',
        'response_time',
        // 'promotion_photo',
        'promotion_link',
        'status',
    ];

    public function quizBankManagement()
    {
        return $this->belongsTo(QuizBankManagement::class,'quizbankmanagement_id');
    }
}
