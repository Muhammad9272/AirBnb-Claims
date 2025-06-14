<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'sku', 'product_type', 'description', 'price', 
        'quantity', 'category_id', 'subcategory_id',
        'tags', 'checks', 'views', 'variations', 'max_bits_allowed', 'paypostage_stock', 'paypostage_price'
    ];

    protected $casts = [
        'variations' => 'array',
        'tags' => 'array',
        'checks' => 'array',
        'gallery' => 'array'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    // public function scopeActive($query)
    // {
    //     return $query->where('products.status', 1); // Explicit table name
    // }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(ProductCategory::class, 'subcategory_id');
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where('approved', true);
    }

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }

    public function getRatingCountAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function getBitsDiscountedPrice()
    {   
        $gs = GeneralSetting::findOrFail(1);
        if ($this->max_bits_allowed == 0) {
            return $this->price;
        }
        $discountedPrice = $this->price - ($this->max_bits_allowed * $gs->bit_value);
        return max(0, $discountedPrice);
    }

    public function getBitsSavingsAmount()
    {
        return $this->price - $this->getBitsDiscountedPrice();
    }

    public function getBitsSavingsPercentage()
    {
        if ($this->price > 0) {
            return number_format((($this->getBitsSavingsAmount() / $this->price) * 100),2);
        }
        return 0;
    }

    public function getBitsDiscountedPricePostage()
    {   
        $gs = GeneralSetting::findOrFail(1);
        if ($this->max_bits_allowed == 0) {
            return $this->paypostage_price;
        }
        $discountedPrice = $this->paypostage_price - ($this->max_bits_allowed * $gs->bit_value);
        return max(0, $discountedPrice);
    }

    public function getBitsSavingsAmountPostage()
    {
        return $this->paypostage_price - $this->getBitsDiscountedPricePostage();
    }

    public function getBitsSavingsPercentagePostage()
    {
        if ($this->paypostage_price > 0) {
            return number_format((($this->getBitsSavingsAmountPostage() / $this->paypostage_price) * 100),2);
        }
        return 0;
    }
    

    // Add accessor methods for tags and checks
    public function getFormattedTagsAttribute()
    {
        if (empty($this->tags)) {
            return '';
        }
        $tags = is_string($this->tags) ? json_decode($this->tags, true) : $this->tags;
        return is_array($tags) ? implode(',', $tags) : '';
    }

    public function getFormattedChecksAttribute()
    {
        if (empty($this->checks)) {
            return [];
        }
        return is_string($this->checks) ? json_decode($this->checks, true) : $this->checks;
    }

    public function getFormattedVariationsAttribute()
    {
        if (empty($this->variations)) {
            return [];
        }
        return is_string($this->variations) ? json_decode($this->variations, true) : $this->variations;
    }

    public function hasVariations()
    {
        return !empty($this->formatted_variations);
    }
}
