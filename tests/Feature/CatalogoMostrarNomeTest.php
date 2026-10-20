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
