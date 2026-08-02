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

test('SigaDezAPI syncProducts route creates products for authenticated user', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'erp',
    ]);

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/sync-products', [
        'products' => [
            [
                'id' => 999,
                'sku' => 'SKU-SIGA-01',
                'name' => 'Produto SigaDez API',
                'description' => 'Descrição do produto SigaDez',
                'price' => 250.00,
            ]
        ]
    ]);

    $response->assertStatus(202)
        ->assertJsonStructure(['message']);

    $product = Products::where('erp_id', 999)->where('user_id', $user->id)->first();
    expect($product)->not->toBeNull();
    expect($product->user_id)->toBe($user->id);
    expect($product->nome)->toBe('Produto SigaDez API');
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

test('Stripe Pix webhook successfully extends plan', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(5),
    ]);

    // Construct valid Stripe Webhook signature
    $payload = json_encode([
        'type' => 'payment_intent.succeeded',
        'data' => [
            'object' => [
                'metadata' => [
                    'user_id' => $user->id,
                    'plan' => 'yearly',
                ]
            ]
        ]
    ]);

    // Generate valid Stripe signature header
    $secret = env('STRIPE_WEBHOOK_SECRET', 'whsec_test');
    $time = time();
    $signature = hash_hmac('sha256', $time . '.' . $payload, $secret);
    $sigHeader = "t={$time},v1={$signature}";

    // Set temporary webhook secret in env for the test
    config(['cashier.webhook.secret' => $secret]);

    $response = $this->postJson(route('api.payments.pix.webhook'), json_decode($payload, true), [
        'Stripe-Signature' => $sigHeader,
    ]);

    $response->assertOk();

    $user->refresh();
    expect($user->status)->toBe('active');
    // It should add 365 days to the expiration
    expect($user->plano_expira_em->isAfter(now()->addDays(360)))->toBeTrue();
});


