<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CentralLogics\Cart;
use Validator;
use Session;

class SubscriptionCheckoutController extends Controller
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }
    
    /**
     * Display the subscription checkout page.
     */
    public function show(Request $request)
    {
        $subscriptions = \App\Models\SubPlan::all();
        // $selectedSubscriptionId = Session::get('selected_subscription_id')
        //     ?? ($request->has('subid') ? $request->query('subid') : $subscriptions->first()->id);

            $selectedSubscriptionId = $request->has('subid')
        ? $request->query('subid')
        : (Session::get('selected_subscription_id') ?? $subscriptions->first()->id);

        $selectedPlan = \App\Models\SubPlan::find($selectedSubscriptionId);
        
        // Ensure cart contains only the selected subscription.
        $currentCart = Cart::getCartItems();
        if ($currentCart) {
            foreach ($currentCart as $key => $item) {
                if ($item['item_type'] === 'subscription_plan' && $item['item_id'] != $selectedPlan->id) {
                    Cart::removeCartItem('subscription_plan', $item['item_id']);

                    Cart::addCartItem([
                        'item_type' => 'subscription_plan',
                        'item_id'   => $selectedPlan->id,
                        'quantity'  => 1,
                    ]);
                }
            }
        } else {
            Cart::addCartItem([
                'item_type' => 'subscription_plan',
                'item_id'   => $selectedPlan->id,
                'quantity'  => 1,
            ]);
        }
        
        $cartItems = Cart::getCartItems();
        //dd($cartItems);
        $totals = Cart::calculateCartTotal($cartItems, $request->input('payment_method', 'stripe'));
        
        return view('front.checkout.show', [
            'subscriptions' => $subscriptions,
            'selectedPlan'  => $selectedPlan,
            'totals'        => $totals,
            'appliedCoupon' => Session::get('coupon_code', ''),
        ]);
    }
    
    /**
     * AJAX endpoint: recalculate pricing when subscription, coupon, or payment method changes.
     */
    public function calculatePrice(Request $request)
    {
        $rules = [
            'subscription_id' => 'required|integer',
            'coupon_code'     => 'nullable|string',
            'payment_method'  => 'required|string|in:stripe,paypal,alipay',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        
        $subscriptionId = $request->input('subscription_id');
        $couponCode = trim($request->input('coupon_code', ''));
        $paymentMethod = $request->input('payment_method');
        $subscription = \App\Models\SubPlan::find($subscriptionId);
        if (!$subscription) {
            return response()->json(['error' => 'Subscription not found.'], 404);
        }
        
        // Update cart: remove any existing subscription if different.
        $currentSubscriptionId = Session::get('selected_subscription_id');
        if ($currentSubscriptionId && $currentSubscriptionId != $subscriptionId) {
            Cart::removeCartItem('subscription_plan', $currentSubscriptionId);
        }
        Session::put('selected_subscription_id', $subscriptionId);
        $cartItems = Cart::getCartItems();
        $found = false;
        if ($cartItems) {
            foreach ($cartItems as $item) {
                if ($item['item_type'] === 'subscription_plan') {
                    $found = true;
                    break;
                }
            }
        }
        if (!$found) {
            Cart::addCartItem([
                'item_type' => 'subscription_plan',
                'item_id'   => $subscriptionId,
                'quantity'  => 1,
            ]);
        }
        
        // Apply coupon if provided.
        $couponValid = false;
        $couponMessage = '';
        if (!empty($couponCode)) {
            $couponResult = Cart::addCoupon($couponCode);
            if ($couponResult['status'] === 'success') {
                $couponValid = true;
            } else {
                $couponMessage = $couponResult['message'];
            }
        } else {
            Cart::removeCoupon();
        }
        
        $cartItems = Cart::getCartItems();
        $totals = Cart::calculateCartTotal($cartItems, $paymentMethod);
        
        return response()->json([
            'subscription_name'     => $subscription->name,
            'subscription_interval' => $subscription->interval,
            'price'                 => \App\CentralLogics\Helpers::getPrice($subscription->price, 1),
            'discount'              => \App\CentralLogics\Helpers::getPrice($totals['discount'], 1),
            'tax'                   => \App\CentralLogics\Helpers::getPrice($totals['tax'], 1),
            'final_price'           => \App\CentralLogics\Helpers::getPrice($totals['total'], 1),
            'coupon_valid'          => $couponValid,
            'coupon_message'        => $couponMessage,
        ]);
    }
    
    /**
     * Process payment: add the subscription to the cart and delegate to your PaymentController.
     */
    public function processPayment(Request $request)
    {
        $validatedData = $request->validate([
            'item_id'        => 'required|integer',
            'item_type'      => 'required|string|in:subscription_plan',
            'package_option' => 'required|string|in:membership,activate',
            'payment_method' => 'required|string|in:paypal,alipay,stripe',
            'coupon_code'    => 'nullable|string',
        ]);
        // $itemId = $validatedData['item_id'];
        
        // Ensure the subscription plan is in the cart.
        // $addItemResult = Cart::addCartItem([
        //     'item_type' => 'subscription_plan',
        //     'item_id'   => $itemId,
        //     'quantity'  => 1,
        // ]);
        // if (!$addItemResult['success']) {
        //     return redirect()->back()->with('error', $addItemResult['message']);
        // }

        $paymentGateway=$request->payment_method;
        switch ($paymentGateway) {
            case 'Wallet':                           
                return app(\App\Http\Controllers\User\WalletController::class)->checkoutStore($request);
            case 'paypal':
                return app(\App\Http\Controllers\User\PaypalController::class)->checkoutStore($request);
            case 'stripe':
                return app(\App\Http\Controllers\User\StripeController::class)->checkoutStore($request);
            // Add cases for other payment methods here
            default:
                return back()->with('error', 'Invalid payment method.');
        }
        
        // // Delegate processing to your existing PaymentController.
        // return app(\App\Http\Controllers\User\PaymentController::class)->processPayment($request);
    }
}
