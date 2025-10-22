<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'source',
        'referral_name',
        'ip_address',
        'user_agent',
        'is_registered',
        'registered_user_id',
        'discount_code_used',
        'status',
    ];

    protected $casts = [
        'is_registered' => 'boolean',
        'discount_code_used' => 'boolean',
    ];

    /**
     * Get the registered user if exists
     * Relationship based on email matching
     */
    public function registeredUser()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    /**
     * Check if this lead has registered
     * This is a dynamic check based on email
     */
    public function getIsRegisteredAttribute()
    {
        return User::where('email', $this->email)->exists();
    }

    /**
     * Check if this lead has used discount
     * This is a dynamic check based on subscriptions
     */
    public function getHasUsedDiscountAttribute()
    {
        $user = User::where('email', $this->email)->first();
        
        if (!$user) {
            return false;
        }

        return UserSubscription::where('user_id', $user->id)
            ->whereNotNull('discount_code')
            ->exists();
    }

    /**
     * Get the subscription where discount was used
     */
    public function discountSubscription()
    {
        $user = User::where('email', $this->email)->first();
        
        if (!$user) {
            return null;
        }

        return UserSubscription::where('user_id', $user->id)
            ->whereNotNull('discount_code')
            ->first();
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter registered leads (has matching user)
     */
    public function scopeRegistered($query)
    {
        return $query->whereHas('registeredUser');
    }

    /**
     * Scope: Filter not registered leads (no matching user)
     */
    public function scopeNotRegistered($query)
    {
        return $query->whereDoesntHave('registeredUser');
    }

    /**
     * Scope: Filter converted leads (used discount)
     */
    public function scopeConverted($query)
    {
        return $query->whereHas('registeredUser', function($q) {
            $q->whereHas('userSubscriptions', function($sq) {
                $sq->whereNotNull('discount_code');
            });
        });
    }
}