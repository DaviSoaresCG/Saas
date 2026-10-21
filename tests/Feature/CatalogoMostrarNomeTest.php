<?php

use App\Models\Catalogo;
use App\Models\User;

test('merchant can store catalog with default mostrar_nome as true', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    $response = $this->actingAs($user)->post("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/catalogos", [
        'nome' => 'Catálogo Teste Nome Padrão',
        'desconto_index' => '10.00',
    ]);

    $response->assertRedirect();
    $catalogo = Catalogo::where('user_id', $user->id)->where('nome', 'Catálogo Teste Nome Padrão')->first();
    expect($catalogo)->not->toBeNull();
    expect((bool) $catalogo->mostrar_nome)->toBeTrue();
});

test('merchant can store catalog with explicit mostrar_nome false', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    $response = $this->actingAs($user)->post("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/catalogos", [
        'nome' => 'Catálogo Ocultando Nome',
        'desconto_index' => '5.00',
        'mostrar_nome' => '0',
    ]);

    $response->assertRedirect();
    $catalogo = Catalogo::where('user_id', $user->id)->where('nome', 'Catálogo Ocultando Nome')->first();
    expect($catalogo)->not->toBeNull();
    expect((bool) $catalogo->mostrar_nome)->toBeFalse();
});

test('merchant can update catalog to toggle mostrar_nome', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    $catalogo = Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Catálogo Original',
        'hash' => 'hash12345678',
        'desconto_index' => 0.00,
        'sob_consulta' => false,
        'mostrar_nome' => true,
    ]);

    // Atualiza para desmarcado (mostrar_nome = 0)
    $response = $this->actingAs($user)->put("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/catalogos/{$catalogo->id}", [
        'nome' => 'Catálogo Original',
        'desconto_index' => 0.00,
        'mostrar_nome' => '0',
    ]);

    $response->assertRedirect();
    $catalogo->refresh();
    expect((bool) $catalogo->mostrar_nome)->toBeFalse();

    // Atualiza novamente para marcado (mostrar_nome = 1)
    $response2 = $this->actingAs($user)->put("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/catalogos/{$catalogo->id}", [
        'nome' => 'Catálogo Original',
        'desconto_index' => 0.00,
        'mostrar_nome' => '1',
    ]);

    $response2->assertRedirect();
    $catalogo->refresh();
    expect((bool) $catalogo->mostrar_nome)->toBeTrue();
});

test('admin catalogos index renders mostrar_nome checkbox and badges', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Catálogo Visível',
        'hash' => 'hashvisivel1',
        'desconto_index' => 10.00,
        'sob_consulta' => false,
        'mostrar_nome' => true,
    ]);

    Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Catálogo Oculto',
        'hash' => 'hashoculto12',
        'desconto_index' => 5.00,
        'sob_consulta' => false,
        'mostrar_nome' => false,
    ]);

    $response = $this->actingAs($user)->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/catalogos");

    $response->assertOk();
    $response->assertSee('Mostrar nome do Catálogo');
    $response->assertSee('Catálogo Visível');
    $response->assertSee('Catálogo Oculto');
    $response->assertSee('Nome oculto');
});

test('visiting catalog with mostrar_nome true stores session and renders catalog name in store header', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $catalogo = Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Catálogo Especial de Verão',
        'hash' => 'verao1234512',
        'desconto_index' => 10.00,
        'sob_consulta' => false,
        'mostrar_nome' => true,
    ]);

    \App\Models\Products::create([
        'user_id' => $user->id,
        'nome' => 'Camiseta Verão',
        'preco_base' => 50.00,
        'sku' => 'SKU-VERAO',
        'estoque' => 5,
        'description' => 'Camiseta fresca de algodão',
    ]);

    $response = $this->get("http://" . env('APP_DOMAIN', 'saas.test') . "/{$catalogo->hash}/produtos");

    $response->assertOk();
    $response->assertSessionHas('catalog_mostrar_nome', true);
    $response->assertSee('Catálogo Especial de Verão');
});

test('visiting catalog with mostrar_nome false stores session and hides catalog name in store header', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $catalogo = Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Catálogo Oculto Secreto',
        'hash' => 'secreto12345',
        'desconto_index' => 10.00,
        'sob_consulta' => false,
        'mostrar_nome' => false,
    ]);

    \App\Models\Products::create([
        'user_id' => $user->id,
        'nome' => 'Camiseta Secreta',
        'preco_base' => 50.00,
        'sku' => 'SKU-SECRETA',
        'estoque' => 5,
        'description' => 'Camiseta exclusiva',
    ]);

    $response = $this->get("http://" . env('APP_DOMAIN', 'saas.test') . "/{$catalogo->hash}/produtos");

    $response->assertOk();
    $response->assertSessionHas('catalog_mostrar_nome', false);
    $response->assertDontSee('Catálogo Oculto Secreto');
});

test('direct subdomain access clears catalog_mostrar_nome from session', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    // Simula uma sessão anterior com catálogo
    $response = $this->withSession(['catalog_mostrar_nome' => false])
        ->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos");

    $response->assertOk();
    $response->assertSessionMissing('catalog_mostrar_nome');
});
