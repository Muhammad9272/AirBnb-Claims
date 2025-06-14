<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use App\Models\User;
use App\Models\SubPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use UnexpectedValueException;
use Carbon\Carbon;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Add immediate logging at the very start
        Log::info('Stripe webhook request received at: ' . now()->toDateTimeString(), [
            'remote_ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'content_length' => $request->header('Content-Length'),
            'signature' => $request->header('Stripe-Signature') ? 'present' : 'missing'
        ]);

        try {
            $payload = $request->getContent();
            
            // Log the first 500 characters of payload to verify content
            Log::info('Payload sample: ' . substr($payload, 0, 500) . (strlen($payload) > 500 ? '...' : ''));
            
            $sig_header = $request->header('Stripe-Signature');
            $endpoint_secret = config('services.stripe.webhook_secret');

            // If webhook secret is not configured, log a warning but continue processing for development purposes
            if (empty($endpoint_secret)) {
                Log::warning('Stripe webhook received but no webhook secret is configured. Skipping signature verification.');
                try {
                    // Parse payload without verification
                    $event = json_decode($payload, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('Invalid JSON payload');
                    }
                    
                    // Create a simple object structure like Stripe's event object
                    $eventObj = new \stdClass();
                    $eventObj->type = $event['type'] ?? 'unknown';
                    $eventObj->data = new \stdClass();
                    $eventObj->data->object = json_decode(json_encode($event['data']['object'] ?? []), false);
                    
                    // Continue processing with unverified data (only in development!)
                    Log::info('Processing unverified Stripe event in development mode: ' . $eventObj->type);
                } catch (\Exception $e) {
                    Log::error('Failed to parse webhook payload: ' . $e->getMessage());
                    return response()->json(['error' => 'Invalid payload'], 400);
                }
            } else {
                try {
                    Stripe::setApiKey(config('services.stripe.secret'));
                    $eventObj = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
                } catch (UnexpectedValueException $e) {
                    // Invalid payload
                    Log::error('Stripe Webhook Error: ' . $e->getMessage());
                    return response()->json(['error' => 'Invalid payload'], 400);
                } catch (SignatureVerificationException $e) {
                    // Invalid signature
                    Log::error('Stripe Signature Error: ' . $e->getMessage());
                    return response()->json(['error' => 'Invalid signature'], 400);
                }
            }

            Log::info('Stripe webhook received: ' . $eventObj->type);

            // Handle the event
            switch ($eventObj->type) {
                case 'checkout.session.completed':
                    $this->handleCheckoutSessionCompleted($eventObj->data->object);
                    break;
                    
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($eventObj->data->object);
                    break;
                    
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($eventObj->data->object);
                    break;
                    
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($eventObj->data->object);
                    break;
                    
                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($eventObj->data->object);
                    break;
                    
                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($eventObj->data->object);
                    break;
                    
                default:
                    Log::info('Unhandled Stripe event: ' . $eventObj->type);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Exception in webhook handler: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a 200 response even on error to prevent Stripe from retrying
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    private function handleCheckoutSessionCompleted($session)
    {
        Log::info('Checkout session completed: ' . $session->id);

        // For subscription mode, we'll get a subscription ID
        if ($session->mode === 'subscription' && isset($session->subscription)) {
            // The subscription is now active, but we'll update details in the subscription.created event
            Log::info('Subscription created in checkout: ' . $session->subscription);
            
            // Find the subscription by transaction ID
            $subscription = UserSubscription::where('transaction_id', $session->id)->first();
            if ($subscription) {
                $subscription->stripe_id = $session->subscription;
                $subscription->save();
            }
        } 
        // For one-time payments (if you still support them)
        else if ($session->mode === 'payment') {
            // Find the subscription by transaction ID
            $subscription = UserSubscription::where('transaction_id', $session->id)
                ->where('status', 'pending')
                ->first();

            if ($subscription) {
                $subscription->status = 'active';
                $subscription->save();
                
                Log::info('One-time payment subscription activated: ' . $subscription->id);
            }
        }
    }

    private function handleSubscriptionCreated($stripeSubscription)
    {
        Log::info('Subscription created: ' . $stripeSubscription->id);
        
        $this->updateSubscriptionFromStripe($stripeSubscription);
    }

    private function handleSubscriptionUpdated($stripeSubscription)
    {
        Log::info('Subscription updated: ' . $stripeSubscription->id);
        
        $this->updateSubscriptionFromStripe($stripeSubscription);
    }

    private function handleSubscriptionDeleted($stripeSubscription)
    {
        Log::info('Subscription deleted: ' . $stripeSubscription->id);
        
        // Find the subscription by Stripe ID
        $subscription = UserSubscription::where('stripe_id', $stripeSubscription->id)->first();
        
        if ($subscription) {
            $subscription->status = 'canceled';
            $subscription->stripe_status = $stripeSubscription->status;
            $subscription->canceled_at = now();
            $subscription->ends_at = now();
            $subscription->save();
            
            Log::info('Subscription canceled in database: ' . $subscription->id);
        }
    }

    private function handleInvoicePaymentSucceeded($invoice)
    {
        if (!isset($invoice->subscription)) {
            Log::info('Invoice without subscription: ' . $invoice->id);
            return;
        }
        
        Log::info('Invoice payment succeeded for subscription: ' . $invoice->subscription);
        
        // Find the subscription by Stripe ID
        $subscription = UserSubscription::where('stripe_id', $invoice->subscription)->first();
        
        if ($subscription) {
            // Get the subscription period from the invoice
            $periodStart = Carbon::createFromTimestamp($invoice->period_start);
            $periodEnd = Carbon::createFromTimestamp($invoice->period_end);
            
            // Update subscription end date
            $subscription->status = 'active';
            $subscription->stripe_status = 'active';
            $subscription->ends_at = $periodEnd;
            $subscription->save();
            
            Log::info('Subscription period updated: ' . $subscription->id . ' - Ends: ' . $periodEnd);
            
            // We no longer need to reset user's claims count since we're tracking via the claims table
        }
    }

    private function handleInvoicePaymentFailed($invoice)
    {
        if (!isset($invoice->subscription)) {
            Log::info('Invoice without subscription: ' . $invoice->id);
            return;
        }
        
        Log::info('Invoice payment failed for subscription: ' . $invoice->subscription);
        
        // Find the subscription by Stripe ID
        $subscription = UserSubscription::where('stripe_id', $invoice->subscription)->first();
        
        if ($subscription) {
            $subscription->stripe_status = 'past_due';
            $subscription->save();
            
            Log::info('Subscription marked as past due: ' . $subscription->id);
            
            // Here you could implement notification logic to alert the customer
        }
    }

    /**
     * Update subscription details from Stripe subscription object
     */
    private function updateSubscriptionFromStripe($stripeSubscription)
    {
        // Find subscription by Stripe ID
        $subscription = UserSubscription::where('stripe_id', $stripeSubscription->id)->first();
        
        // If not found by Stripe ID, check metadata for initial connection
        if (!$subscription && isset($stripeSubscription->metadata->subscription_id)) {
            $subscription = UserSubscription::find($stripeSubscription->metadata->subscription_id);
        } elseif (!$subscription && isset($stripeSubscription->metadata->user_id) && isset($stripeSubscription->metadata->plan_slug)) {
            $plan = SubPlan::where('slug', $stripeSubscription->metadata->plan_slug)->first();
            
            if ($plan) {
                $subscription = UserSubscription::where('user_id', $stripeSubscription->metadata->user_id)
                    ->where('subplan_id', $plan->id)
                    ->where('stripe_id', null)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($subscription) {
                    $subscription->stripe_id = $stripeSubscription->id;
                }
            }
        }
        
        if ($subscription) {
            // Get the current period end
            $currentPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
            
            // Update subscription details
            $subscription->stripe_status = $stripeSubscription->status;
            $subscription->ends_at = $currentPeriodEnd;
            
            // Update status based on Stripe status
            if ($stripeSubscription->status === 'active') {
                $subscription->status = 'active';
            } else if ($stripeSubscription->status === 'canceled') {
                $subscription->status = 'canceled';
                $subscription->canceled_at = now();
            } else if ($stripeSubscription->status === 'unpaid' || $stripeSubscription->status === 'past_due') {
                // You might want to handle these differently
                $subscription->status = 'problem';
            }
            
            // Handle trial period if present
            if (isset($stripeSubscription->trial_end) && $stripeSubscription->trial_end) {
                $subscription->trial_ends_at = Carbon::createFromTimestamp($stripeSubscription->trial_end);
            }
            
            $subscription->save();
            
            Log::info('Subscription updated from Stripe: ' . $subscription->id);
        } else {
            Log::warning('Subscription not found for Stripe ID: ' . $stripeSubscription->id);
        }
    }
}
