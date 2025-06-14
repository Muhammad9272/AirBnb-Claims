<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\CentralLogics\CartLogics;
use App\CentralLogics\Checkout;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\GeneralSetting;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = CartLogics::getOrCreateCart();
        $gs = GeneralSetting::first();
        
        // Redirect if cart is empty
        if ($cart->items->isEmpty()) {
            return redirect()->route('front.products.index')
                           ->with('error', 'Your cart is empty');
        }

        // Validate stock before checkout
        $stockValidation = CartLogics::validateStock($cart);
        if (!$stockValidation['success']) {
            return redirect()->route('front.cart.index')
                           ->with('error', $stockValidation['message']);
        }

        // Get user bit balance for the view
        $userBitBalance = 0;
        if (auth()->check()) {
            $userBitBalance = auth()->user()->bit_balance;
        }

        return view('front.checkout.index', compact('cart', 'userBitBalance', 'gs'));
    }

    public function process(Request $request)
    {
        $request->validate([
            // ...existing validation rules...
            'warranty_confirmed' => 'required|accepted',
        ], [
            'warranty_confirmed.accepted' => 'You must confirm that you have read and understood the warranty terms.'
        ]);

        try {
            DB::beginTransaction();
            
            $cart = CartLogics::getOrCreateCart();
            $gs = GeneralSetting::first();
            
            if ($cart->items->isEmpty()) {
                return redirect()->back()->with('error', 'Your cart is empty');
            }

            // Handle bit redemption
            $bitsUsed = 0;
            $bitsDiscount = 0;
            
            if (auth()->check() && $request->has('use_bits') && (int)$request->use_bits > 0) {
                $user = auth()->user();
                $bitsUsed = min((int)$request->use_bits, $user->bit_balance);
                
                // Calculate bit discount
                $rawBitsDiscount = $bitsUsed * $gs->bit_value;
                
                // Cap discount to prevent negative total
                // First, calculate max allowable discount (subtotal minus coupon discount)
                $maxAllowableDiscount = max(0, $cart->subtotal - $cart->discount);
                
                // Ensure bit discount doesn't exceed max allowable
                $bitsDiscount = min($rawBitsDiscount, $maxAllowableDiscount);
                
                // Recalculate bits used based on actual discount applied
                // This ensures we only use the bits needed for the actual discount
                $bitsUsed = ceil($bitsDiscount / $gs->bit_value);
                
                // Ensure we're not using more bits than available
                $bitsUsed = min($bitsUsed, $user->bit_balance);
                
                // Recalculate final bits discount
                $bitsDiscount = $bitsUsed * $gs->bit_value;
                
                // Log what we're doing for debugging
                \Log::info("Bit redemption: {$bitsUsed} bits used for \${$bitsDiscount} discount. Max allowed: \${$maxAllowableDiscount}");
            }

            // Create order
            $order = new Order([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'subtotal' => $cart->subtotal,
                'tax' => $cart->tax,
                'shipping' => $cart->shipping ?? 0,
                'discount' => $cart->discount, // Coupon discount
                'bits_discount' => $bitsDiscount, // Separate bits discount
                'bits_used' => $bitsUsed,
                'total' => max(0, $cart->total - $bitsDiscount), // Ensure total isn't negative
                'currency' => $cart->currency,
                // Add shipping details
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zipcode' => $request->shipping_zipcode,
                'shipping_country' => $request->shipping_country,
                // Add billing details
                'billing_name' => $request->billing_name,
                'billing_email' => $request->billing_email,
                'billing_phone' => $request->billing_phone,
                'billing_address' => $request->billing_address,
                'billing_city' => $request->billing_city,
                'billing_state' => $request->billing_state,
                'billing_zipcode' => $request->billing_zipcode,
                'billing_country' => $request->billing_country,
            ]);

            $order->save();

            // Deduct bits from user if used
            if ($bitsUsed > 0) {
                $user->deductBits(
                    $bitsUsed,
                    'order',
                    $order->id,
                    "Used for discount on order #{$order->order_number}"
                );
            }

            // Create order items and update stock
            foreach ($cart->items as $cartItem) {
                $orderItem = OrderItem::createFromCartItem($cartItem);
                $order->orderItems()->save($orderItem);
            }

            // Decrement stock after creating order items
            CartLogics::decrementStock($order);

            // Clear the cart after successful order creation
            // $cart->items()->delete();
            // $cart->update([
            //     'subtotal' => 0,
            //     'tax' => 0,
            //     'total' => 0,
            //     'discount' => 0
            // ]);

            DB::commit();

            // Redirect based on payment method
            switch ($request->payment_method) {
                case 'stripe':
                    return app(StripeController::class)->processPayment($order->order_number);
                case 'paypal':
                    return app(PaypalController::class)->processPayment($order->order_number);
                default:
                    return redirect()->route('user.orders.show', $order->order_number)
                                   ->with('success', 'Order placed successfully!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancel($orderNumber)
    {
        try {
            DB::beginTransaction();
            
            $order = Order::where('order_number', $orderNumber)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Only restore stock if order wasn't cancelled before
            if ($order->status !== 'cancelled') {
                CartLogics::incrementStock($order);
            }

            $order->status = 'cancelled';
            $order->save();

            DB::commit();

            return redirect()->route('user.orders.index')
                ->with('success', 'Order has been cancelled and stock has been restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order cancellation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error cancelling order.');
        }
    }
}
