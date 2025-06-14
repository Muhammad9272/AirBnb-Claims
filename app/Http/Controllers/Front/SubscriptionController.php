<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubPlan;

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
        
        // Get user's active subscription if logged in
        $activeSubscription = auth()->check() ? auth()->user()->activeuserSubscriptions()->first() : null;
        
        return view('front.subscription.plans', compact('plans', 'activeSubscription'));
    }
}
