<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutenticadoSlug
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = app(User::class);
        if(!Auth::check()){
            return redirect()->route('products.index', ['slug' => $user->slug]);
        }else{
            return redirect()->route('dashboard', ['slug' => $user->slug]);
        }
    }
}
