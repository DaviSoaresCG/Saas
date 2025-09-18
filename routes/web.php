<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\AutenticadoSlug;
use App\Http\Middleware\hasSubscription;
use App\Http\Middleware\noSubscription;
use App\Http\Middleware\ResolveTenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::domain('{slug}.' . env('APP_DOMAIN'))
    ->middleware([ResolveTenant::class])
    ->group(function () {

        Route::get('/', function () {
            $user = app(User::class);
            return redirect()->route('products.index', ['slug' => $user->slug]);
        })->middleware(AutenticadoSlug::class);

        Route::get('/produtos', [ProdutoController::class, 'index'])->name('products.index');
        // Route::get('produtos', 'Front\ProductController@index')->name('products.index');
        // Route::get('produtos/{slug}', 'Front\ProductController@show')->name('products.show');

        Route::middleware(['auth'])->group(function () {
            Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');
            // outras rotas de adm...
        });
    });

Auth::routes();

Route::get('/', [MainController::class, 'plans'])->name('home');

Route::middleware([noSubscription::class])->group(function () {
        Route::get('/plans', [MainController::class, 'plans'])->name('plans');
        Route::get('/plan_selected/{id}', [MainController::class, 'planSelected'])->name('plans.selected')->middleware('auth');
});


Route::middleware('auth')->group(function () {
    Route::middleware([hasSubscription::class])->group(function () {
        Route::get('/subscription/success', [MainController::class, 'subscriptionSuccess'])->name('subscription.success');
        Route::get('/invoice/{id}', [MainController::class, 'invoiceDownload'])->name('invoice.download');
    });
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
