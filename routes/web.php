<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AtributoController;
use App\Http\Controllers\CartController;
// Importação dos Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\ThemeController;
use App\Http\Middleware\EnsureUserBelongsToTenant;
use App\Http\Middleware\hasSubscription;
// Importação dos Middlewares
use App\Http\Middleware\noSubscription;
use App\Http\Middleware\ResolveTenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('cashier.webhook');

Route::get('/erro', fn () => 'Deu erro no stripe')->name('erro');

/*
|--------------------------------------------------------------------------
| Grupo de Subdomínios (Tenant)
|--------------------------------------------------------------------------
*/

Route::domain('{slug}.'.env('APP_DOMAIN'))->middleware([ResolveTenant::class])->group(function () {

    Route::get('/', fn () => redirect()->route('products.index'));

    // --- ProdutoController (Catálogo Público) ---
    Route::controller(ProdutoController::class)->group(function () {
        Route::get('/produtos', 'index')->name('products.index');
        Route::get('/produtos/{product}', 'show')->name('products.show');
        Route::post('/produtos/search', 'search')->name('products.search');

    });

    // --- CartController (Carrinho) ---
    Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::post('/add/{id}', 'add')->name('add');
        Route::get('/remove/{id}', 'remove')->name('remove');
        Route::post('/update', 'update')->name('update');
        Route::get('/clear', 'clear')->name('clear');
    });

    // finalzar o pedido
    Route::get('/pedido-finalizar', [PedidoController::class, 'finalizar'])->name('order.finished');

    /*
    |--------------------------------------------------------------------------
    | Rotas Autenticadas do Tenant (Dashboard / Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', EnsureUserBelongsToTenant::class])->group(function () {

        Route::prefix('dashboard')->group(function () {
            Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
            Route::get('/products', [AdminController::class, 'getAllProducts'])->name('admin.products');
            Route::patch('/update-slug', [AdminController::class, 'gerarSlugUnicoPost'])->name('slug.update');

            Route::controller(PedidoController::class)->group(function () {
                Route::get('/pedidos', 'index')->name('order.index');
                Route::get('/pedidos/buscar', 'search')->name('order.search');
                Route::get('/pedidos/{id}', 'show')->name('order.show')->whereNumber('id');
            });
            Route::controller(ThemeController::class)->group(function () {
                Route::get('/theme', 'index')->name('theme.index');
                Route::post('/update-theme', 'themeUpdate')->name('theme.update');
            });
        });

        // --- ProdutoController (CRUD Administrativo) ---
        Route::resource('products', ProdutoController::class)->except(['index', 'show', 'destroy']);
        Route::delete('/products/delete/{product}', [ProdutoController::class, 'destroy'])->name('products.destroy');

        // --- AtributoController (CRUD de Atributos) ---
        Route::resource('atributos', AtributoController::class)->only(['index', 'store', 'destroy']);

        // --- ProfileController (Conta do Usuário) ---
        Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
        });

        // --- Billing (Stripe Portal) ---
        Route::get('/billing', fn (Request $request) => $request->user()->redirectToBillingPortal())->name('billing');
    });
});

/*
|--------------------------------------------------------------------------
| Fluxo de Assinatura e Planos
|--------------------------------------------------------------------------
*/

Route::middleware([noSubscription::class])->group(function () {
    Route::get('/plan_selected/{id}', [AdminController::class, 'planSelected'])
        ->middleware(['auth', 'verified'])
        ->name('plans.selected');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/subscription/success', 'subscriptionSuccess')->name('subscription.success')->middleware([hasSubscription::class]);
        Route::get('/subscription/pending', 'subscriptionPending')->name('subscription.pending');
        Route::get('/invoice/{id}', 'invoiceDownload')
            ->middleware([hasSubscription::class])
            ->name('invoice.download');
    });
});

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/plans', [HomeController::class, 'plans'])->name('plans');

/*
|--------------------------------------------------------------------------
| API e Auth Interno
|--------------------------------------------------------------------------
*/

Route::get('/api/subscription/status', fn () => ['subscribed' => auth()->user()->subscribed()])
    ->middleware('auth')
    ->name('api.subscription.status');

require __DIR__.'/auth.php';
