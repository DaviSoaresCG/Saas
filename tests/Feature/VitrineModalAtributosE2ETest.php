<?php

use App\Models\Atributo;
use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('vitrine product cards render data-atributos attribute for products with attributes and omit it for products without attributes', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-vitrine-attrs',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $attr1 = Atributo::create(['user_id' => $user->id, 'nome' => 'Bordô']);
    $attr2 = Atributo::create(['user_id' => $user->id, 'nome' => 'Tamanho M']);

    $productWithAttrs = Products::create([
        'user_id' => $user->id,
        'nome' => 'Vestido Festa',
        'slug' => 'vestido-festa',
        'description' => 'Vestido de festa bordô',
        'preco_base' => 350.00,
        'status' => true,
    ]);
    $productWithAttrs->atributos()->attach([$attr1->id, $attr2->id]);

    $productWithoutAttrs = Products::create([
        'user_id' => $user->id,
        'nome' => 'Brinco Prata',
        'slug' => 'brinco-prata',
        'description' => 'Brinco de prata 925',
        'preco_base' => 45.00,
        'status' => true,
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');

    $response = $this->get("http://{$user->slug}.{$domain}/produtos");
    $response->assertOk();

    // 1. O card com atributos deve possuir data-atributos com id e nome
    $response->assertSee('data-atributos="[{&quot;id&quot;:' . $attr1->id . ',&quot;nome&quot;:&quot;Bord\u00f4&quot;}', false);

    // 2. O card sem atributos NÃO deve conter data-atributos no seu form
    $response->assertDontSee('action="http://' . $user->slug . '.' . $domain . '/cart/add/' . $productWithoutAttrs->id . '" method="POST" data-atributos', false);

    // 3. O modal de adicionar ao carrinho deve estar disponível na vitrine
    $response->assertSee('id="add-to-cart-modal"', false);
    $response->assertSee('id="add-modal-attributes-container"', false);
    $response->assertSee('modal-attribute-checkbox', false);
});

test('end to end journey: admin creates attributes, customer selects and orders via whatsapp', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-e2e-completa',
        'plano_expira_em' => now()->addDays(30),
        'nome_loja' => 'Boutique Elegance',
        'whatsapp' => '11999990000',
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');
    $baseUrl = "http://{$user->slug}.{$domain}";

    // 1. Lojista cria atributos no painel
    $attrCor = Atributo::create(['user_id' => $user->id, 'nome' => 'Azul Céu']);
    $attrTam = Atributo::create(['user_id' => $user->id, 'nome' => 'TAM 38']);

    // 2. Lojista cadastra produto via POST vinculando os atributos criados
    $storeProductResponse = $this->actingAs($user)->post("{$baseUrl}/products", [
        'name' => 'Sandália Verão',
        'value' => '189,90',
        'description' => 'Sandália elegante e confortável',
        'images' => [UploadedFile::fake()->image('sandalia.jpg', 400, 400)],
        'atributos' => [$attrCor->id, $attrTam->id],
    ]);
    $storeProductResponse->assertRedirect();

    $product = Products::where('user_id', $user->id)->where('nome', 'Sandália Verão')->first();
    expect($product)->not->toBeNull();
    expect($product->atributos)->toHaveCount(2);

    // 3. Cliente acessa a vitrine e a página do produto
    $vitrineResponse = $this->get("{$baseUrl}/produtos");
    $vitrineResponse->assertOk();
    $vitrineResponse->assertSee('Sandália Verão', false);

    $productResponse = $this->get("{$baseUrl}/produtos/{$product->id}");
    $productResponse->assertOk();
    $productResponse->assertSee('Azul Céu', false);
    $productResponse->assertSee('TAM 38', false);

    // 4. Cliente adiciona ao carrinho com os atributos escolhidos e uma observação
    $addToCartResponse = $this->post("{$baseUrl}/cart/add/{$product->id}", [
        'quantity' => 2,
        'atributos' => [$attrCor->id, $attrTam->id],
        'observacao' => 'Embalar para presente',
    ]);
    $addToCartResponse->assertRedirect();
    $addToCartResponse->assertSessionHasNoErrors();

    // 5. Cliente visualiza o carrinho com os badges dos atributos
    $cartResponse = $this->get("{$baseUrl}/cart/index");
    $cartResponse->assertOk();
    $cartResponse->assertSee('Sandália Verão', false);
    $cartResponse->assertSee('Azul Céu', false);
    $cartResponse->assertSee('TAM 38', false);
    $cartResponse->assertSee('Embalar para presente', false);

    // 6. Cliente finaliza o pedido (checkout)
    $checkoutResponse = $this->post("{$baseUrl}/pedido-finalizar", [
        'cliente_nome' => 'Mariana Silva',
        'cliente_phone' => '(11) 98877-6655',
    ]);
    $checkoutResponse->assertOk();
    $checkoutResponse->assertViewIs('pedidos.whatsapp');

    // 7. Validação da persistência no banco de dados
    $pedido = Pedido::where('user_id', $user->id)->first();
    expect($pedido)->not->toBeNull();
    expect($pedido->cliente_nome)->toBe('Mariana Silva');

    $item = ItemPedido::where('pedido_id', $pedido->id)->first();
    expect($item)->not->toBeNull();
    expect($item->product_id)->toBe($product->id);
    expect($item->quantidade)->toBe(2);
    expect($item->observacao)->toBe('Embalar para presente');
    expect($item->atributos)->toContain('Azul Céu');
    expect($item->atributos)->toContain('TAM 38');

    // 8. Validação da mensagem gerada para o WhatsApp
    $checkoutResponse->assertSee('Finalizar no WhatsApp', false);
    $checkoutResponse->assertViewHas('url', function ($whatsappUrl) {
        $decoded = urldecode($whatsappUrl);
        return str_contains($decoded, 'Azul Céu')
            && str_contains($decoded, 'TAM 38')
            && str_contains($decoded, 'Embalar para presente');
    });
});

test('storefront product without attributes can be added with quantity and observation without requiring attributes', function () {
    $user = User::factory()->create([
        'tipo_cliente' => 'direct',
        'slug' => 'loja-vitrine-sem-attrs',
        'plano_expira_em' => now()->addDays(30),
    ]);

    $product = Products::create([
        'user_id' => $user->id,
        'nome' => 'Cinto Couro',
        'slug' => 'cinto-couro',
        'description' => 'Cinto marrom clássico',
        'preco_base' => 79.90,
        'status' => true,
    ]);

    $domain = env('APP_DOMAIN', 'saas.test');
    $baseUrl = "http://{$user->slug}.{$domain}";

    // Simula submissão do modal da vitrine para produto simples (sem atributos)
    $response = $this->post("{$baseUrl}/cart/add/{$product->id}", [
        'quantity' => 3,
        'observacao' => 'Ajustar fivela',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();

    $cart = session('cart', []);
    expect($cart)->not->toBeEmpty();
    $item = reset($cart);
    expect($item['id'])->toBe($product->id);
    expect($item['quantity'])->toBe(3);
    expect($item['observacao'])->toBe('Ajustar fivela');
    expect($item['atributos'])->toBeEmpty();
});
