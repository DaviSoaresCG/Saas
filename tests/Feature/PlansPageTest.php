<?php

use App\Models\User;

test('plans page renders successfully with brillance design system elements for guests', function () {
    $response = $this->get('/plans');

    $response->assertStatus(200);
    $response->assertSee('zap', false);
    $response->assertSee('catálogo', false);
    $response->assertSee('Planos', false);
    $response->assertSee('29,90', false);
    $response->assertSee('299', false);
    $response->assertSee(route('register'), false);
    $response->assertSee(route('login'), false);
    $response->assertDontSee('bg-slate-900', false);
    $response->assertDontSee('bg-blue-600', false);
});

test('plans page renders authenticated user controls and checkout links', function () {
    $user = User::factory()->create([
        'name' => 'Renata Calçados',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)->get('/plans');

    $response->assertStatus(200);
    $response->assertSee('Renata Calçados', false);
    $response->assertSee(route('logout'), false);
    $response->assertSee(route('pagamento.pending', ['plan' => 'monthly']), false);
    $response->assertSee(route('pagamento.pending', ['plan' => 'yearly']), false);
});

test('active store merchant sees my store link in header on plans page', function () {
    $user = User::factory()->create([
        'name' => 'Boutique Elegance',
        'status' => 'active',
        'slug' => 'boutique-elegance',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $response = $this->actingAs($user)->get('/plans');

    $response->assertStatus(200);
    $response->assertSee(route('products.index', ['slug' => 'boutique-elegance']), false);
});
