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
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // alias para middleware
        $middleware->alias([EnsureUserBelongsToTenant::class]);
        $middleware->alias([ResolveTenant::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();