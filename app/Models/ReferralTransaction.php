<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_user_id',
        'referee_user_id',
        'subscription_id',
        'credit_amount',
        'status',
    ];

    // protected $casts = [
    //     'credit_amount' => 'float:2',
    // ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_user_id');
    }

    public function referee()
    {
        return $this->belongsTo(User::class, 'referee_user_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
