<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_item_id',
        'rating',
        'review_text',
        'admin_reply',
        'verified_purchase',
        'approved'
    ];

    // Relations
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeVerifiedPurchase($query)
    {
        return $query->where('verified_purchase', true);
    }
    
    public function getStarsHtml()
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $starClass = $i <= $this->rating ? 'text-warning' : 'text-muted';
            $html .= '<i class="las la-star ' . $starClass . '"></i>';
        }
        return $html;
    }
}
