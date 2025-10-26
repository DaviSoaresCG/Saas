<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Middleware\EnsureUserBelongsToTenant;
use App\Http\Middleware\hasSubscription;
use App\Http\Middleware\noSubscription;
use App\Http\Middleware\ResolveTenant;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::domain('{slug}.'.env('APP_DOMAIN'))
    ->middleware([ResolveTenant::class])
    ->group(function () {

        // redireciona para dashboard se for admin
        Route::get('/', function () {
            $user = app(User::class);

            return redirect()->route('products.index', ['slug' => $user->slug]);
        });

        //

        // cart
        Route::get('/cart/index', [CartController::class, 'index'])->name('cart.index');
        Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
        Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
        Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        // pedido
        Route::get('/pedido-finaliar', [PedidoController::class, 'finalizar'])->name('whatsapp');
        Route::get('/pedido', [PedidoController::class, 'index'])->name('falarWhatsapp');

        Route::get('/produtos', [ProdutoController::class, 'index'])->name('products.index');
        Route::get('/produtos/{product}', [ProdutoController::class, 'show'])->name('products.show');

        Route::middleware('auth', EnsureUserBelongsToTenant::class)->group(function () {
            // admin.dashboard
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            // products routes
            Route::get('/dashboard/products', [AdminController::class, 'getAllProducts'])->name('admin.products');
            Route::resource('products', ProdutoController::class)->except(['index', 'show']);

            // pedidos routes
            Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
            Route::get('/pedidos/show/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
            Route::get('/pedido/seach', [PedidoController::class, 'pesquisar'])->name('pedido.pesquisar');

            // assinatura
            Route::get('/billing', function (Request $request) {
                return $request->user()->redirectToBillingPortal();
            })->name('billing');

            // Route::get('/products/create', [ProdutoController::class, 'create'])->name('products.create');
            // Route::post('/produtos/create_post', [ProdutoController::class, 'store'])->name('products.store');
            // Route::get('/produtos/edit/{id}', [ProdutoController::class, 'edit'])->name('products.edit');
            // Route::post('/produtos/update', [ProdutoController::class, 'update'])->name('products.update');
            // Route::delete('/produtos/delete', [ProdutoController::class, 'destroy'])->name('products.destroy');
            // outras rotas de adm...
        });
    });

Auth::routes();

Route::get('/', [AdminController::class, 'plans'])->name('home');

Route::middleware([noSubscription::class])->group(function () {
    Route::get('/plans', [AdminController::class, 'plans'])->name('plans');

    Route::get('/plan_selected/{id}', [AdminController::class, 'planSelected'])
        ->name('plans.selected')
        ->middleware('auth', 'verified');
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/subscription/success', [AdminController::class, 'subscriptionSuccess'])->name('subscription.success');
    Route::get('/subscription/pending', [AdminController::class, 'subscriptionPending'])->name('subscription.pending');
    Route::get('/invoice/{id}', [AdminController::class, 'invoiceDownload'])->name('invoice.download')->middleware([hasSubscription::class]);
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// API
Route::get('/api/subscription/status', function () {
    return ['subscribed' => auth()->user()->subscribed(env('STRIPE_PRODUCT_ID'))];
})->name('api.subscription.status')->middleware('auth');

// email verification
Route::prefix('email')
    ->name('verification.')
    ->group(function () {

        // email verification view
        Route::get('/verify', function () {
            return view('auth.email.verify-email');
        })->name('notice')->middleware(['auth']);

        // link to verify hash
        Route::get('verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();

            return redirect('/home');
        })->middleware(['signed'])->name('verify');

        // resend the email verification
        Route::post('verification-notification', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();

            return back()->with('success', 'Email de verificação enviado novamente!');
        })->middleware(['throttle:6,1'])->middleware(['auth'])->name('send');
    });
