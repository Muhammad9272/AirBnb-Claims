<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'bit_value',
        'status',
        'required_proof',
        'max_submissions',
        'total_submissions'
    ];
     public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function submissions()
    {
        return $this->hasMany(BitSubmission::class);
    }

    public function getPendingSubmissionsCountAttribute()
    {
        return $this->submissions()->where('status', 'pending')->count();
    }

    public function getApprovedSubmissionsCountAttribute()
    {
        return $this->submissions()->where('status', 'approved')->count();
    }

}
