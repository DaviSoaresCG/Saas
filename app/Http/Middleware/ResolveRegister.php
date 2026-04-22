<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ResolveRegister
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
        if($slug){
            return redirect()->route('products.index', ['slug' => $slug]);
        }
        return $next($request);
    }
}
