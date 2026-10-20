<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();
        $host = $request->getHost();
        $baseDomain = config('app.domain', env('APP_DOMAIN', 'lvh.me'));

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Redirecionamento exclusivo para Super Admin
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.index');
        }

        // Garante que o usuário possua um slug válido
        if (empty($user->slug)) {
            $user->slug = \App\Models\User::generateUniqueSlug($user->nome_loja ?: $user->name, $user->id);
            $user->save();
        }

        // 2. Identifica se está no domínio principal / localhost / www
        $isBaseDomain = ($host === $baseDomain || $host === 'www.' . $baseDomain || in_array($host, ['localhost', '127.0.0.1']));

        if ($isBaseDomain) {
            if ($user->isLojaAtiva()) {
                return redirect()->route('dashboard', ['slug' => $user->slug]);
            }

            // Conta suspensa pelo ERP
            if ($user->tipo_cliente === 'erp') {
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'Acesso restrito: sua conta está suspensa pelo ERP.']);
            }

            // Cliente direto com assinatura expirada ou pendente
            return redirect()->route('pagamento.pending')->with('warning', 'Sua assinatura precisa ser renovada para acessar o painel.');
        }

        // 3. Acesso via subdomínio
        $currentSlug = str_replace('.' . $baseDomain, '', $host);

        // Se estiver tentando acessar o subdomínio de outro lojista
        if ($user->slug !== $currentSlug) {
            Auth::logout();
            $port = $request->getPort() && !in_array($request->getPort(), [80, 443]) ? ':' . $request->getPort() : '';
            $scheme = $request->secure() ? 'https://' : 'http://';
            $correctUrl = "{$scheme}{$user->slug}.{$baseDomain}{$port}/login";

            return redirect()->back()->withErrors([
                'email' => "Você não tem acesso a este catálogo. Acesse o painel da sua loja em: {$correctUrl}"
            ]);
        }

        // Subdomínio correto do próprio usuário
        if ($user->isLojaAtiva()) {
            return redirect()->route('dashboard', ['slug' => $user->slug]);
        }

        // Subdomínio correto, mas com loja inativa / expirada
        if ($user->tipo_cliente === 'erp') {
            Auth::logout();
            return redirect()->back()->withErrors(['email' => 'Acesso restrito: sua conta está suspensa pelo ERP.']);
        }

        return redirect()->route('pagamento.pending')->with('warning', 'Sua assinatura expirou. Efetue o pagamento para reativar seu catálogo.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
