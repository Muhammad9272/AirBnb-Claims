<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubPlan;
use App\Models\UserSubscription;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\Product;
use Stripe\Exception\ApiErrorException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Display the available subscription plans.
     *
     * @return \Illuminate\View\View
     */
    public function plans()
    {
        // Get all active subscription plans
        $plans = SubPlan::where('status', 1)->orderBy('price', 'asc')->get();
        
        return view('user.subscription.plans', compact('plans'));
    }
    
    /**
     * Show the checkout page for a specific plan
     * 
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function showCheckout($slug)
    {
        $plan = SubPlan::where('slug', $slug)->where('status', 1)->firstOrFail();
        
        // Check if user already has this plan
        $activeSubscription = auth()->user()->activeuserSubscriptions()->first();
        if ($activeSubscription && $activeSubscription->subplan_id == $plan->id) {
            return redirect()->route('user.subscription.plans')
                ->with('info', 'You are already subscribed to this plan.');
        }
        
        return view('user.subscription.checkout', compact('plan'));
    }
    
    /**
     * Process subscription payment with Stripe
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processSubscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:sub_plans,id',
            'payment_method' => 'required|in:stripe',
        ]);
        
        $plan = SubPlan::findOrFail($request->plan_id);
        $user = auth()->user();
        
        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            // First, create or get a product for the plan
            $stripeProduct = $this->getOrCreateStripeProduct($plan);
            
            // Then, create or get a price for the product
            $stripePrice = $this->getOrCreateStripePrice($plan, $stripeProduct->id);
            
            // Create pending subscription record first so we have the ID for success URL
            $subscription = new UserSubscription();
            $subscription->user_id = $user->id;
            $subscription->subplan_id = $plan->id;
            $subscription->payment_method = 'stripe';
            $subscription->status = 'pending';
            $subscription->price = $plan->price;
            $subscription->save();
            
            // Create a subscription checkout session
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $user->email,
                'client_reference_id' => $user->id,
                'line_items' => [
                    [
                        'price' => $stripePrice->id,
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
                'success_url' => route('subscription.success') . 
                                 '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('subscription.cancel'),
                'metadata' => [
                    'plan_slug' => $plan->slug,
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id,
                ],
                'subscription_data' => [
                    'metadata' => [
                        'plan_slug' => $plan->slug,
                        'user_id' => $user->id,
                        'subscription_id' => $subscription->id,
                    ],
                ],
            ]);
            
            // Update subscription with transaction ID
            $subscription->transaction_id = $checkout_session->id;
            $subscription->stripe_price = $stripePrice->id;
            $subscription->save();
            
            // Save session ID to session for verification
            $request->session()->put('stripe_session_id', $checkout_session->id);
            
            // Redirect to Stripe Checkout
            return redirect()->away($checkout_session->url);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return redirect()->route('user.subscription.plans')
                ->with('error', 'Payment processing error: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle successful subscription
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            
            // Log the session ID for debugging
            Log::info('Subscription success page accessed', ['session_id' => $sessionId]);
            
            // Verify session ID from our saved session or just proceed if it's available
            if (!$sessionId) {
                return redirect()->route('user.subscription.plans')
                    ->with('error', 'Invalid payment session.');
            }
            
            // Set Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));
            
            // Retrieve the session to get subscription details
            try {
                $session = Session::retrieve($sessionId);
                
                // Update subscription status
                $subscription = UserSubscription::where('transaction_id', $sessionId)
                    ->first();
                    
                if (!$subscription) {
                    // Try to find by Stripe subscription ID if it was already set by webhook
                    if (isset($session->subscription)) {
                        $subscription = UserSubscription::where('stripe_id', $session->subscription)->first();
                    }
                    
                    // If still not found, redirect to dashboard
                    if (!$subscription) {
                        Log::warning('Subscription not found for success page', ['session_id' => $sessionId]);
                        return redirect()->route('user.dashboard')
                            ->with('warning', 'Your subscription is being processed. Please check back shortly.');
                    }
                }
                
                // Update subscription with details from Stripe session
                $subscription->stripe_id = $session->subscription;
                $subscription->stripe_status = 'active';
                $subscription->status = 'active';
                
                // Set expires_at to 1 month from now if it's not set
                if (!$subscription->expires_at) {
                    $subscription->expires_at = now()->addMonth();
                }
                
                $subscription->save();
                
                // Clear session
                $request->session()->forget('stripe_session_id');
                
                return view('user.subscription.success', compact('subscription'));
                
            } catch (\Exception $e) {
                Log::error('Error retrieving stripe session: ' . $e->getMessage(), ['session_id' => $sessionId]);
                
                // If we can't get the session, but have the session ID, still try to find the subscription
                $subscription = UserSubscription::where('transaction_id', $sessionId)->first();
                
                if ($subscription) {
                    return view('user.subscription.success', compact('subscription'));
                }
                
                return redirect()->route('user.dashboard')
                    ->with('warning', 'Your subscription is being processed. Please check back shortly.');
            }
        } catch (\Exception $e) {
            Log::error('Exception in subscription success page: ' . $e->getMessage());
            return redirect()->route('user.dashboard')
                ->with('warning', 'There was an issue loading your subscription details. Please contact support if your subscription doesn\'t appear.');
        }
    }
    
    /**
     * Handle cancelled subscription checkout
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        return redirect()->route('user.subscription.plans')
            ->with('info', 'You have cancelled the subscription process.');
    }
    
    /**
     * Display user's subscription transactions
     */
    public function transactions()
    {
        $user = auth()->user();
        
        // Get user's subscription transactions/history using the correct relationship
        $transactions = $user->userSubscriptions()
            ->with('subplan')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('user.subscription.transactions', compact('transactions'));
    }
    
    /**
     * Calculate expiry date based on interval
     * 
     * @param string $interval
     * @return Carbon
     */
    private function calculateExpiryDate($interval)
    {
        $now = Carbon::now();
        
        return match($interval) {
            'weekly' => $now->addWeek(),
            'monthly' => $now->addMonth(),
            'quarterly' => $now->addMonths(3),
            'biannually' => $now->addMonths(6),
            'yearly' => $now->addYear(),
            default => $now->addMonth(), // Default to monthly
        };
    }
    
    /**
     * Get or create a Stripe product for the subscription plan
     * 
     * @param SubPlan $plan
     * @return \Stripe\Product
     */
    private function getOrCreateStripeProduct(SubPlan $plan)
    {
        // Check if the plan already has a product ID stored
        if ($plan->stripe_product_id) {
            try {
                // Try to retrieve the existing product
                return Product::retrieve($plan->stripe_product_id);
            } catch (ApiErrorException $e) {
                // Product not found or other error, we'll create a new one
                Log::warning("Stripe product not found: {$plan->stripe_product_id}, creating new one.");
            }
        }
        
        // Create a new product
        $product = Product::create([
            'name' => $plan->name,
            'description' => $plan->details ?? "Subscription plan: {$plan->name}",
            'metadata' => [
                'plan_id' => $plan->id,
            ],
        ]);
        
        // Store the product ID on the plan for future use
        $plan->stripe_product_id = $product->id;
        $plan->save();
        
        return $product;
    }
    
    /**
     * Get or create a Stripe price for the subscription plan
     * 
     * @param SubPlan $plan
     * @param string $productId
     * @return \Stripe\Price
     */
    private function getOrCreateStripePriceOld(SubPlan $plan, $productId)
    {
        // Check if the plan already has a price ID stored
        if ($plan->stripe_price_id) {
            try {
                // Try to retrieve the existing price
                return Price::retrieve($plan->stripe_price_id);
            } catch (ApiErrorException $e) {
                // Price not found or other error, we'll create a new one
                Log::warning("Stripe price not found: {$plan->stripe_price_id}, creating new one.");
            }
        }
        
        // Convert interval to Stripe format
        $interval = $this->getStripeInterval($plan->interval);
        
        // Create a new price
        $price = Price::create([
            'product' => $productId,
            'unit_amount' => (int)($plan->price * 100), // Convert to cents
            'currency' => 'usd',
            'recurring' => [
                'interval' => $interval['interval'],
                'interval_count' => $interval['interval_count'],
            ],
            'metadata' => [
                'plan_id' => $plan->id,
            ],
        ]);
        
        // Store the price ID on the plan for future use
        $plan->stripe_price_id = $price->id;
        $plan->save();
        
        return $price;
    }

    /**
     * Get or create a Stripe price for the subscription plan
     * 
     * @param SubPlan $plan
     * @param string $productId
     * @return \Stripe\Price
     */
    private function getOrCreateStripePrice(SubPlan $plan, $productId)
    {
        // Check if the plan already has a price ID stored
        if ($plan->stripe_price_id) {
            try {
                // Retrieve the existing price to compare
                $existingPrice = Price::retrieve($plan->stripe_price_id);
                
                // Check if the price matches our current plan price
                $currentPriceCents = (int)($plan->price * 100);
                
                if ($existingPrice->unit_amount === $currentPriceCents) {
                    // Price matches, return existing price
                    return $existingPrice;
                } else {
                    // Price has changed, we need to create a new one
                    Log::info("Price changed for plan {$plan->id}. Old: {$existingPrice->unit_amount}, New: {$currentPriceCents}. Creating new price.");
                }
                
            } catch (ApiErrorException $e) {
                // Price not found or other error, we'll create a new one
                Log::warning("Stripe price not found: {$plan->stripe_price_id}, creating new one.");
            }
        }
        
        // Convert interval to Stripe format
        $interval = $this->getStripeInterval($plan->interval);
        
        // Create a new price
        $price = Price::create([
            'product' => $productId,
            'unit_amount' => (int)($plan->price * 100), // Convert to cents
            'currency' => 'usd',
            'recurring' => [
                'interval' => $interval['interval'],
                'interval_count' => $interval['interval_count'],
            ],
            'metadata' => [
                'plan_id' => $plan->id,
                'created_at' => now()->toDateTimeString(), // For tracking when this price was created
            ],
        ]);
        
        // Archive/deactivate the old price if it exists
        if ($plan->stripe_price_id) {
            try {
                Price::update($plan->stripe_price_id, ['active' => false]);
                Log::info("Archived old Stripe price: {$plan->stripe_price_id}");
            } catch (ApiErrorException $e) {
                Log::warning("Could not archive old Stripe price: {$plan->stripe_price_id}. Error: " . $e->getMessage());
            }
        }
        
        // Store the new price ID on the plan
        $plan->stripe_price_id = $price->id;
        $plan->save();
        
        Log::info("Created new Stripe price {$price->id} for plan {$plan->id} with amount {$price->unit_amount}");
        
        return $price;
    }
    
    /**
     * Convert our internal interval to Stripe's format
     * 
     * @param string $interval
     * @return array
     */
    private function getStripeInterval($interval)
    {
        return match($interval) {
            'weekly' => ['interval' => 'week', 'interval_count' => 1],
            'monthly' => ['interval' => 'month', 'interval_count' => 1],
            'quarterly' => ['interval' => 'month', 'interval_count' => 3],
            'biannually' => ['interval' => 'month', 'interval_count' => 6],
            'yearly' => ['interval' => 'year', 'interval_count' => 1],
            default => ['interval' => 'month', 'interval_count' => 1],
        };
    }
}
