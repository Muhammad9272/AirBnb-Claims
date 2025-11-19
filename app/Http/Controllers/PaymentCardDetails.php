<?php

namespace App\Http\Controllers;
use Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentCardDetails extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function cardSuccess(Request $request)
    {
        if (!$request->has('setup_intent')) {
            return redirect()->route('dashboard')->with('error', 'Card setup failed.');
        }

        return redirect()->route('user.dashboard')->with('success', 'Your card was added successfully.');
    }

    public function cardIndex()
    {
        $user = Auth::user();
        $setupIntent = $user->createSetupIntent();

        return view('user.card-info.add-card', [
                'clientSecret' => $setupIntent->client_secret,
                'setupIntentId' => $setupIntent->id
            ]);
    }

}
