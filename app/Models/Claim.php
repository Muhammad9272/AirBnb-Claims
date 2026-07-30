<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'claim_number',
        'title',
        'description',
        'property_address',
        'amount_requested',
        'amount_approved',
        'commission_amount',
        'is_commission_paid',
        'payment_id',
        'check_in_date',
        'check_out_date',
        'status',
        'incident_date',
        'guest_name',
        'guest_email',
        'airbnb_reservation_code',
        'rejection_reason',
        'paid_date',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'incident_date' => 'datetime',
        'paid_date' => 'datetime',
        'amount_requested' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'is_commission_paid' => 'boolean',
    ];

    /**
     * Get the user that owns the claim.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the claim.
     */
    public function comments()
    {
        return $this->hasMany(ClaimComment::class);
    }

    /**
     * Get the evidence files for the claim.
     */
    public function evidence()
    {
        return $this->hasMany(ClaimEvidence::class);
    }

    /**
     * Get the status history for the claim.
     */
    public function statusHistory()
    {
        return $this->hasMany(ClaimStatusHistory::class);
    }

    /**
     * Get the internal notes for the claim.
     */
    public function notes()
    {
        return $this->hasMany(ClaimNote::class);
    }

    /**
     * Format check-in date for display safely
     */
    public function getCheckInDateFormatted()
    {
        return $this->check_in_date ? $this->check_in_date->format('M d, Y') : 'Not provided';
    }

    /**
     * Format check-out date for display safely
     */
    public function getCheckOutDateFormatted()
    {
        return $this->check_out_date ? $this->check_out_date->format('M d, Y') : 'Not provided';
    }

    /**
     * Format incident date for display safely
     */
    public function getIncidentDateFormatted()
    {
        return $this->incident_date ? $this->incident_date->format('M d, Y') : 'Not provided';
    }

    /**
     * Map status names to readable labels.
     *
     * @return array
     */
    public static function getStatusLabels()
    {
        return [
            'pending' => 'Pending',
            'under_review' => 'In Review',
            'additional_info_requested' => 'Additional Info Requested',
            'challenge_1' => 'Challenge 1',
            'challenge_2' => 'Challenge 2',
            'approved' => 'Accepted',
            'partial_payout' => 'Partial Payout',
            'rejected' => 'Denied',
        ];
    }

    /**
     * Get readable status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $labels = self::getStatusLabels();
        return $labels[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }
    
    /**
     * Check if claim is in a final state.
     *
     * @return bool
     */
    public function isFinal()
    {
        return in_array($this->status, ['approved', 'partial_payout', 'rejected']);
    }
    
    /**
     * Map status names for compatibility between different UI elements
     * 
     * @param string $status
     * @return string
     */
    public static function mapStatus($status)
    {
        return match($status) {
            'under_review' => 'in_review',
            'in_review' => 'under_review',
            default => $status
        };
    }
}
