<?php

use App\Models\Atributo;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('direct client create product page loads tenant attributes and empty state message', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-create-atributos',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);
    $baseUrl = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test');

    // 1. Without attributes: shows empty warning
    $responseEmpty = $this->actingAs($user)->get("{$baseUrl}/products/create");
    $responseEmpty->assertOk();
    $responseEmpty->assertViewHas('atributos', fn ($list) => $list->isEmpty());
    $responseEmpty->assertSee('Nenhum atributo cadastrado no momento.', false);

    // 2. With attributes: shows checkboxes with names
    $attr1 = Atributo::create(['user_id' => $user->id, 'nome' => 'Azul Marinho']);
    $attr2 = Atributo::create(['user_id' => $user->id, 'nome' => 'Tamanho 42']);

    $responseWithAttrs = $this->actingAs($user)->get("{$baseUrl}/products/create");
    $responseWithAttrs->assertOk();
    $responseWithAttrs->assertViewHas('atributos', fn ($list) => $list->count() === 2);
    $responseWithAttrs->assertSee('Azul Marinho', false);
    $responseWithAttrs->assertSee('Tamanho 42', false);
    $responseWithAttrs->assertSee('name="atributos[]"', false);
});

test('direct client can create product with selected attributes in pivot table', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-store-atributos',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);
    $baseUrl = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test');

    $attr1 = Atributo::create(['user_id' => $user->id, 'nome' => 'Vermelho']);
    $attr2 = Atributo::create(['user_id' => $user->id, 'nome' => 'Tamanho P']);

    $response = $this->actingAs($user)->post("{$baseUrl}/products", [
        'name' => 'Vestido Elegante',
        'value' => '199,90',
        'description' => 'Vestido vermelho tamanho P',
        'images' => [UploadedFile::fake()->image('vestido.jpg', 400, 400)],
        'atributos' => [$attr1->id, $attr2->id],
    ]);

    $response->assertRedirect();

    $product = Products::where('user_id', $user->id)->where('nome', 'Vestido Elegante')->first();
    expect($product)->not->toBeNull();
    expect($product->atributos->pluck('id')->sort()->values()->toArray())
        ->toBe(collect([$attr1->id, $attr2->id])->sort()->values()->toArray());
});

test('direct client cannot associate attributes belonging to another tenant', function () {
    Storage::fake('public');

    $userA = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-tenant-a',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $userB = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-tenant-b',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $userA);
    $baseUrlA = 'http://' . $userA->slug . '.' . env('APP_DOMAIN', 'saas.test');

    $attrUserB = Atributo::create(['user_id' => $userB->id, 'nome' => 'Exclusivo do Tenant B']);

    $response = $this->actingAs($userA)->post("{$baseUrlA}/products", [
        'name' => 'Produto Inválido',
        'value' => '50,00',
        'description' => 'Tentativa de associar atributo de outro tenant',
        'images' => [UploadedFile::fake()->image('foto.jpg')],
        'atributos' => [$attrUserB->id],
    ]);

    $response->assertSessionHasErrors(['atributos.0']);
    expect(Products::where('user_id', $userA->id)->count())->toBe(0);
});

test('direct client edit product page displays attributes with checked state for attached attributes', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-edit-atributos',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);
    $baseUrl = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test');

    $attr1 = Atributo::create(['user_id' => $user->id, 'nome' => 'Preto']);
    $attr2 = Atributo::create(['user_id' => $user->id, 'nome' => 'Branco']);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Camisa Social',
        'slug' => 'camisa-social',
        'description' => 'Camisa social preta',
        'preco_base' => 120.00,
        'status' => true,
    ]);

    $product->atributos()->attach($attr1->id);

    $response = $this->actingAs($user)->get("{$baseUrl}/products/{$product->id}/edit");
    $response->assertOk();
    $response->assertViewHas('atributos', fn ($list) => $list->count() === 2);
    $response->assertViewHas('atributosVinculados', [$attr1->id]);
    $response->assertSee('value="' . $attr1->id . '" checked', false);
    $response->assertDontSee('value="' . $attr2->id . '" checked', false);
});

test('direct client can update product attributes and deselect all attributes', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-update-atributos',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);
    $baseUrl = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test');

    $attr1 = Atributo::create(['user_id' => $user->id, 'nome' => 'Tamanho 38']);
    $attr2 = Atributo::create(['user_id' => $user->id, 'nome' => 'Tamanho 39']);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Sapato Couro',
        'slug' => 'sapato-couro',
        'description' => 'Sapato de couro legítimo',
        'preco_base' => 250.00,
        'status' => true,
    ]);

    $product->atributos()->attach($attr1->id);

    // 1. Update to switch from attr1 to attr2
    $responseUpdate = $this->actingAs($user)->patch("{$baseUrl}/products/{$product->id}", [
        'product' => $product->id,
        'name' => 'Sapato Couro Premium',
        'value' => '270,00',
        'description' => 'Sapato atualizado',
        '_has_atributos' => 1,
        'atributos' => [$attr2->id],
    ]);

    $responseUpdate->assertRedirect();
    $product->refresh();
    expect($product->atributos->pluck('id')->toArray())->toBe([$attr2->id]);

    // 2. Update deselecting all attributes (_has_atributos=1 without atributos array)
    $responseDeselect = $this->actingAs($user)->patch("{$baseUrl}/products/{$product->id}", [
        'product' => $product->id,
        'name' => 'Sapato Couro Premium',
        'value' => '270,00',
        'description' => 'Sapato atualizado sem atributos',
        '_has_atributos' => 1,
    ]);

    $responseDeselect->assertRedirect();
    $product->refresh();
    expect($product->atributos)->toHaveCount(0);
});

test('direct client cannot update product with attributes belonging to another tenant', function () {
    $userA = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-tenant-update-a',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $userB = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-tenant-update-b',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $userA);
    $baseUrlA = 'http://' . $userA->slug . '.' . env('APP_DOMAIN', 'saas.test');

    $attrUserB = Atributo::create(['user_id' => $userB->id, 'nome' => 'Atributo Tenant B']);

    $productA = Products::create([
        'user_id' => $userA->id,
        'nome' => 'Produto Tenant A',
        'slug' => 'produto-tenant-a',
        'description' => 'Descricao A',
        'preco_base' => 90.00,
        'status' => true,
    ]);

    $response = $this->actingAs($userA)->patch("{$baseUrlA}/products/{$productA->id}", [
        'product' => $productA->id,
        'name' => 'Produto A Editado',
        'value' => '95,00',
        'description' => 'Descricao editada',
        '_has_atributos' => 1,
        'atributos' => [$attrUserB->id],
    ]);

    $response->assertSessionHasErrors(['atributos.0']);
    $productA->refresh();
    expect($productA->atributos)->toHaveCount(0);
});

test('direct client edit product re-renders deselected attributes after validation failure using old input', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-old-atributos',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);
    $baseUrl = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test');

    $attr = Atributo::create(['user_id' => $user->id, 'nome' => 'Verde Militar']);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Jaqueta',
        'slug' => 'jaqueta',
        'description' => 'Jaqueta bonita',
        'preco_base' => 150.00,
        'status' => true,
    ]);

    $product->atributos()->attach($attr->id);

    // Simulate validation error with _has_atributos=1 and empty atributos (user unchecked all)
    $response = $this->actingAs($user)
        ->withSession([
            '_old_input' => [
                'name' => 'Jaqueta',
                '_has_atributos' => '1',
                // 'atributos' is omitted because user unchecked all checkboxes
            ],
            'errors' => new \Illuminate\Support\ViewErrorBag(),
        ])
        ->get("{$baseUrl}/products/{$product->id}/edit");

    $response->assertOk();
    // Because _has_atributos was submitted without attr id, it must NOT show checked
    $response->assertDontSee('value="' . $attr->id . '" checked', false);
});

test('admin sidebar displays atributos link only for direct clients and highlights it when active', function () {
    $directUser = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-sidebar-direct',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $erpUser = User::factory()->create([
        'tipo_cliente' => 'erp',
        'slug' => 'loja-sidebar-erp',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');

    // Direct client sees Atributos link in dashboard sidebar
    app()->instance(User::class, $directUser);
    $directResponse = $this->actingAs($directUser)->get("http://{$directUser->slug}.{$domain}/dashboard");
    $directResponse->assertOk();
    $directResponse->assertSee("http://{$directUser->slug}.{$domain}/atributos", false);
    $directResponse->assertSee('Atributos');

    // Direct client accessing /atributos sees highlighted sidebar link
    $atributosResponse = $this->actingAs($directUser)->get("http://{$directUser->slug}.{$domain}/atributos");
    $atributosResponse->assertOk();
    $atributosResponse->assertSee('Atributos');

    // ERP client does NOT see Atributos link in dashboard sidebar
    app()->instance(User::class, $erpUser);
    $erpResponse = $this->actingAs($erpUser)->get("http://{$erpUser->slug}.{$domain}/dashboard");
    $erpResponse->assertOk();
    $erpResponse->assertDontSee("http://{$erpUser->slug}.{$domain}/atributos", false);
});
