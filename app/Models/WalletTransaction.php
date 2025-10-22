<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'transaction_type',
        'amount',
        'related_user_id',
        'related_subscription_id',
        'related_commission_id',
        'related_referral_transaction_id',
        'balance_before',
        'balance_after',
        'description',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    public function relatedSubscription()
    {
        return $this->belongsTo(UserSubscription::class, 'related_subscription_id');
    }

    public function relatedCommission()
    {
        return $this->belongsTo(InfluencerCommission::class, 'related_commission_id');
    }

    public function relatedReferralTransaction()
    {
        return $this->belongsTo(ReferralTransaction::class, 'related_referral_transaction_id');
    }
}
