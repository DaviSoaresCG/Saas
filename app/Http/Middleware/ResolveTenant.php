<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return redirect()->away('http://' . env('APP_DOMAIN'));
        }

        $user = User::where('slug', $slug)->firstOrFail();
        // dd($slug, $user);

        if (!$user->subscribed(env('STRIPE_PRODUCT_ID'))) {
            // return redirect()->away('http://' . env('APP_DOMAIN'));
            return redirect()->away('http://127.0.0.1:8000');
        }

        app()->instance(User::class, $user);

        return $next($request);
    }
}
