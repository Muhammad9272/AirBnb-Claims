<?php

namespace App\Models;

use App\Models\Company;
use App\Models\JobApplicationStage;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    protected $fillable = [
        'user_id',
        'jobs_applied',
        'company_id',
        'service_line',
        'location',
        'job_id',
        'instruction_form',
        'resume',
        'motivation_letter',
        // 'status',
        // Add other fields as needed
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function job()
    {
        return $this->belongsTo(JobListing::class, 'job_id');
    }

    // Add other relationships as needed, for example, stages
    public function stages()
    {
        return $this->hasMany(JobApplicationStage::class);
    }
    
}
        