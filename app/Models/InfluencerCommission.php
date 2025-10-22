<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfluencerCommission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'influencer_user_id',
        'customer_user_id',
        'claim_id',
        'subscription_id',
        'estimated_commission',
        'commission_amount',
        'status',
        'commission_date',
        'paid_date',
        'payment_method',
        'payment_reference',
        'notes',
        'created_at',
    ];

    protected $casts = [
        'commission_amount' => 'decimal:2',
        'commission_date' => 'datetime',
        'paid_date' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function influencer()
    {
        return $this->belongsTo(User::class, 'influencer_user_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }
}
