<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'user_id',
        'comment',
        'is_admin',
        'is_private'
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_private' => 'boolean'
    ];

    /**
     * Get the claim that owns the comment.
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get the user that made the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
