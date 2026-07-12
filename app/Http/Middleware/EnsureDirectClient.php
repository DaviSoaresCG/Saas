<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDirectClient
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->tipo_cliente !== 'direct') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Forbidden: Clientes integrados ao ERP não podem gerenciar produtos ou atributos manualmente.'], 403);
            }
            abort(403, 'Clientes integrados ao ERP não podem gerenciar produtos ou atributos manualmente.');
        }

        return $next($request);
    }
}
