<?php

use App\Models\Atributo;
use App\Models\Grupo;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

test('product catalog index paginates products and preserves query parameters', function () {
    $user = User::factory()->create([
        'slug' => 'loja-teste-paginacao',
    ]);

    app()->instance(User::class, $user);

    for ($i = 1; $i <= 30; $i++) {
        Products::create([
            'nome' => "Produto Teste Paginacao {$i}",
            'description' => "Descricao do produto {$i}",
            'preco_base' => 10.00,
            'status' => true,
            'user_id' => $user->id,
        ]);
    }

    $response = $this->get(route('products.index', ['slug' => $user->slug]));

    $response->assertOk();
    $response->assertViewHas('products', function ($products) {
        return $products instanceof LengthAwarePaginator
            && $products->total() === 30
            && $products->perPage() === 25
            && $products->count() === 25;
    });

    $responsePage2 = $this->get(route('products.index', ['slug' => $user->slug, 'page' => 2, 'search' => 'Paginacao']));
    $responsePage2->assertOk();
    $responsePage2->assertViewHas('products', function ($products) {
        return $products instanceof LengthAwarePaginator
            && $products->currentPage() === 2
            && $products->count() === 5;
    });
});

test('direct client can create product with automatic slug and active status and update without losing attributes', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-criacao-produto',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    $grupo = Grupo::create([
        'user_id' => $user->id,
        'nome' => 'Camisetas',
    ]);

    $atributo = Atributo::create([
        'user_id' => $user->id,
        'nome' => 'Tamanho M',
    ]);

    $baseUrl = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test');

    // 1. Create product
    $storeResponse = $this->actingAs($user)->post("{$baseUrl}/products", [
        'name' => 'Camisa Linho Premium',
        'value' => '149,90',
        'description' => 'Camisa de linho leve e confortável',
        'images' => [
            UploadedFile::fake()->image('capa.jpg', 400, 400),
            UploadedFile::fake()->image('detalhe.jpg', 400, 400),
        ],
        'grupos' => [$grupo->id],
    ]);

    $storeResponse->assertRedirect();

    $product = Products::where('user_id', $user->id)->where('nome', 'Camisa Linho Premium')->first();
    expect($product)->not->toBeNull();
    expect($product->slug)->toBe('camisa-linho-premium');
    expect((bool) $product->status)->toBeTrue();
    expect($product->preco_base)->toBe('149,90');
    expect($product->productImages)->toHaveCount(2);
    expect($product->grupos->pluck('id')->toArray())->toBe([$grupo->id]);

    // Attach an attribute directly and ensure product update does not wipe it
    $product->atributos()->attach($atributo->id);
    expect($product->atributos()->count())->toBe(1);

    // 2. Update product
    $updateResponse = $this->actingAs($user)->patch("{$baseUrl}/products/{$product->id}", [
        'product' => $product->id,
        'name' => 'Camisa Linho Atualizada',
        'value' => '159,90',
        'description' => 'Descrição atualizada da camisa',
        'grupos' => [$grupo->id],
    ]);

    $updateResponse->assertRedirect();

    $product->refresh();
    expect($product->nome)->toBe('Camisa Linho Atualizada');
    expect($product->slug)->toBe('camisa-linho-premium');
    expect($product->preco_base)->toBe('159,90');
    expect($product->atributos()->pluck('atributos.id')->toArray())->toBe([$atributo->id]);
});

test('product creation and update validate price format and bounds and description max length', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-validacao-produto',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);
    $baseUrl = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test');

    // 1. Invalid non-numeric or malformed price
    foreach (['abc', '1,23.45', '1..234,56'] as $invalidValue) {
        $this->actingAs($user)->post("{$baseUrl}/products", [
            'name' => 'Produto Preço Inválido',
            'value' => $invalidValue,
            'description' => 'Descrição válida',
            'images' => [UploadedFile::fake()->image('img.jpg')],
        ])->assertSessionHasErrors(['value']);
    }

    // 2. Zero price
    $this->actingAs($user)->post("{$baseUrl}/products", [
        'name' => 'Produto Preço Zero',
        'value' => '0,00',
        'description' => 'Descrição válida',
        'images' => [UploadedFile::fake()->image('img.jpg')],
    ])->assertSessionHasErrors(['value']);

    // 3. Price exceeding decimal(10,2) max (99999999.99)
    $this->actingAs($user)->post("{$baseUrl}/products", [
        'name' => 'Produto Preço Excedido',
        'value' => '100.000.000,00',
        'description' => 'Descrição válida',
        'images' => [UploadedFile::fake()->image('img.jpg')],
    ])->assertSessionHasErrors(['value']);

    // 4. Description exceeding 255 characters
    $this->actingAs($user)->post("{$baseUrl}/products", [
        'name' => 'Produto Descrição Longa',
        'value' => '50,00',
        'description' => str_repeat('a', 256),
        'images' => [UploadedFile::fake()->image('img.jpg')],
    ])->assertSessionHasErrors(['description']);

    expect(Products::where('user_id', $user->id)->count())->toBe(0);

    // 5. Valid price with thousands separator (1.234,56)
    $this->actingAs($user)->post("{$baseUrl}/products", [
        'name' => 'Produto Milhar Válido',
        'value' => '1.234,56',
        'description' => 'Descrição dentro do limite',
        'images' => [UploadedFile::fake()->image('img.jpg')],
    ])->assertSessionHasNoErrors();

    $product = Products::where('user_id', $user->id)->first();
    expect($product)->not->toBeNull();
    expect($product->preco_base)->toBe('1.234,56');

    // 6. Update also validates price and description max length
    $this->actingAs($user)->patch("{$baseUrl}/products/{$product->id}", [
        'product' => $product->id,
        'name' => 'Produto Milhar Válido',
        'value' => '0,00',
        'description' => str_repeat('b', 256),
    ])->assertSessionHasErrors(['value', 'description']);
});

test('product create and edit forms render textarea for description without hidden required file inputs or attributes', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-form-produto',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);

    $product = Products::create([
        'nome' => 'Produto Form Teste',
        'slug' => 'produto-form-teste',
        'description' => 'Descrição teste',
        'preco_base' => 45.00,
        'status' => true,
        'user_id' => $user->id,
    ]);

    $baseUrl = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test');

    $createResponse = $this->actingAs($user)->get("{$baseUrl}/products/create");
    $createResponse->assertOk();
    $createResponse->assertViewMissing('atributos');
    $createResponse->assertSee('<textarea name="description"', false);
    $createResponse->assertSee('maxlength="255"', false);
    $createResponse->assertDontSee('onchange="addFiles(this.files)" require', false);
    $createResponse->assertDontSee('id="images-real" name="images[]" multiple accept="image/png,image/jpeg,image/webp" class="hidden" required', false);
    $createResponse->assertSee('URL.revokeObjectURL', false);

    $editResponse = $this->actingAs($user)->get("{$baseUrl}/products/{$product->id}/edit");
    $editResponse->assertOk();
    $editResponse->assertViewMissing('atributos');
    $editResponse->assertViewMissing('atributosVinculados');
    $editResponse->assertSee('<textarea name="description"', false);
    $editResponse->assertSee('maxlength="255"', false);
});

test('product creation rolls back database and deletes stored files on transaction failure', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-rollback-produto',
        'plano_expira_em' => now()->addDays(30),
    ]);

    app()->instance(User::class, $user);
    $baseUrl = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test');

    // Passa um grupo inexistente para forçar falha de integridade dentro da transação após criar o produto e salvar a imagem
    $this->actingAs($user)->post("{$baseUrl}/products", [
        'name' => 'Produto Com Falha Transacional',
        'value' => '99,90',
        'description' => 'Deve sofrer rollback completo',
        'images' => [UploadedFile::fake()->image('rollback.jpg')],
        'grupos' => [999999],
    ])->assertStatus(500);

    expect(Products::where('user_id', $user->id)->count())->toBe(0);
    expect(Storage::disk('public')->allFiles('path'))->toBeEmpty();
});




