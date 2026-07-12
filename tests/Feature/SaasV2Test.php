<?php

use App\Models\User;
use App\Models\Products;
use App\Models\Catalogo;
use Illuminate\Support\Facades\Hash;

test('ERP onboarding creates new user with CPF as password', function () {
    $response = $this->postJson(route('api.erp.onboarding'), [
        'name' => 'Lojista ERP Test',
        'email' => 'lojista.erp.test@gmail.com',
        'documento' => '98765432109',
        'nome_loja' => 'Loja ERP Test',
        'slug' => 'loja-erp-test',
        'whatsapp' => '63988888888',
    ], [
        'X-ERP-Key' => 'ERP_MASTER_KEY_DEFAULT_123'
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['success', 'api_token', 'store_url', 'user']);

    $user = User::where('email', 'lojista.erp.test@gmail.com')->first();
    expect($user)->not->toBeNull();
    expect($user->tipo_cliente)->toBe('erp');
    expect($user->need_change_password)->toBeTrue();
    expect(Hash::check('98765432109', $user->password))->toBeTrue();
});

test('Sync products API updates products for ERP user', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'erp',
        'api_token' => 'test_api_token_123',
    ]);

    // Bind tenant in container for model creating event
    app()->instance(User::class, $user);

    $response = $this->postJson(route('api.products.sync'), [
        'products' => [
            [
                'erp_id' => 'prod-001',
                'sku' => 'SKU-001',
                'nome' => 'Produto Teste ERP',
                'preco_base' => 120.50,
                'estoque' => 15,
                'description' => 'Descrição do produto teste',
                'foto_url' => 'https://example.com/foto.jpg',
            ]
        ]
    ], [
        'Authorization' => 'Bearer test_api_token_123'
    ]);

    $response->assertOk()
        ->assertJson(['success' => true]);

    $product = Products::where('erp_id', 'prod-001')->first();
    expect($product)->not->toBeNull();
    expect($product->nome)->toBe('Produto Teste ERP');
    expect($product->preco_base)->toBe('120,50'); // Formatted string from accessor
});

test('Accessing variant catalog applies discount and does not redirect', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    // Bind tenant in container for model creating event
    app()->instance(User::class, $user);

    $catalog = Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Catálogo Especial',
        'hash' => 'hash12345678',
        'desconto_index' => 15.00, // 15% discount
    ]);

    // Create a product
    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Produto Desconto',
        'preco_base' => 100.00,
        'sku' => 'SKU-DESC',
        'estoque' => 10,
        'description' => 'Descrição do produto com desconto',
    ]);

    $response = $this->get('http://' . env('APP_DOMAIN', 'saas.test') . '/hash12345678/produtos');

    $response->assertOk();
    expect((float) session('desconto_index'))->toBe(15.00);
    expect(session('catalog_hash'))->toBe('hash12345678');
});

test('ERP client is forbidden from accessing manual product creation and edit routes', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'erp',
    ]);

    $urlCreate = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test') . '/products/create';
    
    $response = $this
        ->actingAs($user)
        ->get($urlCreate);

    $response->assertStatus(403);
});

