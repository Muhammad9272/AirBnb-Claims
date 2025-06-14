<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\CentralLogics\{Helpers, CheckoutLogics};
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Exception;
use Illuminate\Http\Request;

class PaypalController extends Controller
{
    private $client;

    public function __construct()
    {
        $environment = new SandboxEnvironment(
            config('services.paypal.client_id'),
            config('services.paypal.secret')
        );
        $this->client = new PayPalHttpClient($environment);
    }

    public function process(Order $order)
    {
        try {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            
            $request->body = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $order->order_number,
                    'amount' => [
                        'currency_code' => Helpers::getCurrency(),
                        'value' => number_format($order->total, 2, '.', '')
                    ],
                    'description' => "Payment for order #{$order->order_number}"
                ]],
                'application_context' => [
                    'return_url' => route('user.paypal.capture', ['order' => $order->order_number]),
                    'cancel_url' => route('user.paypal.cancel', ['order' => $order->order_number])
                ]
            ];

            $response = $this->client->execute($request);

            return response()->json([
                'success' => true,
                'approval_url' => $this->getApprovalUrl($response->result->links)
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function capture(Request $request, $orderNumber)
    {
        try {
            $order = Order::where('order_number', $orderNumber)
                         ->where('user_id', auth()->id())
                         ->firstOrFail();

            $captureRequest = new OrdersCaptureRequest($request->token);
            $response = $this->client->execute($captureRequest);

            if ($response->result->status === 'COMPLETED') {
                $order->payment_status = 'completed';
                $order->status = 'processing';
                $order->save();

                CheckoutLogics::updateProductStock($order);

                return redirect()->route('front.checkout.complete', $order->order_number);
            }

            throw new Exception('Payment not completed');

        } catch (Exception $e) {
            return redirect()->route('front.checkout.cancel', $order->order_number)
                           ->with('error', $e->getMessage());
        }
    }

    public function cancel($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();

        $order->payment_status = 'cancelled';
        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('front.checkout.cancel', $order->order_number)
                       ->with('error', 'Payment was cancelled');
    }

    private function getApprovalUrl($links)
    {
        foreach ($links as $link) {
            if ($link->rel === 'approve') {
                return $link->href;
            }
        }
        throw new Exception('No approval URL found');
    }
}
