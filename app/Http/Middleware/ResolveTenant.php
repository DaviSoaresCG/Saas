<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Catalogo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        // 1. Resolve via hash do catálogo no domínio principal (ex: saas.test/{hash})
        $hash = $request->route('hash');
        if ($hash) {
            $catalogo = Catalogo::where('hash', $hash)->first();

            if (!$catalogo) {
                return redirect()->away('http://' . env('APP_DOMAIN') . '/loja-indisponivel');
            }

            $user = $catalogo->user;

            if (!$user || !$user->isLojaAtiva()) {
                return redirect()->away('http://' . env('APP_DOMAIN') . '/loja-indisponivel');
            }

            // Ativa a sessão do catálogo
            session([
                'catalog_hash' => $hash,
                'desconto_index' => $catalogo->desconto_index
            ]);

            app()->instance(User::class, $user);
            URL::defaults(['hash' => $hash]);
            URL::defaults(['slug' => $user->slug]);
            View::share('theme', $user->theme_name);
            View::share('slug', $user->slug);

            return $next($request);
        }

        // 2. Resolve via subdomínio (ex: loja.saas.test)
        $slug = $request->route('slug');
        if (!$slug) {
            $host = $request->getHost();
            $base = env('APP_DOMAIN');
            $slug = str_replace('.' . $base, '', $host);
            if ($slug == $host) {
                $slug = null;
            }
        }

        if ($slug) {
            $user = User::where('slug', $slug)->first();

            if (!$user || !$user->isLojaAtiva()) {
                return redirect()->away('http://' . env('APP_DOMAIN') . '/loja-indisponivel');
            }

            // Acesso direto ao subdomínio limpa a sessão de desconto do catálogo
            session()->forget(['catalog_hash', 'desconto_index']);

            app()->instance(User::class, $user);
            URL::defaults(['slug' => $slug]);
            View::share('theme', $user->theme_name);
            session(['theme' => $user->theme_name]);
        }

        return $next($request);
    }
}

