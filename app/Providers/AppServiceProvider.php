<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $url);
        });
        // Sobrescreve a lógica de redirecionamento padrão do Laravel
        RedirectIfAuthenticated::redirectUsing(function ($request) {
            $user = Auth::user();

            // Se o usuário tem slug, manda pro dashboard dele
            $host = $request->getHost();
            if($host == env('APP_DOMAIN')){
                return route('home');
            };

            if ($user && $user->slug) {
                return route('dashboard', ['slug' => $user->slug]);
            }

            // Se não, manda pra home
            return '/';
        });
    }
}
