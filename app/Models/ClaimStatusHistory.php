<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'from_status',
        'to_status',
        'user_id',
        'notes'
    ];

    /**
     * Get the claim that owns the status history record.
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get the user that created the status history record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
