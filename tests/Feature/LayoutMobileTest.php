<?php

use App\Models\User;

test('authenticated merchant can access layout mobile settings page', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    $response = $this->actingAs($user)->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/dashboard/layout-mobile");

    $response->assertOk();
    $response->assertSee('Disposição dos Cards de Produto (Mobile)');
    $response->assertSee('1 Coluna');
    $response->assertSee('2 Colunas');
});

test('merchant can update colunas_mobile to 2', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    $response = $this->actingAs($user)->post("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/dashboard/layout-mobile", [
        'colunas_mobile' => 2,
    ]);

    $response->assertRedirect();
    $user->refresh();
    expect($user->colunas_mobile)->toBe(2);
});

test('merchant can update colunas_mobile to 1', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    // Inicializa com 2
    $user->update(['colunas_mobile' => 2]);

    $response = $this->actingAs($user)->post("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/dashboard/layout-mobile", [
        'colunas_mobile' => 1,
    ]);

    $response->assertRedirect();
    $user->refresh();
    expect($user->colunas_mobile)->toBe(1);
});

test('updating colunas_mobile fails with invalid value', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    $response = $this->actingAs($user)->post("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/dashboard/layout-mobile", [
        'colunas_mobile' => 5,
    ]);

    $response->assertSessionHasErrors('colunas_mobile');
});

test('unauthenticated user cannot access layout mobile settings', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $response = $this->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/dashboard/layout-mobile");
    $response->assertRedirect('/login');
});
