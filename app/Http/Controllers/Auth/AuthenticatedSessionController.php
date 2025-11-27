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
        $baseDomain = env('APP_DOMAIN');      
        
        //tira o saas.test
        $currentSlug = str_replace('.' . $baseDomain, '', $host);

        if($currentSlug === $host){
            return redirect()->route('home');
        }
        $user = Auth::user();
        
        if($user->subscribed(env('STRIPE_PRODUCT_ID'))){
            //se o slug do site for diferente do slug do usuario autenticado
            if($user->slug !== $currentSlug){
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Voce nao tem acesso à esse dominio']);
            }else{
                return redirect()->intended(route('dashboard', ['slug' => $user->slug]));
            }
         }else{
            Auth::logout();
            return redirect()->back()->withErrors(['email' => 'Sua inscrição expirou']);
         }

        return redirect()->intended();
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
