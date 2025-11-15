<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // pega o subdomínio
        $slug = explode('.', $request->getHost())[0];

        if ($slug === 'www' || $slug === env('APP_DOMAIN')) {
            return redirect()->away('http://'.env('APP_DOMAIN'));

            // return redirect()->away('http://127.0.0.1:8000');
        }

        $user = User::where('slug', $slug)->firstOrFail();
        // dd($slug, $user);

        if (! $user->subscribed(env('STRIPE_PRODUCT_ID'))) {
            return redirect()->away('http://'.env('APP_DOMAIN'));
            // return redirect()->away('http://127.0.0.1:8000');
        } elseif (! $user->hasVerifiedEmail()) {
            return redirect()->away('http://'.env('APP_DOMAIN'));
        }

        app()->instance(User::class, $user);

        return $next($request);
    }
}
