<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Add sort options
        $path = request()->path();
        
        $sortOptions = [
            '' => 'Name (A-Z)',
            'price_low' => 'Price (Low to High)',
            'price_high' => 'Price (High to Low)',
            'newest' => 'Newest First'
        ];

        $query = Product::with('category','subcategory')->active();
        
        // Filter by path type based on the JSON-encoded "checks" field
        if ($path === "postage") {
            $query->where('checks', 'like', '%postage_eligible%');
        } 
        
        if ($request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        if ($request->subcategory) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('slug', $request->subcategory);
            });
        }

        // Price filter
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        // Sort products
        switch($request->sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12);

        $categories = ProductCategory::active()->withCount('products')->get();
        // Get min and max prices for filter
        
        $priceRange = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        
        if($path === "postage") {
            $priceRange = Product::selectRaw('MIN(paypostage_price) as min_price, MAX(paypostage_price) as max_price')->first();
            return view('front.postage.index', compact('products', 'categories', 'priceRange', 'sortOptions'));
        }

        return view('front.products.index', compact('products', 'categories', 'priceRange', 'sortOptions'));
    }

    public function show($slug)
    {

        $path = request()->path();

        $path= explode('/', $path);
        
        $product = Product::where('slug', $slug)
            ->with(['category'])
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->firstOrFail();
        
        $related_products = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('checks', 'like', '%featured%')
                ->take(4)
                ->get();
        // Get related products from same category
        if($path[0] === "postage") {
            $related_products = Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->where('checks', 'like', '%postage_eligible%')
                    ->take(4)
                    ->get();
        }
        
        
        // Get paginated reviews
        $reviews = $product->approvedReviews()
            ->with('user')
            ->latest()
            ->paginate(5);
        
        // Check if user has already reviewed
        $userReview = null;
        if(auth()->check()) {
            $userReview = $product->reviews()
                ->where('user_id', auth()->id())
                ->first();
        }
        
        // Can user review this product?
        $canReview = false;
        if(auth()->check() && !$userReview) {
            $canReview = OrderItem::whereHas('order', function($q) {
                $q->where('user_id', auth()->id())
                    ->whereIn('status', ['completed', 'delivered']);
            })->where('product_id', $product->id)
                ->exists();
        }
        //dd($canReview);
        if($path[0] === "postage") {
            return view('front.postage.show', compact(
                'product',
                'related_products',
                'reviews',
                'userReview',
                'canReview',
                'path'
            ));
        }

        return view('front.products.show', compact(
            'product',
            'related_products',
            'reviews',
            'userReview',
            'canReview',
            'path'
        ));
    }

    public function getVariantPrice(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $selectedOptions = $request->option_values ?? [];
        
        if (empty($selectedOptions) || !$product->variations) {
            return response()->json([
                'success' => true,
                'price' => $product->discount_price ?? $product->price,
                'stock' => $product->stock
            ]);
        }

        $variations = json_decode($product->variations, true);
        $additionalPrice = 0;

        // Calculate additional price from selected options
        foreach ($variations as $variation) {
            if (isset($selectedOptions[$variation['option_type_id']])) {
                foreach ($variation['values'] as $value) {
                    if ($value['value'] == $selectedOptions[$variation['option_type_id']]) {
                        $additionalPrice += floatval($value['additional_price']);
                    }
                }
            }
        }

        $basePrice = $product->discount_price ?? $product->price;
        $finalPrice = $basePrice + $additionalPrice;

        return response()->json([
            'success' => true,
            'price' => number_format($finalPrice, 2),
            'stock' => $product->stock
        ]);
    }
}
