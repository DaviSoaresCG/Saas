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

        // Se a requisição for para renovação, seleção de plano ou pagamento direto, permite prosseguir
        if ($request->has('renew') || $request->has('plan') || $request->is('pagamento/*') || $request->is('assinatura/*')) {
            return $next($request);
        }

        if ($user && $user->isLojaAtiva()) {
            if (!empty($user->slug)) {
                $scheme = $request->secure() ? 'https://' : 'http://';
                $port = $request->getPort() && !in_array($request->getPort(), [80, 443]) ? ':' . $request->getPort() : '';
                return redirect()->away($scheme . $user->slug . '.' . env('APP_DOMAIN') . $port . '/dashboard');
            }
            
            return redirect()->route('dashboard', ['slug' => $user->slug]);
        }

        return $next($request);
    }
}
