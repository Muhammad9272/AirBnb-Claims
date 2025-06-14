<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Cart;
use App\CentralLogics\CartLogics;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartLogics::getOrCreateCart();
        return view('front.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'options' => 'nullable|array'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check quantity
        if ($product->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough quantity available'
            ], 400);
        }

        $cart = CartLogics::getOrCreateCart();

        // Create cart item with calculated prices
        $cartItem = new CartItem();
        $cartItem->product_id = $product->id;
        $cartItem->quantity = $request->quantity;
        $cartItem->options = $request->options;
        $cartItem->variations = $product->variations;
        
        // Calculate prices before saving
        $cartItem->beforeSave();
        
        $cart->items()->save($cartItem);

        // Recalculate cart totals
        $totals = CartLogics::calculateCartTotals($cart);
        $cart->update($totals);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => $cart->items->sum('quantity'),
            'cart' => [
                'subtotal' => Helpers::setCurrency($totals['subtotal']),
                'tax' => Helpers::setCurrency($totals['tax']),
                'total' => Helpers::setCurrency($totals['total']),
                'discount' => $cart->discount ? Helpers::setCurrency($cart->discount) : null
            ]
        ]);
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = CartLogics::getOrCreateCart();
        $item->update(['quantity' => $request->quantity]);

        // Recalculate cart totals
        $totals = CartLogics::calculateCartTotals($cart);
        $cart->update($totals);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart' => [
                'subtotal' => Helpers::setCurrency($totals['subtotal']),
                'tax' => Helpers::setCurrency($totals['tax']),
                'total' => Helpers::setCurrency($totals['total']),
                'discount' => $cart->discount ? Helpers::setCurrency($cart->discount) : null
            ]
        ]);
    }

    public function remove($itemId)
    {
        try {
            $cartItem = CartItem::findOrFail($itemId);
            $cart = $cartItem->cart;

            if ($cart->session_id !== session()->getId() && (!auth()->check() || $cart->user_id !== auth()->id())) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $cartItem->delete();
            $cart->recalculateTotal();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart' => [
                    'subtotal' => Helpers::setCurrency($cart->subtotal),
                    'tax' => Helpers::setCurrency($cart->tax),
                    'total' => Helpers::setCurrency($cart->total),
                    'discount' => $cart->discount ? Helpers::setCurrency($cart->discount) : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error removing item'], 500);
        }
    }

    public function clear()
    {
        try {
            $cart = CartLogics::getOrCreateCart();
            
            // Check if cart belongs to current user/session
            if ($cart->session_id !== session()->getId() && (!auth()->check() || $cart->user_id !== auth()->id())) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $cart->items()->delete();
            $cart->update([
                'subtotal' => 0,
                'tax' => 0,
                'total' => 0,
                'discount' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error clearing cart'], 500);
        }
    }
}
