<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->isLojaAtiva()) {
            return $user->tipo_cliente === 'erp'
                ? response("Acesso restrito: conta suspensa pelo ERP.", 403)
                : redirect()->route('pagamento.pendente');
        }

        return $next($request);
    }
}
