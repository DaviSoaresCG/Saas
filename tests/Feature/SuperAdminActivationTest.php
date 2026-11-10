<?php

use App\Models\User;

test('super admin can activate a direct client indefinitely and deactivate them', function () {
    $superAdminEmail = config('app.super_admin_email', 'davisoaresgigante@gmail.com');
    $superAdmin = User::factory()->create([
        'email' => $superAdminEmail,
    ]);

    $client = User::factory()->create([
        'tipo_cliente' => 'direct',
        'status' => 'active',
        'plano_expira_em' => null,
    ]);

    expect($client->isLojaAtiva())->toBeFalse();
    expect($client->isUnlimited())->toBeFalse();

    // 1. Ativar por tempo indeterminado
    $response = $this->actingAs($superAdmin)
        ->patch(route('superadmin.users.activate-unlimited', $client));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $client->refresh();
    expect($client->status)->toBe('unlimited');
    expect($client->plano_expira_em)->toBeNull();
    expect($client->isUnlimited())->toBeTrue();
    expect($client->isLojaAtiva())->toBeTrue();
    expect($client->tipo_cliente)->toBe('direct');

    // 2. Desativar cliente
    $deactivateResponse = $this->actingAs($superAdmin)
        ->patch(route('superadmin.users.deactivate', $client));

    $deactivateResponse->assertRedirect();
    $deactivateResponse->assertSessionHas('success');

    $client->refresh();
    expect($client->status)->toBe('inactive');
    expect($client->plano_expira_em)->toBeNull();
    expect($client->isUnlimited())->toBeFalse();
    expect($client->isLojaAtiva())->toBeFalse();
});

test('super admin cannot deactivate their own account', function () {
    $superAdminEmail = config('app.super_admin_email', 'davisoaresgigante@gmail.com');
    $superAdmin = User::factory()->create([
        'email' => $superAdminEmail,
    ]);

    $response = $this->actingAs($superAdmin)
        ->patch(route('superadmin.users.deactivate', $superAdmin));

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $superAdmin->refresh();
    expect($superAdmin->status)->not->toBe('inactive');
});

test('non-superadmin cannot activate or deactivate clients', function () {
    $normalUser = User::factory()->create([
        'email' => 'normal@example.com',
    ]);

    $targetUser = User::factory()->create([
        'plano_expira_em' => null,
    ]);

    $this->actingAs($normalUser)
        ->patch(route('superadmin.users.activate-unlimited', $targetUser))
        ->assertForbidden();

    $this->actingAs($normalUser)
        ->patch(route('superadmin.users.deactivate', $targetUser))
        ->assertForbidden();
});

test('super admin pages display activation and deactivation buttons', function () {
    $superAdminEmail = config('app.super_admin_email', 'davisoaresgigante@gmail.com');
    $superAdmin = User::factory()->create([
        'email' => $superAdminEmail,
    ]);

    $inactiveClient = User::factory()->create([
        'name' => 'Cliente Inativo Teste',
        'tipo_cliente' => 'direct',
        'plano_expira_em' => null,
    ]);

    $this->actingAs($superAdmin)
        ->get(route('superadmin.index'))
        ->assertOk()
        ->assertSee('Ativar Indeterminado');

    $this->actingAs($superAdmin)
        ->get(route('superadmin.users.show', $inactiveClient))
        ->assertOk()
        ->assertSee('Ativar por Tempo Indeterminado');

    $inactiveClient->update(['status' => 'unlimited']);

    $this->actingAs($superAdmin)
        ->get(route('superadmin.users.show', $inactiveClient))
        ->assertOk()
        ->assertSee('Desativar Cliente')
        ->assertSee('Tempo Indeterminado');
});

