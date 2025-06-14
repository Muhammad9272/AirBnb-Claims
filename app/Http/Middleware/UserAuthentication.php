<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   
        if (auth()->check()) {
            // Check if user has favorites
            // if (auth()->user()->favorites->isEmpty()) {
            //     // Redirect or return response if user does not have favorites
            //     return redirect()->route('front.pick-interests');
            // }else
            if(!auth()->user()->pickinterest_visited){
                // if(!auth()->user()->internshipgraduate){
                return redirect()->route('front.pick-interests','step2')->with('info','Please Pick your interest first!!');
            }else if(auth()->user()->internshipgraduate){
                return $next($request);
            }
        }
        return $next($request);
    }
}
