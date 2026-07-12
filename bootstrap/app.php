<?php

use App\Http\Middleware\EnsureUserBelongsToTenant;
use App\Http\Middleware\ResolveTenant;
use App\Providers\EventServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',

        channels: __DIR__.'/../routes/channels.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias para middlewares
        $middleware->alias([
            'tenant' => \App\Http\Middleware\ResolveTenant::class,
            'tenant.member' => \App\Http\Middleware\EnsureUserBelongsToTenant::class,
            'subscription.active' => \App\Http\Middleware\EnsureSubscriptionActive::class,
            'subscription.inactive' => \App\Http\Middleware\RedirectIfSubscriptionActive::class,
            'api.token' => \App\Http\Middleware\VerifyApiToken::class,
            'password.reset.forced' => \App\Http\Middleware\CheckForcedPasswordReset::class,
            'client.direct' => \App\Http\Middleware\EnsureDirectClient::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'cashier/*',
            'api/*',
            'webhook/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();