<?php

use App\Models\Catalogo;
use App\Models\Products;
use App\Models\User;

test('storefront layout loads instrument serif and inter typography in styles stack', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');
    $response = $this->get("http://{$user->slug}.{$domain}/produtos");

    $response->assertOk();
    $response->assertSee('family=Instrument+Serif', false);
    $response->assertSee('family=Inter', false);
    $response->assertSee('.font-serif', false);
});

test('app.css clean theme defines brillance palette variables and preserves alternative themes', function () {
    $cssContent = file_get_contents(resource_path('css/app.css'));

    // Brillance Clean Palette
    expect($cssContent)->toContain(':root, [data-theme="clean"]');
    expect($cssContent)->toMatch('/--bg-page:\s*#f7f5f3;/');
    expect($cssContent)->toMatch('/--bg-card:\s*#ffffff;/');
    expect($cssContent)->toMatch('/--color-primary:\s*#37322f;/');
    expect($cssContent)->toMatch('/--text-base:\s*#37322f;/');
    expect($cssContent)->toMatch('/--text-muted:\s*#605a57;/');
    expect($cssContent)->toMatch('/--text-on-primary:\s*#ffffff;/');

    // Preservation of alternative themes
    expect($cssContent)->toContain('[data-theme="ocean"]');
    expect($cssContent)->toContain('[data-theme="sunset"]');
    expect($cssContent)->toContain('[data-theme="midnight"]');
    expect($cssContent)->toContain('[data-theme="forest"]');
    expect($cssContent)->toContain('[data-theme="premium"]');
    expect($cssContent)->toContain('[data-theme="lavender"]');
    expect($cssContent)->toContain('[data-theme="terracotta"]');
    expect($cssContent)->toContain('[data-theme="cyber"]');
});

test('storefront header renders brillance styling with backdrop blur, rounded-2xl logo, font-serif store name and pill navigation', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
        'nome_loja' => 'Boutique Elegance',
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');
    $response = $this->get("http://{$user->slug}.{$domain}/produtos");

    $response->assertOk();
    // Header container with backdrop-blur-xl and subtle border
    $response->assertSee('backdrop-blur-xl', false);
    $response->assertSee('border-b border-[var(--text-base)]/10', false);

    // Store logo container with rounded-2xl
    $response->assertSee('rounded-2xl', false);

    // Store name with editorial serif
    $response->assertSee('font-serif', false);
    $response->assertSee('Boutique Elegance', false);

    // Dashboard pill button
    $response->assertSee('rounded-full border border-[var(--text-base)]/15', false);

    // Cart pill button and badge
    $response->assertSee('id="cart-badge"', false);
    $response->assertSee('rounded-full bg-[var(--color-primary)]', false);
});

test('storefront header renders elegant catalog name badge when catalogoName is present and visible', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $catalogo = Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Coleção Especial de Inverno',
        'hash' => 'inverno12345',
        'desconto_index' => 15.00,
        'sob_consulta' => false,
        'mostrar_nome' => true,
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');
    $response = $this->get("http://{$domain}/{$catalogo->hash}/produtos");

    $response->assertOk();
    $response->assertSee('Coleção Especial de Inverno', false);
    $response->assertSee('rounded-full border border-[var(--text-base)]/10', false);
});

test('storefront modal renders brillance styling with rounded-3xl, font-serif price and tactile quantity controls when modal_carrinho is enabled', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
        'modal_carrinho' => true,
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');
    $response = $this->get("http://{$user->slug}.{$domain}/produtos");

    $response->assertOk();

    // Modal structure with rounded-3xl and subtle border
    $response->assertSee('id="add-to-cart-modal"', false);
    $response->assertSee('id="add-to-cart-modal-content"', false);
    $response->assertSee('rounded-3xl', false);
    $response->assertSee('border border-[var(--text-base)]/10', false);
    $response->assertSee('shadow-2xl', false);

    // Product price with font-serif
    $response->assertSee('id="add-modal-product-price"', false);
    $response->assertSee('font-serif', false);

    // Quantity selector buttons with rounded-xl and border
    $response->assertSee('id="btn-modal-qty-minus"', false);
    $response->assertSee('id="btn-modal-qty-plus"', false);
    $response->assertSee('rounded-xl border border-[var(--text-base)]/15', false);

    // Submit button with micro-interactions and rounded-xl
    $response->assertSee('Confirmar e Adicionar', false);
    $response->assertSee('rounded-xl', false);
    $response->assertSee('active:scale-[0.98]', false);
});

test('storefront renders tenant custom logo when available and standard icon fallback when absent', function () {
    $userWithLogo = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
        'logo_path' => 'https://example.com/uploads/custom-logo.png',
        'nome_loja' => 'Loja Com Logo',
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');
    $responseWithLogo = $this->get("http://{$userWithLogo->slug}.{$domain}/produtos");
    $responseWithLogo->assertOk();
    $responseWithLogo->assertSee('src="https://example.com/uploads/custom-logo.png"', false);
    $responseWithLogo->assertSee('alt="Loja Com Logo"', false);

    $userWithoutLogo = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
        'logo_path' => null,
        'nome_loja' => 'Loja Sem Logo',
    ]);

    $responseWithoutLogo = $this->get("http://{$userWithoutLogo->slug}.{$domain}/produtos");
    $responseWithoutLogo->assertOk();
    $responseWithoutLogo->assertDontSee('src="https://example.com/uploads/custom-logo.png"', false);
    $responseWithoutLogo->assertSee('data-lucide="shopping-bag"', false);
});

test('storefront renders header cart badge accurately across empty, count, and 99+ states', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');

    // 1. Estado vazio
    $responseEmpty = $this->withSession(['cart' => []])
        ->get("http://{$user->slug}.{$domain}/produtos");
    $responseEmpty->assertOk();
    $responseEmpty->assertSee('id="cart-badge"', false);
    $responseEmpty->assertSee('class="hidden inline-flex', false);

    // 2. Estado numérico (7 itens)
    $responseCount = $this->withSession([
        'cart' => [
            'item_1' => ['id' => 1, 'name' => 'Vestido', 'value' => 150.00, 'quantity' => 3],
            'item_2' => ['id' => 2, 'name' => 'Bolsa', 'value' => 80.00, 'quantity' => 4],
        ],
    ])->get("http://{$user->slug}.{$domain}/produtos");
    $responseCount->assertOk();
    $responseCount->assertSee('id="cart-badge"', false);
    $responseCount->assertSee('7');

    // 3. Estado 99+ (105 itens)
    $response99Plus = $this->withSession([
        'cart' => [
            'item_1' => ['id' => 1, 'name' => 'Cinto', 'value' => 30.00, 'quantity' => 105],
        ],
    ])->get("http://{$user->slug}.{$domain}/produtos");
    $response99Plus->assertOk();
    $response99Plus->assertSee('id="cart-badge"', false);
    $response99Plus->assertSee('99+');
});

test('storefront child views (products.show, cart.index, and pedidos.whatsapp) inherit brillance store layout typography and header', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
        'nome_loja' => 'Maison Brillance',
        'whatsapp' => '11999998888',
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Relógio Clássico Vintage',
        'preco_base' => 380.00,
        'sku' => 'REL-001',
        'estoque' => 10,
        'status' => true,
        'description' => 'Relógio clássico de alta precisão',
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');

    // 1. Teste de renderização em products.show
    $responseShow = $this->get("http://{$user->slug}.{$domain}/produtos/{$product->id}");
    $responseShow->assertOk();
    $responseShow->assertSee('family=Instrument+Serif', false);
    $responseShow->assertSee('Maison Brillance', false);
    $responseShow->assertSee('backdrop-blur-xl', false);
    $responseShow->assertSee('rounded-2xl', false);
    $responseShow->assertSee('id="cart-badge"', false);

    // 2. Teste de renderização em cart.index
    $responseCart = $this->get("http://{$user->slug}.{$domain}/cart/index");
    $responseCart->assertOk();
    $responseCart->assertSee('family=Instrument+Serif', false);
    $responseCart->assertSee('Maison Brillance', false);
    $responseCart->assertSee('id="cart-badge"', false);
    $responseCart->assertSee('rounded-2xl', false);

    // 3. Teste de renderização em pedidos.whatsapp
    $cart = [
        'item_1' => [
            'id' => $product->id,
            'name' => $product->nome,
            'value' => 380.00,
            'quantity' => 1,
        ],
    ];

    $responseOrder = $this->withSession(['cart' => $cart])
        ->post("http://{$user->slug}.{$domain}/pedido-finalizar", [
            'cliente_nome' => 'Cliente Elegante',
            'cliente_phone' => '(11) 98888-7777',
        ]);

    $responseOrder->assertOk();
    $responseOrder->assertViewIs('pedidos.whatsapp');
    $responseOrder->assertSee('family=Instrument+Serif', false);
    $responseOrder->assertSee('Maison Brillance', false);
    $responseOrder->assertSee('Quase lá!', false);
    $responseOrder->assertSee('Finalizar no WhatsApp', false);
});
