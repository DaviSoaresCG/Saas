<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class noSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = app(User::class);
        // check if the user has no subscription
        if(Auth::check() && Auth::user()->subscribed(env('STRIPE_PRODUCT_ID'))){
            return redirect()->away('http://' .$user->slug. env('APP_DOMAIN'));
        }else{
            return redirect()->route('register');
        }

        return $next($request);
    }
}
