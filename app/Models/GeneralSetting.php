<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'general_affiliate_discount_percentage',
        'creator_affiliate_commission_percentage',
        'lead_popup_discount_percentage',
        'creator_commission_limit_days',
        'creator_commission_limit_claims',
        'enable_lead_popup',
        'enable_general_affiliate',
        'enable_creator_affiliate',
    ];

    protected $casts = [
        'general_affiliate_discount_percentage' => 'decimal:2',
        'creator_affiliate_commission_percentage' => 'decimal:2',
        'lead_popup_discount_percentage' => 'decimal:2',
        'enable_lead_popup' => 'boolean',
        'enable_general_affiliate' => 'boolean',
        'enable_creator_affiliate' => 'boolean',
    ];
}
