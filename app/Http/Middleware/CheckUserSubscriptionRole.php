<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserSubscriptionRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */



    public function handle($request, Closure $next, $planId)
    {

        $activeSubscription = auth()->user()->subscriptionsActive();
                                                                               
        // Check if the authenticated user has an active subscription with the specified plan ID
        if ( $activeSubscription && $activeSubscription->subplan && $activeSubscription->subplan->id==$planId)  {
            return $next($request);
        }

        // Redirect or return an error response if the user doesn't have the required subscription plan
        return redirect()->route('user.dashboard')->with('error', 'You do not have permission to access this page.');
    }


}
