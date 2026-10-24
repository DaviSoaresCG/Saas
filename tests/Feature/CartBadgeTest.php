<?php

use App\Models\Products;
use App\Models\User;

test('storefront header hides cart badge when cart is empty', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $response = $this->withSession([])->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos");

    $response->assertOk();
    $response->assertSee('id="cart-badge"', false);
    $response->assertSee('hidden');
});

test('storefront header renders cart badge with total items count when cart has items in session', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $cart = [
        'item_1' => [
            'id' => 1,
            'name' => 'Camisa',
            'value' => 50.00,
            'quantity' => 2,
        ],
        'item_2' => [
            'id' => 2,
            'name' => 'Calça',
            'value' => 120.00,
            'quantity' => 3,
        ],
    ];

    $response = $this->withSession(['cart' => $cart])
        ->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos");

    $response->assertOk();
    $response->assertSee('id="cart-badge"', false);
    $response->assertSee('5');
});

test('storefront header renders 99+ when total items exceeds 99', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $cart = [
        'item_1' => [
            'id' => 1,
            'name' => 'Caneta',
            'value' => 2.00,
            'quantity' => 150,
        ],
    ];

    $response = $this->withSession(['cart' => $cart])
        ->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos");

    $response->assertOk();
    $response->assertSee('id="cart-badge"', false);
    $response->assertSee('99+');
});

test('variant storefront header renders cart badge with session items', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $catalogo = \App\Models\Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Catálogo Especial',
        'hash' => 'variant12345',
        'desconto_index' => 0.00,
        'sob_consulta' => false,
    ]);

    $cart = [
        'item_1' => [
            'id' => 1,
            'name' => 'Produto Variante',
            'value' => 80.00,
            'quantity' => 4,
        ],
    ];

    $appDomain = config('app.domain', env('APP_DOMAIN', 'lvh.me'));
    $response = $this->withSession(['cart' => $cart])
        ->get("http://{$appDomain}/{$catalogo->hash}/produtos");

    $response->assertOk();
    $response->assertSee('id="cart-badge"', false);
    $response->assertSee('4');
});

test('cart update endpoint returns total sum of quantities as cartCounter', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Camisa Polo',
        'preco_base' => 100.00,
        'sku' => 'CAM-01',
        'estoque' => 10,
        'description' => 'Camisa polo',
    ]);

    $cart = [
        $product->id => [
            'id' => $product->id,
            'name' => $product->nome,
            'value' => 100.00,
            'quantity' => 2,
        ],
        'outro_item' => [
            'id' => 9999,
            'name' => 'Outro Item',
            'value' => 50.00,
            'quantity' => 3,
        ],
    ];

    $response = $this->withSession(['cart' => $cart])
        ->postJson("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/cart/update", [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

    $response->assertOk();
    $response->assertJson([
        'success' => true,
        'quantity' => 5,
        'cartCounter' => 8, // 5 da camisa + 3 do outro item
    ]);
});

test('cart update endpoint returns updated cartCounter when item is removed with quantity 0', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Camisa Polo',
        'preco_base' => 100.00,
        'sku' => 'CAM-02',
        'estoque' => 10,
        'description' => 'Camisa polo',
    ]);

    $cart = [
        $product->id => [
            'id' => $product->id,
            'name' => $product->nome,
            'value' => 100.00,
            'quantity' => 2,
        ],
        'outro_item' => [
            'id' => 9999,
            'name' => 'Outro Item',
            'value' => 50.00,
            'quantity' => 3,
        ],
    ];

    $response = $this->withSession(['cart' => $cart])
        ->postJson("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/cart/update", [
            'product_id' => $product->id,
            'quantity' => 0,
        ]);

    $response->assertOk();
    $response->assertJson([
        'success' => true,
        'message' => 'removido',
        'cartCounter' => 3, // apenas os 3 do outro item
    ]);
});

test('cart page renders cart badge and sync logic in client script', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $response = $this->withSession([])->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/cart/index");

    $response->assertOk();
    $response->assertSee('id="cart-badge"', false);
    $response->assertSee('data.cartCounter');
});



