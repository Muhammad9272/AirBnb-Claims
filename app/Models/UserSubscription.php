<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subplan_id',
        'stripe_id',
        'stripe_status',
        'stripe_price',
        'quantity',
        'trial_ends_at',
        'ends_at',
        'status',
        'payment_method',
        'transaction_id',
        'price',
        'expires_at',
        'canceled_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
        'expires_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan that belongs to the subscription.
     */
    public function plan()
    {
        return $this->belongsTo(SubPlan::class, 'subplan_id');
    }

    public function subplan()
    {
        return $this->belongsTo(SubPlan::class, 'subplan_id');
    }

    /**
     * Determine if the subscription is active.
     */
    public function isActive()
    {
        return $this->status === 'active' && 
               ($this->ends_at === null || $this->ends_at->isFuture());
    }

    /**
     * Determine if the subscription is canceled.
     */
    public function isCanceled()
    {
        return $this->canceled_at !== null;
    }

    /**
     * Determine if the subscription is on trial.
     */
    public function onTrial()
    {
        return $this->trial_ends_at !== null && $this->trial_ends_at->isFuture();
    }

    /**
     * Cancel the subscription.
     */
    public function cancel()
    {
        $this->canceled_at = now();
        $this->save();

        return $this;
    }
}
