<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfPaymentComplete
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->isLojaAtiva()) {
            if (!empty($user->slug)) {
                $scheme = $request->secure() ? 'https://' : 'http://';
                return redirect()->away($scheme . $user->slug . '.' . env('APP_DOMAIN') . '/dashboard');
            }
            
            return redirect()->route('dashboard', ['slug' => $user->slug]);
        }

        return $next($request);
    }
}
