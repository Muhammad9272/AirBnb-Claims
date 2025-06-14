<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Cart;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\TransactionLogic;
use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\TemporaryOrder;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Session;
use Stripe\Exception\CardException;
class AliPayController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth')->only('checkoutStore');

        // $this->middleware('auth')->except(['handleNotification', 'handleReturn']);  // Assuming these are the method names in the controller for the notify and return routes
        $this->request = $request;
        $this->signKey = env('ALIPAY_KEY');
        $this->partnerNo = env('ALIPAY_PARTNER_NO');
        $this->storeNo = env('ALIPAY_STORE_NO');
         // This should be your sign key from ScanForPay
    }

    
    public function checkoutStore(Request $request)
    {  
        
        $gs = GeneralSetting::findOrFail(1);
        $user = Auth::user();
        $cartItems = Cart::getCartItems();

        if (!$cartItems) {
            return redirect()->back()->with('error', 'Empty Cart');
        }

        $cartTotal = Cart::calculateCartTotal($cartItems);
        $totalprice = Helpers::getPrice($cartTotal['total']);
        
        $convertedRateHKD=round($totalprice * $gs->alipay_conversion_rate * 100);
        //dd($convertedRate);
        // dd(205*10*100,$convertedRate);
        // Constructing the ScanForPay request
        $header = $this->generateHeader();
        $partnerOrderNo='p' . time() . mt_rand(1000, 9999);
        $authToken = hash('sha256', Str::random(32));
        $body = [
            'storeNo' => $this->storeNo, // This should be your store number from ScanForPay
            'partnerOrderNo' => $partnerOrderNo,
            'wallet' => 'Alipay',
            'currency' => 'HKD',
            'orderAmount' => $convertedRateHKD, // Convert to smallest unit of the currency
            'orderTitle' => 'Items in cart',
            'operatorId' => '211918', // Adjust as per your system
            'terminalNo' => '123456', // Adjust as per your system
            'notifyUrl' => route('user.alipay.notify'),
            'returnUrl' => route('user.alipay.return',$authToken)
        ];
        $fullRequest = $this->generateFullRequest($header, $body);
        $response = Http::post('https://pay.scanforpay.com/api/online/create', $fullRequest);

        if ($response->successful()) {
            $responseData = $response->json();
            if ($responseData['response']['body']['code'] == 1) { // Check for successful response code               
                //Remove temporary order by changing the status
                $tempOrder=TemporaryOrder::where('user_id',auth::user()->id)->where('status','pending')->update(['status'=>'override']);

                $coupon_code=Session::get('coupon_code');
                $coupon_own_type=Session::get('coupon_own_type');
                $couponPercentage = Session::get('coupon_percentage');

                // Add the 'total' key in the $cartTotal array
                $cartTotal['convertedRateHKD'] = $convertedRateHKD;

                
                $createOrder=OrderLogic::createTemporaryOrder($user->id,$partnerOrderNo,$cartItems,$cartTotal,'pending','AliPay',$authToken,$coupon_code,$coupon_own_type,$couponPercentage);
                $payUrl = $responseData['response']['body']['payUrl'];
                return redirect($payUrl);
            } else {
                // Log the error for debugging purposes
                \Log::error('ScanForPay API Error:', ['response' => $responseData]);
                return redirect()->back()->with('error', 'Payment initialization failed. Please try again.');
            }
        } else {
            // Log the error for debugging purposes
            \Log::error('ScanForPay API Request Error:', ['response' => $response->body()]);
            return redirect()->back()->with('error', 'There was a problem processing your request. Please try again.');
        }
    }

    public function handleNotification(Request $request)
    {
        // Handle the notification logic here
        \Log::info('Received checkout notify request:', $request->all());
        $partnerOrderNo = $request->input('partnerOrderNo');
        // return redirect()->route('user.dashboard')->with('success','Payment SuccessFully');
    }

    public function handleReturn(Request $request,$authToken)
    {  

        $gs = GeneralSetting::findOrFail(1);
        $partnerOrderNo = $request->input('partnerOrderNo');
        $tempOrder=TemporaryOrder::where('partner_order_no',$partnerOrderNo)->
        where('auth_token',$authToken)->where('status','pending')->firstOrFail();
        // dd($tempOrder);
        $verificationResult = $this->verifyTransactionWithScanForPay($partnerOrderNo);
        // Handle the return logic here
        \Log::info('Received checkout notify request Return:', [
            'request_data' => $request->all(),
            'verification_result' => $verificationResult
        ]);


        if ($verificationResult['status'] === 'success' && $verificationResult['data']['code']==1 && $verificationResult['data']['status']==1) {
            $transactionData = $verificationResult['data'];
            // Decode the cart items and cart total, since they were stored as JSON
            $cartItems = json_decode($tempOrder->cart_items, true);
            $cartTotal = json_decode($tempOrder->cart_total, true);

            // Now, use these details to call the createOrder function in OrderLogic class
            $orderAmountInGBP = Helpers::getPrice($transactionData['orderAmount'] / ($gs->alipay_conversion_rate * 100),1);
            $cartTotal['total'] = $orderAmountInGBP;

            $createOrder = OrderLogic::createOrder(
                $tempOrder->user_id, 
                $cartItems, 
                $cartTotal, 
                $payment_status='completed', 
                $tempOrder->payment_method, 
                $tempOrder->coupon_code, 
                $tempOrder->coupon_own_type, 
                $tempOrder->coupon_percentage
            );
            
            $txn_id=$transactionData['orderNo'];

            

            $order= $createOrder; 
            $createtransaction=TransactionLogic::createTransaction($order->id,$order->user_id,$order->payment_gateway, $orderAmountInGBP,$order->checkout_fee,'checkout',$txn_id); 

            $tempOrder->status='completed';
            $tempOrder->payment_status='completed';
            $tempOrder->update(); 

            $mailer = new GeniusMailer();
            $mailer->sendOrderConfirmation($order);
            
            
            //clear cart
            Cart::clearCart();

            $data=User::user()->where('id',$order->user_id)->first();
            Auth::guard('web')->login($data); 

            return redirect()->route('user.orders')->with('success','Order placed SuccessFully');
            
            // Handle successful verification (e.g., update order status in your DB)
        } else {
            $tempOrder->status='failed';
            $tempOrder->update(); 
            // Handle the return logic here
           \Log::info('Transaction Failed partnerOrderNo '. $partnerOrderNo);
            return redirect()->route('user.orders')->with('error','Transaction verification failed.');
            // Handle failed verification (e.g., log it for further investigation)
        }
    }

    private function generateHeader()
    {
        return [
            'version' => '1.0',
            'partnerNo' => $this->partnerNo,
            'reqMsgId' => Str::uuid()->toString(),
            'reqTime' => now()->toRfc3339String(),
            'signType' => 'SHA256'
        ];
    }
    private function generateFullRequest($header, $body)
    {
        $requestForSignature = [
            'header' => $header,
            'body' => $body
        ];

        $jsonRequest = json_encode($requestForSignature);
        $combinedString = $jsonRequest . $this->signKey;
        $signature = hash('sha256', $combinedString);

        return [
            'request' => $requestForSignature,
            'signature' => $signature
        ];
    }
    private function verifySignature($data, $receivedSignature) {
        $stringToSign = json_encode($data) . $this->signKey;
        $calculatedSignature = hash('sha256', $stringToSign);

        return $calculatedSignature === $receivedSignature;
    }

    private function verifyTransactionWithScanForPay($partnerOrderNo)
    {
        $header = $this->generateHeader();  // Assuming you have this method from your previous code

        $body = [
            'partnerOrderNo' => $partnerOrderNo
        ];

        $fullRequest = $this->generateFullRequest($header, $body);

        $response = Http::post('https://pay.scanforpay.com/api/common/payQuery', $fullRequest);

        if ($response->successful()) {
            $responseData = $response->json();
            // Signature Verification
            // if (!$this->verifySignature($responseData['response'], $responseData['signature'])) {
            //     return [
            //         'status' => 'failed',
            //         'message' => 'Signature verification failed.'
            //     ];
            // }
            if ($responseData['response']['body']['status'] == 1) {
                return [
                    'status' => 'success',
                    'data' => $responseData['response']['body'],
                ];
            } else {
                return [
                    'status' => 'failed',
                    'data' => $responseData['response']['body'],
                ];
            }
        } else {
            // Handle the situation when the response itself is not successful
            return [
                'status' => 'failed',
                'message' => 'Failed to connect to payment gateway or unexpected response.'
            ];
        }
    }

}
