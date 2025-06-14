<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'parent_id', 'status'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function parentCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')
            ->orWhereIn('category_id', $this->subcategories->pluck('id'));
    }

    public function directProducts()
    {
        return $this->hasMany(Product::class, 'category_id')
                    ->where('status', 1); // Only active products
    }

    public function directSubProducts()
    {
        return $this->hasMany(Product::class, 'subcategory_id')
                    ->where('status', 1); // Only active products
    }

}
