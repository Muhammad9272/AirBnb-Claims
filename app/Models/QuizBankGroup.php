<?php

namespace App\Models;

use App\Models\QuizBankManagement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizBankGroup extends Model
{
    use HasFactory;
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    protected $fillable = [
        'name',
        'quiz_type',
        'quizbankmanagement_id',
    ];

    public function quizBankManagement()
    {
        return $this->belongsTo(QuizBankManagement::class,'quizbankmanagement_id');
    }
}
