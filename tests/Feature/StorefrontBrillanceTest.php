<?php

use App\Models\User;

test('storefront layout loads instrument serif and inter typography in styles stack', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');
    $response = $this->get("http://{$user->slug}.{$domain}/produtos");

    $response->assertOk();
    $response->assertSee('family=Instrument+Serif', false);
    $response->assertSee('family=Inter', false);
    $response->assertSee('.font-serif', false);
});

test('app.css clean theme defines brillance palette variables and preserves alternative themes', function () {
    $cssContent = file_get_contents(resource_path('css/app.css'));

    // Brillance Clean Palette
    expect($cssContent)->toContain(':root, [data-theme="clean"]');
    expect($cssContent)->toMatch('/--bg-page:\s*#f7f5f3;/');
    expect($cssContent)->toMatch('/--bg-card:\s*#ffffff;/');
    expect($cssContent)->toMatch('/--color-primary:\s*#37322f;/');
    expect($cssContent)->toMatch('/--text-base:\s*#37322f;/');
    expect($cssContent)->toMatch('/--text-muted:\s*#605a57;/');
    expect($cssContent)->toMatch('/--text-on-primary:\s*#ffffff;/');

    // Preservation of alternative themes
    expect($cssContent)->toContain('[data-theme="ocean"]');
    expect($cssContent)->toContain('[data-theme="sunset"]');
    expect($cssContent)->toContain('[data-theme="midnight"]');
    expect($cssContent)->toContain('[data-theme="forest"]');
    expect($cssContent)->toContain('[data-theme="premium"]');
    expect($cssContent)->toContain('[data-theme="lavender"]');
    expect($cssContent)->toContain('[data-theme="terracotta"]');
    expect($cssContent)->toContain('[data-theme="cyber"]');
});
