<?php

namespace App\CentralLogics;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartLogics 
{
    public static function getOrCreateCart()
    {
        $sessionId = Session::getId();
        $userId = auth()->id();

        $cart = Cart::where(function($query) use ($sessionId, $userId) {
            $query->where('session_id', $sessionId)
                  ->orWhere('user_id', $userId);
        })->first();

        if (!$cart) {
            $cart = Cart::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'currency' => Helpers::getCurrency()
            ]);
        }

        return $cart;
    }

    public static function calculateItemPrice($product, $options = null)
    {
        $basePrice = $product->discount_price ?? $product->price;
        
        if (!$options || !$product->variations) {
            return $basePrice;
        }

        $variations = json_decode($product->variations, true);
        $additionalPrice = 0;

        foreach ($variations as $variation) {
            if (isset($options[$variation['option_type_id']])) {
                foreach ($variation['values'] as $value) {
                    if ($value['value'] == $options[$variation['option_type_id']]) {
                        $additionalPrice += floatval($value['additional_price']);
                    }
                }
            }
        }

        return $basePrice + $additionalPrice;
    }

    public static function calculateCartTotals($cart)
    {
        $subtotal = 0;
        foreach ($cart->items as $item) {
            $subtotal += $item->price * $item->quantity;
        }

        $tax = $subtotal * Helpers::getTaxRate();
        $total = $subtotal + $tax - $cart->discount;

        return [
            'subtotal' => Helpers::getPrice($subtotal),
            'tax' => Helpers::getPrice($tax),
            'total' => Helpers::getPrice($total),
        ];
    }

    public static function mergeCarts($sessionCart, $userCart)
    {
        if (!$sessionCart || !$userCart) {
            return $userCart ?? $sessionCart;
        }

        // Move items from session cart to user cart
        foreach ($sessionCart->items as $item) {
            $existingItem = $userCart->items()
                ->where('product_id', $item->product_id)
                ->where('options', $item->options)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $item->quantity
                ]);
            } else {
                $item->cart_id = $userCart->id;
                $item->save();
            }
        }

        // Delete the session cart
        $sessionCart->delete();

        // Recalculate totals
        $totals = self::calculateCartTotals($userCart);
        $userCart->update($totals);

        return $userCart;
    }

    public static function addItem($product, $quantity = 1, $options = null)
    {
        $cart = self::getOrCreateCart();
        
        // Validate stock
        if ($product->quantity < $quantity) {
            return [
                'success' => false,
                'message' => 'Not enough stock available'
            ];
        }

        // Calculate price with options
        $price = self::calculateItemPrice($product, $options);

        // Check for existing item
        $existingItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('options', json_encode($options))
            ->first();

        if ($existingItem) {
            // Update quantity if exists
            $newQuantity = $existingItem->quantity + $quantity;
            if ($product->quantity < $newQuantity) {
                return [
                    'success' => false,
                    'message' => 'Cannot add more of this item (stock limit)'
                ];
            }
            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new item
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
                'options' => $options
            ]);
        }

        // Recalculate cart totals
        self::updateCartTotals($cart);

        return [
            'success' => true,
            'message' => 'Item added to cart',
            'cart' => $cart
        ];
    }

    public static function updateCartTotals($cart)
    {
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart->items as $item) {
            $subtotal += $item->price * $item->quantity;
            $totalItems += $item->quantity;
        }

        $tax = $subtotal * Helpers::getTaxRate();
        $shipping = Helpers::calculateShipping($totalItems);
        
        // Apply coupon if exists
        $discount = 0;
        if ($cart->coupon_code) {
            $couponValidation = Checkout::validateCoupon($cart->coupon_code);
            if ($couponValidation['valid']) {
                $discount = ($subtotal * $couponValidation['discount']) / 100;
            }
        }

        $total = $subtotal + $tax + $shipping - $discount;

        $cart->update([
            'subtotal' => Helpers::getPrice($subtotal),
            'tax' => Helpers::getPrice($tax),
            'shipping' => Helpers::getPrice($shipping),
            'discount' => Helpers::getPrice($discount),
            'total' => Helpers::getPrice($total),
            'total_items' => $totalItems
        ]);

        return $cart;
    }

    public static function validateStock($cart)
    {
        try {
            foreach ($cart->items as $item) {
                $product = Product::findOrFail($item->product_id);
                
                if ($product->quantity < $item->quantity) {
                    return [
                        'success' => false,
                        'message' => "Insufficient stock for {$product->name}. Available: {$product->quantity}, Requested: {$item->quantity}"
                    ];
                }
            }
            return ['success' => true];
        } catch (\Exception $e) {
            \Log::error('Stock validation error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error validating stock'
            ];
        }
    }

    public static function decrementStock($order)
    {
        try {
            \DB::beginTransaction();

            foreach ($order->orderItems as $item) {
                $product = Product::findOrFail($item->product_id);
                
                if ($product->quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $product->decrement('quantity', $item->quantity);
                
                \Log::info("Stock decreased for product {$product->id}: -{$item->quantity}");
            }

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Stock decrement error: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function incrementStock($order)
    {
        try {
            \DB::beginTransaction();

            foreach ($order->orderItems as $item) {
                $product = Product::findOrFail($item->product_id);
                $product->increment('quantity', $item->quantity);
                
                \Log::info("Stock increased for product {$product->id}: +{$item->quantity}");
            }

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Stock increment error: ' . $e->getMessage());
            throw $e;
        }
    }
}
