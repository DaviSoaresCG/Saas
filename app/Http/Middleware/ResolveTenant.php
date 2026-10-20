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
        $baseDomain = config('app.domain', env('APP_DOMAIN', 'lvh.me'));
        $scheme = $request->secure() ? 'https://' : 'http://';
        $port = $request->getPort() && !in_array($request->getPort(), [80, 443]) ? ':' . $request->getPort() : '';
        $lojaIndisponivelUrl = "{$scheme}{$baseDomain}{$port}/loja-indisponivel";

        // 1. Resolve via hash do catálogo no domínio principal (ex: saas.test/{hash})
        $hash = $request->route('hash');
        if ($hash) {
            $catalogo = Catalogo::where('hash', $hash)->first();

            if (!$catalogo) {
                return redirect()->away($lojaIndisponivelUrl);
            }

            $user = $catalogo->user;

            if (!$user || !$user->isLojaAtiva()) {
                return redirect()->away($lojaIndisponivelUrl);
            }

            // Ativa a sessão do catálogo
            session([
                'catalog_hash' => $hash,
                'desconto_index' => $catalogo->desconto_index,
                'sob_consulta' => (bool) $catalogo->sob_consulta,
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
            $slug = str_replace('.' . $baseDomain, '', $host);
            if ($slug == $host || $slug === 'www' || in_array($host, ['localhost', '127.0.0.1'])) {
                $slug = null;
            }
        }

        if ($slug) {
            $user = User::where('slug', $slug)->first();

            if (!$user) {
                return redirect()->away($lojaIndisponivelUrl);
            }

            // Se a loja não estiver ativa, visitantes da vitrine pública são redirecionados,
            // mas o lojista ainda pode acessar login, logout e painel (onde o middleware de assinatura o levará ao pagamento).
            if (!$user->isLojaAtiva()) {
                $isAdminOrAuthRoute = $request->is('dashboard*')
                    || $request->is('profile*')
                    || $request->is('login')
                    || $request->is('logout')
                    || $request->is('password*');

                if (!$isAdminOrAuthRoute) {
                    return redirect()->away($lojaIndisponivelUrl);
                }
            }

            // Acesso direto ao subdomínio limpa a sessão de desconto e sob consulta do catálogo
            session()->forget(['catalog_hash', 'desconto_index', 'sob_consulta']);

            app()->instance(User::class, $user);
            URL::defaults(['slug' => $slug]);
            View::share('theme', $user->theme_name);
            session(['theme' => $user->theme_name]);
        }

        return $next($request);
    }
}

