<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
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
       $hostSegments = explode('.', $request->getHost());
       $slug = $hostSegments[0];

       $tenant = Tenant::where('slug', $slug)->firstOrFail();

       app()->instance(Tenant::class, $tenant);

       return $next($request);
    }
}
