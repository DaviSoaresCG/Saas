<?php

use App\Models\User;

test('landing page returns 200 ok for visitors', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('landing page contains essential seo meta tags and title', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('ZapCatálogo', false);
    $response->assertSee('<meta name="viewport"', false);
    $response->assertSee('<meta name="description"', false);
});

test('landing page loads brillance typography instrument serif and inter', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('family=Instrument+Serif', false);
    $response->assertSee('family=Inter', false);
});

test('landing page configures lucide icons and brillance styling tokens', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('lucide', false);
    $response->assertSee('#f7f5f3', false);
    $response->assertSee('#37322f', false);
});

test('header renders brand, navigation anchors and guest actions for visitors', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('zap', false);
    $response->assertSee('catálogo', false);
    $response->assertSee('href="#recursos"', false);
    $response->assertSee('href="#como-funciona"', false);
    $response->assertSee('href="#precos"', false);
    $response->assertSee('href="#duvidas"', false);
    $response->assertSee(route('login'), false);
    $response->assertSee('Entrar no painel', false);
    $response->assertSee('Criar catálogo', false);
});

test('header renders merchant links and logout form for authenticated users', function () {
    $user = User::factory()->create([
        'status' => 'unlimited',
        'slug' => 'loja-teste-header',
        'name' => 'Lojista Teste',
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
    $response->assertSee('Lojista Teste', false);
    $response->assertSee(route('products.index', ['slug' => 'loja-teste-header']), false);
    $response->assertSee(route('logout'), false);
});

test('hero section renders brillance editorial headline, ctas and trust badges', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('ZapCatálogo 2.0', false);
    $response->assertSee('Sem taxas por venda', false);
    $response->assertSee('Seu WhatsApp pode vender', false);
    $response->assertSee('0% de comissão', false);
    $response->assertSee('Começar agora', false);
    $response->assertSee('Ver demonstração', false);
});

test('mockup section renders smartphone store preview and whatsapp incoming order bubble', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('boutique.zapcatalogo.com.br', false);
    $response->assertSee('Blusa Aurora', false);
    $response->assertSee('Bolsa Serena', false);
    $response->assertSee('R$ 239,80', false);
    $response->assertSee('Seu WhatsApp Business', false);
    $response->assertSee('#1024', false);
    $response->assertSee('Marina Costa', false);
});

test('metrics bar renders 4 key business indicators', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('0%', false);
    $response->assertSee('comissão por venda', false);
    $response->assertSee('1 link', false);
    $response->assertSee('para sua loja', false);
    $response->assertSee('24/7', false);
    $response->assertSee('catálogo no ar', false);
    $response->assertSee('100%', false);
    $response->assertSee('do lucro é seu', false);
});

test('features grid renders 8 core platform capabilities with icons', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('id="recursos"', false);
    $response->assertSee('Sua loja online. Do seu jeito.', false);
    $response->assertSee('Seu endereço profissional', false);
    $response->assertSee('Pedidos prontos no WhatsApp', false);
    $response->assertSee('Zero comissão por venda', false);
    $response->assertSee('A sua marca em cada detalhe', false);
    $response->assertSee('Vitrine do seu jeito', false);
    $response->assertSee('Dados para vender melhor', false);
    $response->assertSee('Promoções com link próprio', false);
    $response->assertSee('Produtos e variações', false);
});

test('comparison section contrasts old way against zapcatalogo workflow', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Menos conversa perdida. Mais pedidos fechados.', false);
    $response->assertSee('Do jeito antigo', false);
    $response->assertSee('Tudo se perde no chat.', false);
    $response->assertSee('PDF pesado que ninguém abre', false);
    $response->assertSee('Com ZapCatálogo', false);
    $response->assertSee('A venda flui naturalmente.', false);
    $response->assertSee('Pedido completo, somado e pronto no WhatsApp', false);
});



