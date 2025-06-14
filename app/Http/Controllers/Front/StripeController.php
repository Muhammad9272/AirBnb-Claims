<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\CentralLogics\Helpers;
use Stripe\Stripe;
use Exception;
use DB;
use App\Models\GeneralSetting;

use Illuminate\Http\Request;
class StripeController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function processPayment($orderNumber) 
    {
        try {
            $gs=GeneralSetting::findOrFail(1);
            $order = Order::where('order_number', $orderNumber)
                         ->where('user_id', auth()->id())
                         ->whereIn('status', ['pending', 'processing'])
                         ->firstOrFail();

            // Create Stripe checkout session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($order->currency),
                        'product_data' => [
                            'name' => 'Order #' . $order->order_number,
                            'description' => 'Payment for your order at ' . $gs->name,
                        ],
                        'unit_amount' => intval($order->total * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('front.payment.stripe.success', ['orderNumber' => $order->order_number]),
                'cancel_url' => route('front.checkout.cancel', $order->order_number),
                'metadata' => [
                    'order_number' => $order->order_number,
                    'user_id' => auth()->id()
                ],
                'customer_email' => auth()->user()->email,
            ]);

            // Store session ID with order for verification
            $order->update([
                'meta' => array_merge($order->meta ?? [], ['stripe_session_id' => $session->id])
            ]);

            return redirect($session->url);

        } catch(Exception $e) {
            \Log::error('Stripe session creation error: ' . $e->getMessage());
            return redirect()->route('user.orders.show', $order->order_number)
                           ->with('error', 'Unable to initialize payment: ' . $e->getMessage());
        }
    }

    public function callback($orderNumber)
    {
        try {
            DB::beginTransaction();

            $order = Order::where('order_number', $orderNumber)
                         ->where('user_id', auth()->id())
                         ->firstOrFail();

            // Verify the payment was successful using Stripe webhook or session verification
            $session = \Stripe\Checkout\Session::retrieve(
                $order->meta['stripe_session_id'] ?? ''
            );

            if ($session->payment_status !== 'paid') {
                throw new Exception('Payment was not successful');
            }

            // Create transaction record
            Transaction::create([
                'order_id' => $order->id,
                'transaction_id' => $session->payment_intent,
                'payment_method' => 'stripe',
                'amount' => $order->total,
                'currency' => $order->currency,
                'status' => 'completed',
                'payload' => [
                    'session_id' => $session->id,
                    'payment_intent' => $session->payment_intent,
                    'payment_status' => $session->payment_status,
                    'customer_email' => $session->customer_email,
                    'metadata' => $session->metadata->toArray()
                ]
            ]);

            // Update order status
            $order->update([
                'payment_status' => 'completed',
                'status' => 'processing'
            ]);

            DB::commit();

            return redirect()->route('user.orders.show', $order->order_number)
                           ->with('success', 'Payment completed successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Stripe payment verification error: ' . $e->getMessage());
            
            return redirect()->route('user.orders.show', $order->order_number)
                           ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

}
