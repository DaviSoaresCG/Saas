<?php

use App\Models\Atributo;
use App\Models\Products;
use App\Models\User;

test('product show page renders selectable attribute pills when product has attributes', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-show-attrs',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $attr1 = Atributo::create(['user_id' => $user->id, 'nome' => 'Azul Turquesa']);
    $attr2 = Atributo::create(['user_id' => $user->id, 'nome' => 'Tamanho 40']);

    $productWithAttrs = Products::create([
        'user_id' => $user->id,
        'nome' => 'Tênis Esportivo',
        'slug' => 'tenis-esportivo',
        'description' => 'Tênis confortável',
        'preco_base' => 299.90,
        'status' => true,
    ]);
    $productWithAttrs->atributos()->attach([$attr1->id, $attr2->id]);

    $productWithoutAttrs = Products::create([
        'user_id' => $user->id,
        'nome' => 'Meia Esportiva',
        'slug' => 'meia-esportiva',
        'description' => 'Meia de algodão',
        'preco_base' => 19.90,
        'status' => true,
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');

    // 1. Produto COM atributos exibe seção, pills e validação
    $responseWithAttrs = $this->get("http://{$user->slug}.{$domain}/produtos/{$productWithAttrs->id}");
    $responseWithAttrs->assertOk();
    $responseWithAttrs->assertSee('Azul Turquesa', false);
    $responseWithAttrs->assertSee('Tamanho 40', false);
    $responseWithAttrs->assertSee('name="atributos[]"', false);
    $responseWithAttrs->assertSee('value="' . $attr1->id . '"', false);
    $responseWithAttrs->assertSee('value="' . $attr2->id . '"', false);
    $responseWithAttrs->assertSee('attribute-checkbox', false);

    // 2. Produto SEM atributos não exibe seletores de atributos
    $responseWithoutAttrs = $this->get("http://{$user->slug}.{$domain}/produtos/{$productWithoutAttrs->id}");
    $responseWithoutAttrs->assertOk();
    $responseWithoutAttrs->assertDontSee('name="atributos[]"', false);
    $responseWithoutAttrs->assertDontSee('id="product-attributes-list"', false);
    $responseWithoutAttrs->assertDontSee('Opções disponíveis:', false);
});

test('adding product with attributes to cart requires attribute selection', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-cart-require-attrs',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $attr = Atributo::create(['user_id' => $user->id, 'nome' => 'TAM 38']);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Calça Jeans',
        'slug' => 'calca-jeans',
        'description' => 'Calça jeans básica',
        'preco_base' => 120.00,
        'status' => true,
    ]);
    $product->atributos()->attach($attr->id);

    $domain = env('APP_DOMAIN', 'saas.test');

    // 1. Tentar adicionar sem atributos -> erro de validação
    $responseEmpty = $this->post("http://{$user->slug}.{$domain}/cart/add/{$product->id}", []);
    $responseEmpty->assertRedirect();
    $responseEmpty->assertSessionHasErrors(['atributos']);
    expect(session('cart', []))->toBeEmpty();

    // 2. Tentar adicionar com atributo de outro tenant ou não vinculado -> erro de validação
    $otherUser = User::factory()->create(['slug' => 'outro-tenant', 'tipo_cliente' => 'direct']);
    $otherAttr = Atributo::create(['user_id' => $otherUser->id, 'nome' => 'Atributo Intruso']);

    $responseForeign = $this->post("http://{$user->slug}.{$domain}/cart/add/{$product->id}", [
        'atributos' => [$otherAttr->id],
    ]);
    $responseForeign->assertRedirect();
    $responseForeign->assertSessionHasErrors(['atributos']);
    expect(session('cart', []))->toBeEmpty();

    // 3. Adicionar com atributo válido -> sucesso
    $responseValid = $this->post("http://{$user->slug}.{$domain}/cart/add/{$product->id}", [
        'atributos' => [$attr->id],
    ]);
    $responseValid->assertRedirect();
    $responseValid->assertSessionHasNoErrors();

    $cart = session('cart', []);
    expect($cart)->not->toBeEmpty();
    $firstItem = reset($cart);
    expect($firstItem['id'])->toBe($product->id);
    expect($firstItem['atributos'])->toBe(['TAM 38']);
});

test('adding product without attributes to cart succeeds without attributes', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-cart-no-attrs',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Caneca Cerâmica',
        'slug' => 'caneca-ceramica',
        'description' => 'Caneca branca',
        'preco_base' => 35.00,
        'status' => true,
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');

    $response = $this->post("http://{$user->slug}.{$domain}/cart/add/{$product->id}", [
        'quantity' => 2,
    ]);
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();

    $cart = session('cart', []);
    expect($cart)->not->toBeEmpty();
    $firstItem = reset($cart);
    expect($firstItem['id'])->toBe($product->id);
    expect($firstItem['quantity'])->toBe(2);
    expect($firstItem['atributos'])->toBeEmpty();
});

test('cart page renders attribute badges and cart update handles composite cart keys', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-cart-view-attrs',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $attr1 = Atributo::create(['user_id' => $user->id, 'nome' => 'Vermelho']);
    $attr2 = Atributo::create(['user_id' => $user->id, 'nome' => 'TAM G']);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Camisa Polo',
        'slug' => 'camisa-polo',
        'description' => 'Camisa polo bordada',
        'preco_base' => 89.90,
        'status' => true,
    ]);
    $product->atributos()->attach([$attr1->id, $attr2->id]);

    $domain = env('APP_DOMAIN', 'saas.test');

    // Adiciona ao carrinho
    $this->post("http://{$user->slug}.{$domain}/cart/add/{$product->id}", [
        'atributos' => [$attr1->id, $attr2->id],
        'quantity' => 1,
    ]);

    // 1. Página do carrinho exibe as badges dos atributos
    $responseCart = $this->get("http://{$user->slug}.{$domain}/cart/index");
    $responseCart->assertOk();
    $responseCart->assertSee('Vermelho', false);
    $responseCart->assertSee('TAM G', false);

    $cart = session('cart', []);
    $cartKey = key($cart);

    // 2. Endpoint de update do carrinho funciona com a chave composta
    $responseUpdate = $this->postJson("http://{$user->slug}.{$domain}/cart/update", [
        'product_id' => $cartKey,
        'quantity' => 3,
    ]);
    $responseUpdate->assertOk();
    $responseUpdate->assertJson([
        'success' => true,
        'quantity' => 3,
        'cartCounter' => 3,
    ]);
});
