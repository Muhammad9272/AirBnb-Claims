<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Exception;
use DB;

use App\Models\GeneralSetting;
use Log;

class PaypalController extends Controller
{
    protected PayPalClient $provider;


    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->provider = new PayPalClient();
        // load credentials & generate access token
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }

    /**
     * Create PayPal order & redirect user to approval URL
     */
    public function processPayment(string $orderNumber)
    {
        try {
            $gs=GeneralSetting::findOrFail(1);
            $order = Order::where('order_number', $orderNumber)
                          ->where('user_id', auth()->id())
                          ->whereIn('status', ['pending', 'processing'])
                          ->firstOrFail();

            $payload = [
                'intent'             => 'CAPTURE',
                'purchase_units'     => [[
                    'reference_id' => $order->order_number,
                    'amount'       => [
                        'currency_code' => strtoupper($order->currency),
                        'value'         => number_format($order->total, 2, '.', ''),
                    ],
                    'description'  => 'Order #' . $order->order_number,
                ]],
                'application_context' => [
                    'brand_name'             => $gs->name,
                    'return_url'             => route('front.payment.paypal.success',   ['orderNumber' => $orderNumber]),
                    'cancel_url'             => route('front.checkout.cancel',         ['orderNumber' => $orderNumber]),
                    'user_action'            => 'PAY_NOW',
                ],
            ];

            $response = $this->provider->createOrder($payload);

            if (empty($response['id']) || empty($response['links'])) {
                throw new Exception('Invalid createOrder response');
            }

            // store PayPal order ID
            $order->update([
                'meta' => array_merge($order->meta ?? [], [
                    'paypal_order_id' => $response['id'],
                ]),
            ]);

            // find approval link
            $approvalUrl = collect($response['links'])
                ->firstWhere('rel', 'approve')['href'] ?? null;

            if (! $approvalUrl) {
                throw new Exception('Approval URL not found');
            }

            return redirect()->away($approvalUrl);

        } catch (Exception $e) {
            Log::error('PayPal createOrder error: '.$e->getMessage());
            return redirect()
                ->route('user.orders.show', $orderNumber)
                ->with('error', 'Unable to initialize PayPal payment: '.$e->getMessage());
        }
    }

    /**
     * Handle PayPal callback & capture payment
     */
    public function callback(Request $request, string $orderNumber)
    {
        try {
            DB::beginTransaction();

            $order = Order::where('order_number', $orderNumber)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();

            // PayPal returns token param = PayPal order ID
            $paypalOrderId = $request->query('token')
                             ?: ($order->meta['paypal_order_id'] ?? null);

            if (! $paypalOrderId) {
                throw new Exception('Missing PayPal order ID');
            }

            // capture payment
            $captureResponse = $this->provider->capturePaymentOrder($paypalOrderId);

            if (($captureResponse['status'] ?? '') !== 'COMPLETED') {
                throw new Exception('Payment not completed: '.($captureResponse['status'] ?? 'unknown'));
            }

            // record transaction
            Transaction::create([
                'order_id'       => $order->id,
                'transaction_id' => $captureResponse['id'],
                'payment_method' => 'paypal',
                'amount'         => $order->total,
                'currency'       => $order->currency,
                'status'         => 'completed',
                'payload'        => $captureResponse,
            ]);

            // update order
            $order->update([
                'payment_status' => 'completed',
                'status'         => 'processing',
            ]);

            DB::commit();

            return redirect()
                ->route('user.orders.show', $orderNumber)
                ->with('success', 'Payment completed successfully via PayPal!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PayPal capture error: '.$e->getMessage());

            return redirect()
                ->route('user.orders.show', $orderNumber)
                ->with('error', 'PayPal payment failed: '.$e->getMessage());
        }
    }
}
