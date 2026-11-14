<?php

use App\Models\Products;
use App\Models\User;

test('cart page renders brillance back button, editorial typography, and empty state when cart is empty', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
        'nome_loja' => 'Maison Brillance',
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');
    $response = $this->withSession(['cart' => []])
        ->get("http://{$user->slug}.{$domain}/cart/index");

    $response->assertOk();

    // 1. Back pill button with Brillance styling
    $response->assertSee('Voltar às compras', false);
    $response->assertSee('rounded-full border border-[var(--text-base)]/15 bg-[var(--bg-card)]', false);

    // 2. Editorial title with Instrument Serif
    $response->assertSee('font-serif text-3xl sm:text-4xl text-[var(--text-base)] tracking-tight', false);
    $response->assertSee('Meu Carrinho', false);

    // 3. Elegant Empty State container and CTA
    $response->assertSee('Seu carrinho está vazio', false);
    $response->assertSee('rounded-3xl border border-[var(--text-base)]/10 bg-[var(--bg-card)]', false);
    $response->assertSee('Explorar produtos', false);
    $response->assertDontSee('id="open-checkout-modal-btn"', false);
});

test('cart page renders items grid and order summary when cart has products', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Bolsa de Couro Nobre',
        'preco_base' => 450.00,
        'sku' => 'BOL-001',
        'estoque' => 5,
        'status' => true,
        'description' => 'Bolsa nobre em couro legítimo',
    ]);

    $cart = [
        $product->id => [
            'id' => $product->id,
            'name' => $product->nome,
            'value' => 450.00,
            'quantity' => 1,
            'path' => '',
        ],
    ];

    $domain = env('APP_DOMAIN', 'saas.test');
    $response = $this->withSession(['cart' => $cart])
        ->get("http://{$user->slug}.{$domain}/cart/index");

    $response->assertOk();

    // Title and back button
    $response->assertSee('Meu Carrinho', false);
    $response->assertSee('font-serif text-3xl sm:text-4xl', false);
    $response->assertSee('Voltar às compras', false);

    // Cart items and summary
    $response->assertSee('Bolsa de Couro Nobre', false);
    $response->assertSee('id="open-checkout-modal-btn"', false);
    $response->assertDontSee('Seu carrinho está vazio', false);
});

test('cart page renders brillance item cards with tactile quantity controls, attributes badges and subtotal typography', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Vestido de Seda Floral',
        'preco_base' => 280.00,
        'sku' => 'VES-002',
        'estoque' => 8,
        'status' => true,
        'description' => 'Vestido em pura seda com estampa floral',
    ]);

    $cart = [
        $product->id => [
            'id' => $product->id,
            'name' => $product->nome,
            'value' => 280.00,
            'quantity' => 2,
            'path' => 'https://example.com/vestido.jpg',
            'atributos' => ['Tamanho: M', 'Cor: Lavanda'],
            'observacao' => 'Embalar para presente',
        ],
    ];

    $domain = env('APP_DOMAIN', 'saas.test');
    $response = $this->withSession(['cart' => $cart])
        ->get("http://{$user->slug}.{$domain}/cart/index");

    $response->assertOk();

    // 1. Card frame and product item metadata
    $response->assertSee('rounded-2xl border border-[var(--text-base)]/10 bg-[var(--bg-card)]', false);
    $response->assertSee('data-product-id="' . $product->id . '"', false);
    $response->assertSee('data-price="280"', false);

    // 2. Image container
    $response->assertSee('rounded-xl border border-[var(--text-base)]/10', false);
    $response->assertSee('src="https://example.com/vestido.jpg"', false);

    // 3. Badges for attributes and observation
    $response->assertSee('Tamanho: M', false);
    $response->assertSee('Cor: Lavanda', false);
    $response->assertSee('rounded-full border border-[var(--text-base)]/10 bg-[var(--text-base)]/5 px-2.5 py-0.5 text-xs font-medium text-[var(--text-muted)]', false);
    $response->assertSee('Embalar para presente', false);
    $response->assertSee('Obs:', false);

    // 4. Unit price in font-serif
    $response->assertSee('Unitário:', false);
    $response->assertSee('font-serif font-medium text-[var(--text-base)] text-sm sm:text-base', false);
    $response->assertSee('R$ 280,00', false);

    // 5. Tactile quantity controls
    $response->assertSee('btn-update-cart', false);
    $response->assertSee('rounded-xl border border-[var(--text-base)]/15 bg-[var(--bg-card)]', false);
    $response->assertSee('data-action="decrement"', false);
    $response->assertSee('data-action="increment"', false);
    $response->assertSee('data-lucide="minus"', false);
    $response->assertSee('data-lucide="plus"', false);
    $response->assertSee('id="quantity-' . $product->id . '"', false);

    // 6. Editorial subtotal
    $response->assertSee('id="item-subtotal-' . $product->id . '"', false);
    $response->assertSee('font-serif text-lg sm:text-xl font-medium text-[var(--text-base)]', false);
    $response->assertSee('R$ 560,00', false);

    // 7. Refined remove item button
    $response->assertSee('btn-remove-item', false);
    $response->assertSee('data-lucide="trash-2"', false);
});

test('cart page renders sob consulta badge instead of price when catalog is sob consulta', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $catalogo = \App\Models\Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Catálogo Sob Consulta',
        'hash' => 'consulta1234',
        'desconto_index' => 0.00,
        'sob_consulta' => true,
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Joia Personalizada Ouro 18k',
        'preco_base' => 1200.00,
        'sku' => 'JOI-001',
        'estoque' => 2,
        'status' => true,
        'description' => 'Joia sob medida',
    ]);

    $cart = [
        $product->id => [
            'id' => $product->id,
            'name' => $product->nome,
            'value' => 1200.00,
            'quantity' => 1,
            'path' => '',
        ],
    ];

    $appDomain = config('app.domain', env('APP_DOMAIN', 'saas.test'));
    $response = $this->withSession(['cart' => $cart])
        ->get("http://{$appDomain}/{$catalogo->hash}/cart/index");

    $response->assertOk();

    // Sob consulta badge in card item
    $response->assertSee('Sob Consulta', false);
    $response->assertSee('rounded-full border border-amber-500/20 bg-amber-500/10', false);
    $response->assertDontSee('id="item-subtotal-' . $product->id . '"', false);
});

test('cart page renders order summary card with rounded-3xl, font-serif total and pill checkout button', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Colar de Pérolas',
        'preco_base' => 350.00,
        'sku' => 'COL-001',
        'estoque' => 4,
        'status' => true,
        'description' => 'Colar de pérolas naturais',
    ]);

    $cart = [
        $product->id => [
            'id' => $product->id,
            'name' => $product->nome,
            'value' => 350.00,
            'quantity' => 2,
            'path' => '',
        ],
    ];

    $domain = env('APP_DOMAIN', 'saas.test');
    $response = $this->withSession(['cart' => $cart])
        ->get("http://{$user->slug}.{$domain}/cart/index");

    $response->assertOk();

    // 1. Order Summary Card frame
    $response->assertSee('rounded-3xl border border-[var(--text-base)]/10 bg-[var(--bg-card)]', false);
    $response->assertSee('font-serif text-2xl sm:text-3xl text-[var(--text-base)] tracking-tight', false);
    $response->assertSee('Resumo do Pedido', false);

    // 2. Editorial total in font-serif
    $response->assertSee('id="summary-total"', false);
    $response->assertSee('font-serif text-2xl sm:text-3xl font-medium text-[var(--text-base)]', false);
    $response->assertSee('R$ 700,00', false);

    // 3. Primary checkout CTA in pill style
    $response->assertSee('id="open-checkout-modal-btn"', false);
    $response->assertSee('rounded-full bg-[var(--color-primary)] text-[var(--text-on-primary)]', false);
    $response->assertSee('Finalizar pedido', false);
});

test('cart page renders checkout identification modal with rounded-3xl, backdrop blur, and refined inputs', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Relógio Cronógrafo',
        'preco_base' => 500.00,
        'sku' => 'REL-002',
        'estoque' => 3,
        'status' => true,
        'description' => 'Relógio cronógrafo em aço',
    ]);

    $cart = [
        $product->id => [
            'id' => $product->id,
            'name' => $product->nome,
            'value' => 500.00,
            'quantity' => 1,
            'path' => '',
        ],
    ];

    $domain = env('APP_DOMAIN', 'saas.test');
    $response = $this->withSession(['cart' => $cart])
        ->get("http://{$user->slug}.{$domain}/cart/index");

    $response->assertOk();

    // 1. Modal backdrop & container
    $response->assertSee('id="checkout-modal"', false);
    $response->assertSee('bg-black/60 backdrop-blur-md', false);
    $response->assertSee('rounded-3xl border border-[var(--text-base)]/10 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl', false);

    // 2. Header and close button
    $response->assertSee('id="close-modal-btn"', false);
    $response->assertSee('font-serif text-2xl sm:text-3xl font-medium text-[var(--text-base)] tracking-tight', false);
    $response->assertSee('Identificação do Cliente', false);

    // 3. Form & inputs
    $response->assertSee('id="checkout-form"', false);
    $response->assertSee('pedido-finalizar', false);
    $response->assertSee('id="cliente_nome"', false);
    $response->assertSee('id="cliente_phone"', false);
    $response->assertSee('rounded-xl border border-[var(--text-base)]/15 bg-[var(--bg-page)]', false);
    $response->assertSee('focus:ring-2 focus:ring-[var(--color-primary)]/15', false);
    $response->assertSee('x-mask="(99) 99999-9999"', false);

    // 4. Modal action buttons
    $response->assertSee('rounded-full bg-[var(--color-primary)]', false);
    $response->assertSee('Salvar e Enviar Pedido', false);
    $response->assertSee('id="cancel-modal-btn"', false);
});
