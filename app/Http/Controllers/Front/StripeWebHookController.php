<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Laravel\Cashier\Subscription;
use Stripe\PaymentIntent as StripePaymentIntent;
use App\Models\User;
use Stripe\Transfer;
class StripeWebHookController extends WebhookController
{

    public function handleTransferEvents(array $payload)
    {
        $transferId = $payload['data']['object']['id'];
        $transfer = Transfer::retrieve($transferId);

        // Check if transfer succeeded or failed
        if ($transfer->status === 'paid') {
            // Handle successful transfer
            // Code to update user's balance, add transaction record, etc.
        } elseif ($transfer->status === 'failed') {
            // Handle failed transfer
            // Code to notify user or admin about the failure, etc.
        }
    }

    public function handle(Request $request)
    {
        $payload = $this->parseStripeEvent($request);

        if (method_exists($this, 'handle' . $payload['type'])) {
            return $this->{'handle' . $payload['type']}($payload);
        }

        return $this->missingMethod();
    }


    // public function handleWebhook(Request $request)
    // {
    //     // Verify the authenticity of the webhook request
    //     $payload = $request->getContent();
    //     $sigHeader = $request->header('Stripe-Signature');
    //     $event = null;
    //     try {
    //         $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, env('STRIPE_WEBHOOK_SECRET'));
    //     } catch(\UnexpectedValueException $e) {
    //         // Invalid payload
    //         return response()->json(['error' => 'Invalid payload'], 400);
    //     } catch(\Stripe\Exception\SignatureVerificationException $e) {
    //         // Invalid signature
    //         return response()->json(['error' => 'Invalid signature'], 400);
    //     }
        
    //     // Handle the webhook event
    //     if ($event->type == 'transfer.paid') {
    //         $transfer = $event->data->object;
    //         $withdraw = Withdraw::where('transfer_id', $transfer->id)->first();
    //         if ($withdraw) {
    //             // Update the withdrawal status to completed
    //             $withdraw->status = 'completed';
    //             $withdraw->save();
                
    //             // Update the user's wallet balance
    //             $user = $withdraw->user;
    //             $user->wallet += $withdraw->amount;
    //             $user->save();
    //         }
    //     }
        
    //     return response()->json(['success' => true]);
    // }


    
}


