<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\CentralLogics\{Helpers, CheckoutLogics};
use Stripe\{Stripe, PaymentIntent};
use Exception;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function process(Order $order)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => round($order->total * 100), // Convert to cents
                'currency' => Helpers::getCurrency(),
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ],
                'description' => "Payment for order #{$order->order_number}"
            ]);

            return response()->json([
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'order_number' => $order->order_number,
                'publishable_key' => config('services.stripe.key')
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $order = Order::where('order_number', $paymentIntent->metadata->order_number)->first();
                    
                    if ($order) {
                        $order->payment_status = 'completed';
                        $order->status = 'processing';
                        $order->save();

                        CheckoutLogics::updateProductStock($order);
                    }
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $order = Order::where('order_number', $paymentIntent->metadata->order_number)->first();
                    
                    if ($order) {
                        $order->payment_status = 'failed';
                        $order->status = 'cancelled';
                        $order->save();
                    }
                    break;
            }

            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
