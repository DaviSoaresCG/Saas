<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $tenantSlug = $request->route('slug'); // slug do domínio
        $user = auth()->user();

        //verifica se o usuario est
        if ($user && $user->slug !== $tenantSlug) {
            // Opcional: deslogar o usuário
            auth()->logout();

            // Redirecionar para login do tenant correto
            return redirect()->route('login', ['slug' => $tenantSlug])
                ->withErrors(['email' => 'Você não tem acesso a este painel.']);
        }

        return $next($request);
    }
}
