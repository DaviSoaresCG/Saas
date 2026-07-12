<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfSubscriptionActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isLojaAtiva()) {
            return redirect()->away('http://' . Auth::user()->slug . '.' . env('APP_DOMAIN') . '/dashboard');
        }

        return $next($request);
    }
}
