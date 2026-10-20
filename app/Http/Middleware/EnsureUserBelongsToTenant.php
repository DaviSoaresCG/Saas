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

        if (! $user) {
            return $next($request);
        }

        // Super Admin possui acesso irrestrito para suporte e auditoria
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Subdomínio Atual
        $host = $request->getHost();
        $baseDomain = config('app.domain', env('APP_DOMAIN', 'lvh.me'));
        $currentSlug = str_replace('.' . $baseDomain, '', $host);

        // Se estiver dentro do app principal ou www
        if ($currentSlug === $host || $currentSlug === 'www' || in_array($host, ['localhost', '127.0.0.1'])) {
            return $next($request);
        }

        // Verifica se o usuário autenticado pertence ao tenant deste subdomínio
        if ($user->slug !== $currentSlug) {
            Auth::guard('web')->logout();

            $port = $request->getPort() && !in_array($request->getPort(), [80, 443]) ? ':' . $request->getPort() : '';
            $scheme = $request->secure() ? 'https://' : 'http://';
            $correctLoginUrl = !empty($user->slug)
                ? "{$scheme}{$user->slug}.{$baseDomain}{$port}/login"
                : "{$scheme}{$baseDomain}{$port}/login";

            return redirect()->away($correctLoginUrl)
                ->withErrors(['email' => 'Você não tem acesso a este painel administrativo.']);
        }

        return $next($request);
    }
}
