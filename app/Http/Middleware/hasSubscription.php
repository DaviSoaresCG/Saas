<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class hasSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // check if user has subscription
        if(Auth::check()){
            if(!Auth::user()->subscribed(env('STRIPE_PRODUCT_ID'))){
                return redirect()->route('plans');
            }else{
                return $next($request);
            }
        }else{
            return redirect()->route('plans');
        }
    }
}
