<?php

use App\Models\Catalogo;
use App\Models\Products;
use App\Models\User;

test('public storefront renders grid-cols-1 on mobile when merchant colunas_mobile is 1', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
        'colunas_mobile' => 1,
    ]);

    Products::create([
        'user_id' => $user->id,
        'nome' => 'Tênis Teste 1 Coluna',
        'preco_base' => 150.00,
        'sku' => 'SKU-COL1',
        'estoque' => 10,
        'description' => 'Descrição do tênis',
    ]);

    $response = $this->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos");

    $response->assertOk();
    $response->assertSee('grid-cols-1');
    $response->assertSee('data-mobile-cols="1"', false);
    $response->assertDontSee('data-mobile-cols="2"', false);
});

test('public storefront renders grid-cols-2 on mobile when merchant colunas_mobile is 2', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
        'colunas_mobile' => 2,
    ]);

    Products::create([
        'user_id' => $user->id,
        'nome' => 'Tênis Teste 2 Colunas',
        'preco_base' => 150.00,
        'sku' => 'SKU-COL2',
        'estoque' => 10,
        'description' => 'Descrição do tênis 2',
    ]);

    $response = $this->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos");

    $response->assertOk();
    $response->assertSee('grid-cols-2');
    $response->assertSee('data-mobile-cols="2"', false);
    $response->assertDontSee('data-mobile-cols="1"', false);
});

test('variant catalog storefront respects merchant colunas_mobile', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
        'colunas_mobile' => 2,
    ]);

    $catalogo = Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Catálogo Especial',
        'hash' => 'hashvariante',
        'desconto_index' => 10.00,
        'sob_consulta' => false,
        'mostrar_nome' => true,
    ]);

    Products::create([
        'user_id' => $user->id,
        'nome' => 'Produto Variante',
        'preco_base' => 200.00,
        'sku' => 'SKU-VAR',
        'estoque' => 5,
        'description' => 'Item variante',
    ]);

    $response = $this->get("http://" . env('APP_DOMAIN', 'saas.test') . "/{$catalogo->hash}/produtos");

    $response->assertOk();
    $response->assertSee('grid-cols-2');
    $response->assertSee('data-mobile-cols="2"', false);
});
