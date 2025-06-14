<?php

namespace App\Models;

use App\Models\Favorite;
use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    protected $fillable = [
        'name', 'slug', 'small_description', 'details', 'tags', 'application_process','position_details','sample_question'
    ];

    public function favorited_by($user)
    {
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }

    public function quizBankManagements()
    {
        return $this->hasMany(QuizBankManagement::class,'company_id');
    }

    public function jobListings()
    {
        return $this->hasMany(JobListing::class, 'company_id');
    }
}
