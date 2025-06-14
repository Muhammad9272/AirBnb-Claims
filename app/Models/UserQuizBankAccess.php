<?php

namespace App\Models;

use App\Models\QuizBankManagement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizBankAccess extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'company_id', 'quiz_bank_management_id', 'position'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function quizBankManagement()
    {
        return $this->belongsTo(QuizBankManagement::class,'quiz_bank_management_id');
    }
}
