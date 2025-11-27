<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserBelongsToTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Se não estiver logado, deixa passar (o middleware 'auth' cuida disso depois)
        if (! $user) {
            return $next($request);
        }

        //Subdomínio Atual
        $host = $request->getHost();
        $baseDomain = env('APP_DOMAIN'); // ex: saas.test
        $currentSlug = str_replace('.' . $baseDomain, '', $host);


        //se estiver dentro do app principal
        if ($currentSlug === $host || $currentSlug === 'www') {
             return $next($request);
        }

        //verifica se o usuario est
        if ($user->slug !== $currentSlug) {

            Auth::guard('web')->logout();
            // Redirecionar para login do tenant correto
            return redirect()->route('login')
                ->withErrors(['email' => 'Você não acesso este painel.(middleware)']);
        }

        return $next($request);
    }
}
