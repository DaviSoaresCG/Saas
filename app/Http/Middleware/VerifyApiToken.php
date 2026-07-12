<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized: token not provided'], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized: invalid api token'], 401);
        }

        Auth::setUser($user);

        // Injeta a instância do User correspondente no container para o TenantScope funcionar
        app()->instance(User::class, $user);

        return $next($request);
    }
}
