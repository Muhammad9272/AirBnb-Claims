<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Favorite;
use App\Models\QuizBank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizBankManagement extends Model
{
    use HasFactory;
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    protected $fillable = [
        'name',
        'company_id',
        'position',
        'assessment_stage',
        'program',
        'location',
        'details',
        'quiz_group_names',
        'price',
        'assessment_type',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function quizBanks()
    {
        return $this->hasMany(QuizBank::class,'quizbankmanagement_id');
    }

    public function favorited_by($user)
    {
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }

    // public function subPlans()
    // {
    //     return $this->belongsToMany(SubPlan::class, 'quiz_bank_sub_plans', 'quiz_bank_id', 'sub_plan_id');
    // }
}
