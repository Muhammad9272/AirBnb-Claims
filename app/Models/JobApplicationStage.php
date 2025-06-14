<?php

namespace App\Models;

use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplicationStage extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_application_id',
        'stage_name',
        'status',
        'last_date',
        'details',
        'admin_docs',
        'user_docs_required'
        // Add other fields as needed
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }
}