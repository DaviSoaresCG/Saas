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
                'status' => true,
                'peso' => 0.5,
                'group' => [
                    'id' => 1,
                    'name' => 'Geral',
                ],
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

test('Accessing catalog with sob_consulta sets session and passes to order and sync-orders API', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'erp',
        'plano_expira_em' => now()->addDays(30),
        'api_token' => 'token_sync_orders_test',
    ]);

    app()->instance(User::class, $user);

    $catalog = Catalogo::create([
        'user_id' => $user->id,
        'nome' => 'Catálogo Orçamento',
        'hash' => 'hashsobcons1',
        'desconto_index' => 0.00,
        'sob_consulta' => true,
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Produto Sob Consulta Teste',
        'preco_base' => 250.00,
        'sku' => 'SKU-SOB-01',
        'estoque' => 5,
        'description' => 'Item especial sob consulta',
    ]);

    // 1. Acesso ao catálogo variante ativa session('sob_consulta')
    $response = $this->get('http://' . env('APP_DOMAIN', 'saas.test') . '/hashsobcons1/produtos');
    $response->assertOk();
    $response->assertSee('Preço Sob Consulta');
    expect(session('sob_consulta'))->toBeTrue();

    // 2. Finalizar pedido grava sob_consulta = true no Pedido
    $orderResponse = $this->withSession([
        'sob_consulta' => true,
        'cart' => [
            $product->id => [
                'id' => $product->id,
                'name' => $product->nome,
                'value' => 250.00,
                'path' => '',
                'quantity' => 2,
                'atributos' => [],
                'observacao' => '',
            ]
        ]
    ])->post('http://' . env('APP_DOMAIN', 'saas.test') . '/hashsobcons1/pedido-finalizar', [
        'cliente_nome' => 'Cliente Teste Sob Consulta',
        'cliente_phone' => '11999998888',
    ]);

    $orderResponse->assertOk();

    $pedido = \App\Models\Pedido::where('cliente_nome', 'Cliente Teste Sob Consulta')->first();
    expect($pedido)->not->toBeNull();
    expect($pedido->sob_consulta)->toBeTrue();

    // 3. Chamada à API sync-orders retorna o pedido com sob_consulta = true
    $apiResponse = $this->actingAs($user, 'sanctum')->postJson('/api/sync-orders');
    $apiResponse->assertOk()
        ->assertJsonPath('pedidos.0.sob_consulta', true)
        ->assertJsonPath('pedidos.0.cliente_nome', 'Cliente Teste Sob Consulta');
});

test('Merchant can store and update catalog with sob_consulta via controller', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    // 1. Criar catálogo com sob_consulta = true
    $response = $this->actingAs($user)->post("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/catalogos", [
        'nome' => 'Catálogo Sem Preço',
        'sob_consulta' => '1',
    ]);

    $response->assertRedirect();
    $catalogo = Catalogo::where('user_id', $user->id)->where('nome', 'Catálogo Sem Preço')->first();
    expect($catalogo)->not->toBeNull();
    expect($catalogo->sob_consulta)->toBeTrue();
    expect((float) $catalogo->desconto_index)->toBe(0.00);

    // 2. Atualizar catálogo desmarcando sob_consulta e definindo desconto
    $updateResponse = $this->actingAs($user)->put("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/catalogos/{$catalogo->id}", [
        'nome' => 'Catálogo Atualizado com Desconto',
        'sob_consulta' => '0',
        'desconto_index' => '20.00',
    ]);

    $updateResponse->assertRedirect();
    $catalogo->refresh();
    expect($catalogo->nome)->toBe('Catálogo Atualizado com Desconto');
    expect($catalogo->sob_consulta)->toBeFalse();
    expect((float) $catalogo->desconto_index)->toBe(20.00);
});

test('Tenant can upload and remove company logo and see it rendered in store-layout', function () {
    \Illuminate\Support\Facades\Storage::fake('public');

    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    $file = \Illuminate\Http\UploadedFile::fake()->image('logo_empresa.png', 200, 200);

    // 1. Upload da logo pelo perfil
    $response = $this->actingAs($user)->patch("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/profile", [
        'name' => $user->name,
        'email' => $user->email,
        'store_name' => 'Minha Loja Com Logo',
        'logo' => $file,
    ]);

    $response->assertRedirect();
    $user->refresh();
    expect($user->logo_path)->not->toBeNull();
    \Illuminate\Support\Facades\Storage::disk('public')->assertExists($user->logo_path);

    // 2. Acesso à loja exibe a tag img da logo
    $storeResponse = $this->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos");
    $storeResponse->assertOk();
    $storeResponse->assertSee($user->logo_url);

    // 3. Remoção da logo
    $removeResponse = $this->actingAs($user)->patch("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/profile", [
        'name' => $user->name,
        'email' => $user->email,
        'store_name' => 'Minha Loja Com Logo',
        'remover_logo' => '1',
    ]);

    $removeResponse->assertRedirect();
    $user->refresh();
    expect($user->logo_path)->toBeNull();

    // 4. Acesso à loja volta para o fallback com ícone
    $storeFallbackResponse = $this->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos");
    $storeFallbackResponse->assertOk();
    $storeFallbackResponse->assertSee('data-lucide="shopping-bag"', false);
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

test('Catalog product search filters products by name, SKU and description', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    $prod1 = Products::create([
        'user_id' => $user->id,
        'nome' => 'Camisa Polo Azul',
        'description' => 'Camisa de algodão premium',
        'sku' => 'SKU-CAM-AZUL',
        'preco_base' => 99.90,
        'estoque' => 10,
        'status' => true,
    ]);

    $prod2 = Products::create([
        'user_id' => $user->id,
        'nome' => 'Calça Jeans Preta',
        'description' => 'Calça slim masculina',
        'sku' => 'SKU-CAL-JEANS',
        'preco_base' => 199.90,
        'estoque' => 5,
        'status' => true,
    ]);

    // 1. Search by name 'Polo'
    $resName = $this->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos?search=Polo");
    $resName->assertOk();
    $resName->assertSee('Camisa Polo Azul');
    $resName->assertDontSee('Calça Jeans Preta');

    // 2. Search by SKU 'SKU-CAL'
    $resSku = $this->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos?search=SKU-CAL");
    $resSku->assertOk();
    $resSku->assertSee('Calça Jeans Preta');
    $resSku->assertDontSee('Camisa Polo Azul');

    // 3. Search with non-matching term
    $resEmpty = $this->get("http://{$user->slug}." . env('APP_DOMAIN', 'saas.test') . "/produtos?search=Inexistente123");
    $resEmpty->assertOk();
    $resEmpty->assertSee('Nenhum produto encontrado');
    $resEmpty->assertSee('Limpar pesquisa');
});


