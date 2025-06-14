<?php

namespace App\Models;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;
    // protected $casts = [
    //     'last_date' => 'date', // This casts 'last_date' to a Carbon instance
    // ];
    public function scopeActive($query)
    {
        return $query->where('status', 1)
                 ->where(function ($query) {
                     $query->whereNull('last_date') // Jobs without a last date
                           ->orWhere('last_date', '>=', Carbon::today()); // Jobs with a last date that is not expired
                 });
    }
    protected $fillable = [
        'title',
        'slug',
        'company_id',
        'service_line',
        'program',
        'location',
        'last_date',
        'job_link',
        'details',
    ];

     // Define the relationship with the Company model
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
