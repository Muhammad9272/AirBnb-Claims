<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubPlan;
use App\Models\UserSubscription;
use App\Models\User;
use App\Models\ReferralTransaction;
use App\Models\WalletTransaction;
use App\Models\Lead;
use App\Models\GeneralSetting;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\Product;
use Stripe\Exception\ApiErrorException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\{Helpers, CheckoutLogics};
class SubscriptionController extends Controller
{
    /**
     * Validate discount code via AJAX
     */
    public function validateDiscount(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string',
            'plan_id' => 'required|exists:sub_plans,id',
        ]);

        $gs = GeneralSetting::first();
        $plan = SubPlan::find($request->plan_id);
        $user = auth()->user();
        $code = strtoupper(trim($request->discount_code));

        // Check if it's the lead funnel discount code
        if ($code === strtoupper($gs->lead_discount_code)) {
            // Validate user is in leads table and hasn't used the code
            $lead = Lead::where('email', $user->email)
                ->where('discount_code_used', false)
                ->first();

            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => 'This discount code is not valid for your account'
                ]);
            }

            $discountPercentage = Helpers::getPrice($gs->lead_discount_percentage ?? 0);
            $discountAmount = ($plan->price * $discountPercentage) / 100;

            return response()->json([
                'success' => true,
                'discount_amount' => Helpers::getPrice($discountAmount),
                'discount_percentage' => $discountPercentage,
                'message' => "Discount applied successfully!"
            ]);
        }

        // Add other discount code types here (custom codes, promotional codes, etc.)

        return response()->json([
            'success' => false,
            'message' => 'Invalid discount code'
        ]);
    }

    /**
     * Show the checkout page for a specific plan
     */
    public function showCheckout($slug)
    {
        $plan = SubPlan::where('slug', $slug)->where('status', 1)->firstOrFail();
        $user = auth()->user();
        
        // Check if user already has this plan
        $activeSubscription = $user->activeuserSubscriptions()->first();
        if ($activeSubscription && $activeSubscription->subplan_id == $plan->id) {
            return redirect()->route('user.subscription.plans')
                ->with('info', 'You are already subscribed to this plan.');
        }
        
        return view('user.subscription.checkout', compact('plan'));
    }
    
    /**
     * Process subscription payment with Stripe
     */
    public function processSubscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:sub_plans,id',
            'payment_method' => 'required|in:stripe',
            'discount_code' => 'nullable|string',
            'use_wallet' => 'nullable|boolean',
            'agreement_accepted' => 'required|accepted',
        ]);
        
        $plan = SubPlan::findOrFail($request->plan_id);
        $user = auth()->user();
        
        // Calculate final pricing
        $pricing = $this->calculatePricing($plan, $user, $request->discount_code, $request->use_wallet);
        
        // Validate discount code if provided
        if ($request->discount_code && !$pricing['discount_code']) {
            return redirect()->back()
                ->with('error', 'Invalid discount code')
                ->withInput();
        }
        
        // If amount is 0 or negative (covered by wallet/discount), create subscription directly
        if ($pricing['final_amount'] <= 0) {
            return $this->createFreeSubscription($plan, $user, $pricing);
        }
        
        // Do not remove this code - Its needed to check for recurring Payments.
        // $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        
        // $testClock = $stripe->testHelpers->testClocks->create([
        //     'frozen_time' => time(),
        // ]);

        // $stripeCustomer = $stripe->customers->create([
        //     'email' => $user->email,
        //     'test_clock' => $testClock->id,
        // ]);
        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            $stripeProduct = $this->getOrCreateStripeProduct($plan);
            
            // Create pending subscription with all discount details
            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'subplan_id' => $plan->id,
                'payment_method' => 'stripe',
                'status' => 'pending',
                'price' => $plan->price,
                'discount_code' => $pricing['discount_code'],
                'discount_amount' => $pricing['discount_amount'],
                'discount_percentage' => $pricing['discount_percentage'],
                'wallet_credit_used' => $pricing['wallet_used'],
                'amount_paid' => $pricing['final_amount'],
            ]);
            
            // Create one-time payment session (not recurring subscription)
            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $user->email,
                'client_reference_id' => $user->id,
                'saved_payment_method_options' => ['payment_method_save' => 'enabled'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product' => $stripeProduct->id,
                            'unit_amount' => (int)($pricing['final_amount'] * 100),
                            'recurring' => [
                                'interval' => $this->getStripeInterval($plan->interval)['interval'],
                                'interval_count' => $this->getStripeInterval($plan->interval)['interval_count'],
                            ],
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
                'subscription_data' => [
                    'metadata' => [
                        'plan_slug' => $plan->slug,
                        'user_id' => $user->id,
                        'subscription_id' => $subscription->id,
                    ],
                    'default_payment_method' => null, 
                ],
                'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('subscription.cancel'),
            ]);

            
            $subscription->transaction_id = $checkout_session->id;
            $subscription->save();
            
            $request->session()->put('stripe_session_id', $checkout_session->id);
            
            return redirect()->away($checkout_session->url);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return redirect()->route('user.subscription.plans')
                ->with('error', 'Payment processing error: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful subscription
     */
    public function success(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            
            if (!$sessionId) {
                return redirect()->route('user.subscription.plans')
                    ->with('error', 'Invalid payment session.');
            }
            
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $session = Session::retrieve($sessionId);
            $subscription = UserSubscription::where('transaction_id', $sessionId)->first();
                
            if (!$subscription) {
                if (isset($session->subscription)) {
                    $subscription = UserSubscription::where('stripe_id', $session->subscription)->first();
                }
                
                if (!$subscription) {
                    return redirect()->route('user.dashboard')
                        ->with('warning', 'Your subscription is being processed.');
                }
            }

            // Check if already processed (prevent duplicate processing on refresh)
            if ($subscription->status === 'active' && $subscription->stripe_status === 'active') {
                // Already processed, just show the success page
                return view('user.subscription.success', compact('subscription'));
            }

            DB::beginTransaction();
            try {
                $subscription->update([
                    'stripe_id' => $session->subscription,
                    'stripe_status' => 'active',
                    'status' => 'active',
                    'expires_at' => $subscription->expires_at ?? $this->calculateExpiryDate($subscription->subplan->interval),
                ]);

                // Deduct wallet if used
                if ($subscription->wallet_credit_used > 0) {
                    $this->deductWallet(auth()->user(), $subscription->wallet_credit_used, $subscription);
                }

                // Mark lead discount as used
                if ($subscription->discount_code) {
                    $this->markDiscountAsUsed(auth()->user()->email);
                }

                // Process referral bonus ONLY for regular users (not influencers)
                $this->processReferralBonus($subscription);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
            $request->session()->forget('stripe_session_id');
            
            return view('user.subscription.success', compact('subscription'));
            
        } catch (\Exception $e) {
            Log::error('Subscription success error: ' . $e->getMessage());
            return redirect()->route('user.dashboard')
                ->with('warning', 'Your subscription is being processed.');
        }
    }

    /**
     * Calculate pricing with discounts and wallet credit
     * ENSURES final amount is never negative
     */
    private function calculatePricing(SubPlan $plan, User $user, $discountCode = null, $useWallet = false)
    {
        $gs = GeneralSetting::first();
        $originalPrice = Helpers::getPrice($plan->price);
        $discountAmount = 0;
        $discountPercentage = 0;
        $validDiscountCode = null;
        
        // Apply discount code if provided
        if ($discountCode) {
            $code = strtoupper(trim($discountCode));
            
            // Check if it's the lead funnel discount code
            if ($code === strtoupper($gs->lead_discount_code)) {
                // Validate user is in leads table and hasn't used the code
                $lead = Lead::where('email', $user->email)
                    ->where('discount_code_used', false)
                    ->first();
                
                if ($lead) {
                    $discountPercentage = Helpers::getPrice($gs->lead_discount_percentage ?? 0);
                    $discountAmount = ($originalPrice * $discountPercentage) / 100;
                    $validDiscountCode = $code;
                }
            }
            // Add other discount code types here
        }
        
        $priceAfterDiscount = max(0, $originalPrice - $discountAmount);
        
        // Apply wallet credit if requested
        $walletUsed = 0;
        if ($useWallet && $user->wallet_balance > 0 && $priceAfterDiscount > 0) {
            $walletUsed = min(Helpers::getPrice($user->wallet_balance), $priceAfterDiscount);
        }
        
        // IMPORTANT: Ensure final amount is never negative
        $finalAmount = max(0, $priceAfterDiscount - $walletUsed);
        
        return [
            'original_price' => Helpers::getPrice($originalPrice),
            'discount_code' => $validDiscountCode,
            'discount_amount' => Helpers::getPrice($discountAmount),
            'discount_percentage' => Helpers::getPrice($discountPercentage),
            'price_after_discount' => Helpers::getPrice($priceAfterDiscount),
            'wallet_used' => Helpers::getPrice($walletUsed),
            'final_amount' => Helpers::getPrice($finalAmount),
        ];
    }

    /**
     * Create subscription without payment (covered by wallet/discount)
     */
    private function createFreeSubscription(SubPlan $plan, User $user, array $pricing)
    {
        DB::beginTransaction();
        try {
            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'subplan_id' => $plan->id,
                'payment_method' => 'wallet',
                'status' => 'active',
                'price' => $plan->price,
                'discount_code' => $pricing['discount_code'],
                'discount_amount' => $pricing['discount_amount'],
                'discount_percentage' => $pricing['discount_percentage'],
                'wallet_credit_used' => $pricing['wallet_used'],
                'amount_paid' => 0.00,
                'expires_at' => $this->calculateExpiryDate($plan->interval),
            ]);

            // Deduct wallet if used
            if ($pricing['wallet_used'] > 0) {
                $this->deductWallet($user, $pricing['wallet_used'], $subscription);
            }

            // Mark lead discount as used
            if ($pricing['discount_code']) {
                $this->markDiscountAsUsed($user->email);
            }

            // Process referral bonus
            $this->processReferralBonus($subscription);

            DB::commit();

            return redirect()->route('user.dashboard')
                ->with('success', 'Subscription activated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Free subscription error: ' . $e->getMessage());
            return redirect()->route('user.subscription.plans')
                ->with('error', 'Subscription failed. Please try again.');
        }
    }

    /**
     * Process referral bonus for REGULAR users only (NOT influencers)
     * Calculated based on amount_paid (after discounts)
     */
    private function processReferralBonus(UserSubscription $subscription)
    {
        $user = User::find($subscription->user_id);
        
        if (!$user || !$user->referred_by) {
            return;
        }

        $gs = GeneralSetting::first();
        if (!$gs || $gs->is_affiliate != 1) {
            return;
        }

        $referrer = User::find($user->referred_by);
        if (!$referrer) {
            return;
        }

        // IMPORTANT: Only process for regular users (role_id = 1)
        // Influencers (role_id = 2) get commission on APPROVED CLAIMS, not subscriptions
        if ($referrer->role_id == 2) {
            Log::info("Skipping referral bonus - referrer is influencer", [
                'referrer_id' => $referrer->id,
                'customer_id' => $user->id,
            ]);
            return;
        }

        DB::beginTransaction();
        try {
            // Calculate credit based on AMOUNT_PAID (after discount), not original price
            $rewardPercentage = Helpers::getPrice($gs->referral_reward_percentage ?? 10);
            $amountPaid = Helpers::getPrice($subscription->amount_paid);
            $creditAmount = Helpers::getPrice( ($amountPaid * $rewardPercentage) / 100);

            // Skip if amount is 0
            if ($creditAmount <= 0) {
                DB::commit();
                return;
            }

            // CHECKPOINT: Check referral bonus type setting
            $bonusType = $gs->referral_bonus_type ?? 'once'; // Default to 'once'


            if ($bonusType === 'once') {
                // For 'once' type, create ONLY if it doesn't exist (no update)
                $referralTransaction = ReferralTransaction::firstOrCreate(
                    [
                        'referrer_user_id' => $referrer->id,
                        'referee_user_id' => $user->id,
                    ],
                    [
                        'subscription_id' => $subscription->id,
                        'credit_amount' => $creditAmount,
                        'status' => 'completed',
                    ]
                );

            } else {
                // For 'unlimited' type, create new record each time
                $referralTransaction = ReferralTransaction::create([
                    'referrer_user_id' => $referrer->id,
                    'referee_user_id' => $user->id,
                    'subscription_id' => $subscription->id,
                    'credit_amount' => $creditAmount,
                    'status' => 'completed',
                ]);
            }

            // Update referrer's wallet
            $balanceBefore = Helpers::getPrice($referrer->wallet_balance);
            $balanceAfter = $balanceBefore + $creditAmount;

            $referrer->wallet_balance = $balanceAfter;
            $referrer->save();

            // Create wallet transaction
            // WalletTransaction::create([
            //     'user_id' => $referrer->id,
            //     'transaction_type' => 'referral_earned',
            //     'amount' => $creditAmount,
            //     'related_user_id' => $user->id,
            //     'related_subscription_id' => $subscription->id,
            //     'related_referral_transaction_id' => $referralTransaction->id,
            //     'balance_before' => $balanceBefore,
            //     'balance_after' => $balanceAfter,
            //     'description' => "Referral bonus from {$user->name} (${$amountPaid} subscription)",
            // ]);

            $dd = WalletTransaction::create([
                'user_id' => $referrer->id,
                'transaction_type' => 'referral_earned',
                'amount' => $creditAmount,
                'related_user_id' => $user->id,
                'related_subscription_id' => $subscription->id,
                'related_referral_transaction_id' => $referralTransaction->id,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => "Referral bonus from {$user->name} (\${$amountPaid} subscription)",
                'created_at' => now(),
            ]);

            DB::commit();

            Log::info("Referral bonus credited", [
                'referrer_id' => $referrer->id,
                'referee_id' => $user->id,
                'amount_paid' => $amountPaid,
                'credit_amount' => $creditAmount,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Referral bonus error: ' . $e->getMessage());
        }
    }

    /**
     * Deduct wallet credit from user
     */
    private function deductWallet(User $user, float $amount, UserSubscription $subscription)
    {
        $balanceBefore = Helpers::getPrice($user->wallet_balance);
        $balanceAfter = max(0, $balanceBefore - $amount);

        $user->wallet_balance = $balanceAfter;
        $user->save();

        WalletTransaction::create([
            'user_id' => $user->id,
            'transaction_type' => 'subscription_used',
            'amount' => -$amount,
            'related_subscription_id' => $subscription->id,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => "Applied to subscription #{$subscription->id}",
            'created_at' => now(),
        ]);
    }

    /**
     * Mark lead discount code as used
     */
    private function markDiscountAsUsed(string $email)
    {
        Lead::where('email', $email)
            ->where('discount_code_used', false)
            ->update([
                'discount_code_used' => true,
                'is_registered' => true,
                'registered_user_id' => auth()->id(),
                'status' => 'converted',
            ]);
    }

    /**
     * Calculate expiry date based on interval
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
            default => $now->addMonth(),
        };
    }

    /**
     * Handle cancelled subscription checkout
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
        
        $transactions = $user->userSubscriptions()
            ->with('subplan')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('user.subscription.transactions', compact('transactions'));
    }

    /**
     * Get or create a Stripe product for the subscription plan
     */
    private function getOrCreateStripeProduct(SubPlan $plan)
    {
        if ($plan->stripe_product_id) {
            try {
                $product = Product::retrieve($plan->stripe_product_id);
                
                // Update the product name in Stripe if it has changed
                if ($product->name !== $plan->name || $product->description !== ($plan->details ?? "Subscription plan: {$plan->name}")) {
                    $product = Product::update(
                        $plan->stripe_product_id,
                        [
                            'name' => $plan->name,
                            'description' => $plan->details ?? "Subscription plan: {$plan->name}",
                        ]
                    );
                    Log::info("Updated Stripe product name for plan {$plan->id}: {$plan->name}");
                }
                
                return $product;
            } catch (ApiErrorException $e) {
                Log::warning("Stripe product not found: {$plan->stripe_product_id}, creating new one.");
            }
        }
        
        $product = Product::create([
            'name' => $plan->name,
            'description' => $plan->details ?? "Subscription plan: {$plan->name}",
            'metadata' => [
                'plan_id' => $plan->id,
            ],
        ]);
        
        $plan->stripe_product_id = $product->id;
        $plan->save();
        
        return $product;
    }

    /**
     * Get or create a Stripe price for the subscription plan
     */
    private function getOrCreateStripePrice(SubPlan $plan, $productId)
    {
        if ($plan->stripe_price_id) {
            try {
                $existingPrice = Price::retrieve($plan->stripe_price_id);
                $currentPriceCents = (int)($plan->price * 100);
                
                if ($existingPrice->unit_amount === $currentPriceCents) {
                    return $existingPrice;
                } else {
                    Log::info("Price changed for plan {$plan->id}. Creating new price.");
                }
            } catch (ApiErrorException $e) {
                Log::warning("Stripe price not found: {$plan->stripe_price_id}, creating new one.");
            }
        }
        
        $interval = $this->getStripeInterval($plan->interval);
        
        $price = Price::create([
            'product' => $productId,
            'unit_amount' => (int)($plan->price * 100),
            'currency' => 'usd',
            'recurring' => [
                'interval' => $interval['interval'],
                'interval_count' => $interval['interval_count'],
            ],
            'metadata' => [
                'plan_id' => $plan->id,
                'created_at' => now()->toDateTimeString(),
            ],
        ]);
        
        if ($plan->stripe_price_id) {
            try {
                Price::update($plan->stripe_price_id, ['active' => false]);
            } catch (ApiErrorException $e) {
                Log::warning("Could not archive old Stripe price: " . $e->getMessage());
            }
        }
        
        $plan->stripe_price_id = $price->id;
        $plan->save();
        
        return $price;
    }

    /**
     * Convert internal interval to Stripe's format
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