<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AtributoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\ErpApiController;
use App\Http\Controllers\PixPaymentController;
use App\Http\Controllers\CatalogoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas do Catálogo (Compartilhadas entre Subdomínio e Variante)
|--------------------------------------------------------------------------
*/

if (!function_exists('registerPublicCatalogRoutes')) {
    function registerPublicCatalogRoutes($isVariant = false) {
        $prefix = $isVariant ? 'variant.' : '';

        Route::controller(ProdutoController::class)->group(function () use ($prefix) {
            Route::get('/produtos', 'index')->name($prefix . 'products.index');
            Route::get('/produtos/{product}', 'show')->name($prefix . 'products.show');
            Route::post('/produtos/search', 'search')->name($prefix . 'products.search');
        });

        Route::controller(CartController::class)->prefix('cart')->name($prefix . 'cart.')->group(function () {
            Route::get('/index', 'index')->name('index');
            Route::post('/add/{id}', 'add')->name('add');
            Route::get('/remove/{id}', 'remove')->name('remove');
            Route::post('/update', 'update')->name('update');
            Route::get('/clear', 'clear')->name('clear');
        });

        Route::get('/pedido-finalizar', [PedidoController::class, 'finalizar'])->name($prefix . 'order.finished');
    }
}

/*
|--------------------------------------------------------------------------
| 1. Rotas de Variante de Catálogo (Domínio Principal com Hash)
|--------------------------------------------------------------------------
*/
Route::domain(env('APP_DOMAIN'))->prefix('{hash}')->where(['hash' => '[a-zA-Z0-9]{12}'])->middleware(['tenant'])->group(function () {
    Route::get('/', fn ($hash) => redirect()->route('variant.products.index', ['hash' => $hash]));
    registerPublicCatalogRoutes(true);
});

/*
|--------------------------------------------------------------------------
| 2. Rotas de Subdomínio do Tenant (Catálogo e Área Administrativa)
|--------------------------------------------------------------------------
*/
Route::domain('{slug}.' . env('APP_DOMAIN'))->middleware(['tenant'])->group(function () {
    Route::get('/', fn () => redirect()->route('products.index'));

    // Catálogo público no subdomínio
    registerPublicCatalogRoutes(false);

    // Área Administrativa do Lojista
    Route::middleware(['auth', 'tenant.member', 'password.reset.forced'])->group(function () {

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

        // CRUD de Produtos e Atributos (Apenas Clientes Diretos)
        Route::middleware(['client.direct'])->group(function () {
            Route::resource('products', ProdutoController::class)->except(['index', 'show', 'destroy']);
            Route::delete('/products/delete/{product}', [ProdutoController::class, 'destroy'])->name('products.destroy');
            Route::delete('/products/images/{image}', [ProdutoController::class, 'destroyImage'])->name('products.image.destroy');
            Route::post('/products/{product}/reorder-images', [ProdutoController::class, 'reorderImages'])->name('products.images.reorder');
            Route::post('/products/{product}/update-image', [ProdutoController::class, 'uploadImage'])->name('products.image.upload');

            // CRUD de Atributos
            Route::resource('atributos', AtributoController::class)->only(['index', 'store', 'destroy']);
        });

        // CRUD de Catálogos Promocionais
        Route::resource('catalogos', CatalogoController::class)->except(['show', 'create', 'edit']);

        // Perfil do Usuário
        Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
        });
    });
});

/*
|--------------------------------------------------------------------------
| 3. Rotas do Domínio Principal (Pagamentos, Onboarding e Home)
|--------------------------------------------------------------------------
*/
Route::domain(env('APP_DOMAIN'))->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/plans', [HomeController::class, 'plans'])->name('plans');

    // Fluxo de pagamento Pix (Clientes Diretos)
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/pagamento/pendente', [PixPaymentController::class, 'pending'])->name('pagamento.pending');
        Route::post('/pagamento/gerar', [PixPaymentController::class, 'generate'])->name('pagamento.generate');
        Route::get('/pagamento/checkout', [PixPaymentController::class, 'checkout'])->name('pagamento.checkout');
        Route::post('/pagamento/simular-sucesso', [PixPaymentController::class, 'simulateSuccess'])->name('pagamento.simulate');
    });

    // Erros e avisos
    Route::get('/loja-indisponivel', fn () => view('errors.loja-indisponivel'))->name('loja-indisponivel');
});

/*
|--------------------------------------------------------------------------
| 4. Endpoints de API (ERP Webhook Onboarding & Sincronização)
|--------------------------------------------------------------------------
*/
Route::post('/api/erp/onboarding', [ErpApiController::class, 'onboarding'])->name('api.erp.onboarding');
Route::post('/api/payments/pix/webhook', [PixPaymentController::class, 'webhook'])->name('api.payments.pix.webhook');

Route::middleware(['api.token'])->prefix('api')->group(function () {
    Route::post('/products/sync', [ErpApiController::class, 'syncProducts'])->name('api.products.sync');
    Route::delete('/products/sync/{erp_id}', [ErpApiController::class, 'deleteProduct'])->name('api.products.delete');
});

require __DIR__.'/auth.php';
