<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckForcedPasswordReset
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->need_change_password) {
            $currentRoute = $request->route() ? $request->route()->getName() : null;

            $allowedRoutes = [
                'profile.edit',
                'profile.update',
                'logout',
                'password.update'
            ];

            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('profile.edit')
                    ->with('warning', 'Atenção: Você deve alterar a sua senha inicial (CPF/CNPJ) antes de prosseguir.');
            }
        }

        return $next($request);
    }
}
