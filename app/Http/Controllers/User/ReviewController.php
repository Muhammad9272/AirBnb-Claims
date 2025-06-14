<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
     public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }
    public function index()
    {
        $reviews = ProductReview::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('user.reviews.index', compact('reviews'));
    }
    
    public function create(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        // Check if user already reviewed this product
        $existingReview = ProductReview::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();
            
        if ($existingReview) {
            return redirect()->route('user.reviews.edit', $existingReview->id)
                ->with('info', 'You have already reviewed this product. You can edit your review.');
        }
            
        // Check if user has purchased the product
        $hasOrdered = OrderItem::whereHas('order', function($q) {
            $q->where('user_id', Auth::id())
                ->where('status', 'completed');
        })->where('product_id', $product->id)
            ->exists();
            
        return view('user.reviews.create', compact('product', 'hasOrdered'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000'
        ]);
        
        // Check if user already reviewed this product
        $existingReview = ProductReview::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();
            
        if ($existingReview) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this product. You can edit your review.',
                    'redirect' => route('user.reviews.edit', $existingReview->id)
                ]);
            }
            
            return redirect()->route('user.reviews.edit', $existingReview->id)
                ->with('info', 'You have already reviewed this product. You can edit your review.');
        }
        
        // Check if user has purchased the product
        $orderItem = OrderItem::whereHas('order', function($q) {
            $q->where('user_id', Auth::id())
                ->whereIn('status', ['completed', 'delivered']);
        })->where('product_id', $request->product_id)
            ->first();
        
        // Save review
        $review = new ProductReview;
        $review->user_id = Auth::id();
        $review->product_id = $request->product_id;
        $review->order_item_id = $orderItem ? $orderItem->id : null;
        $review->rating = $request->rating;
        $review->review_text = $request->review_text;
        $review->verified_purchase = $orderItem ? true : false;
        $review->approved = false; // Require admin approval
        $review->save();
        
        $message = 'Thank you for your review! It will be visible after approval by moderators.';
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('front.products.show', $review->product->slug)
            ]);
        }
        
        return redirect()->route('user.reviews.index')
            ->with('success', $message);
    }
    
    public function edit($id)
    {
        $review = ProductReview::with('product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('user.reviews.edit', compact('review'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000'
        ]);
        
        $review = ProductReview::where('user_id', Auth::id())
            ->findOrFail($id);
            
        $review->rating = $request->rating;
        $review->review_text = $request->review_text;
        $review->approved = false; // Reset approval status
        $review->save();
        
        $message = 'Your review has been updated and will be visible after approval.';
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('front.products.show', $review->product->slug)
            ]);
        }
        
        return redirect()->route('user.reviews.index')
            ->with('success', $message);
    }
    
    public function delete($id)
    {
        $review = ProductReview::where('user_id', Auth::id())
            ->findOrFail($id);
        $review->delete();
        
        return redirect()->route('user.reviews.index')
            ->with('success', 'Your review has been deleted.');
    }
}
