<?php

use App\Models\Products;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

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
