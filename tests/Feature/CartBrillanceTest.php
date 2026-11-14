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
