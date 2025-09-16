<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Middleware\hasSubscription;
use App\Http\Middleware\noSubscription;
use App\Http\Middleware\ResolveTenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('plans');
});

Route::domain('{tenant}.' . env('APP_DOMAIN'))
    ->middleware([ResolveTenant::class, 'auth'])
    ->group(function () {
        Route::get('/', function(){
            echo "AAA";
        });
        Route::get('produtos', 'Front\ProductController@index')->name('products.index');
        Route::get('produtos/{slug}', 'Front\ProductController@show')->name('products.show');
        // outras rotas de painel público ou admin
    });

Route::controller(MainController::class)->group(function () {

    Route::middleware('auth')->group(function () {
        // nao acessa se não tiver uma subscription
        Route::middleware([hasSubscription::class])->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/subscription/success', 'subscriptionSuccess')->name('subscription.success');
            Route::get('/invoice/{id}', 'invoiceDownload')->name('invoice.download');
        });
    });

        // nao acessa se tiver uma subscription
        Route::middleware([noSubscription::class])->group(function () {
            Route::get('/plans', 'plans')->name('plans');
            Route::get('/plan_selected/{id}', 'planSelected')->name('plans.selected');
        });
    });

Auth::routes();
