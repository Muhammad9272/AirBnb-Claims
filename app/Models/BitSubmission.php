<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bit_task_id',
        'submission_content',
        'proof',
        'status',
        'admin_notes',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(BitTask::class, 'bit_task_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getProofUrlAttribute()
    {
        if (!$this->proof) return null;
        return asset('storage/bit-submissions/' . $this->proof);
    }
}
