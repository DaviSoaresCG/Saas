<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        $slug = $request->route('slug');
        if (! $slug) {
            $host = $request->getHost();
            $base = env('APP_DOMAIN');
            $slug = str_replace('.'.$base, '', $host);
            if ($slug == $host) {
                $slug = null;
            }
        }
        if ($slug) {

            $user = User::where('slug', $slug)->first();

            if (! $user) {
                return redirect()->away('https://'.env("APP_DOMAIN"));
            }

            if (! $user->subscribed() || ! $user->hasVerifiedEmail()) {
                return redirect()->away('https://'.env('APP_DOMAIN'));
                // retornar uma view de aviso de loja inativa
            }

            app()->instance(User::class, $user);
            // disponibiliza nas rotas
            URL::defaults(['slug' => $slug]);

            // disponibiliza nas views
        }

        return $next($request);
    }
}
