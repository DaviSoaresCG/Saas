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

test('how it works section renders 3 sequential steps', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('id="como-funciona"', false);
    $response->assertSee('Do catálogo ao pedido em 3 passos.', false);
    $response->assertSee('Monte sua vitrine', false);
    $response->assertSee('Compartilhe seu link', false);
    $response->assertSee('Receba e confirme', false);
    $response->assertSee('01', false);
    $response->assertSee('02', false);
    $response->assertSee('03', false);
});

test('shopper story banner renders mobile shopping message', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('A sua vitrine na palma da mão.', false);
    $response->assertSee('Uma compra fácil, do começo ao fim', false);
    $response->assertSee('mulher_comprando', false);
});

test('order demo section renders whatsapp interactive simulation with order #1042 and copy button', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('id="demonstracao"', false);
    $response->assertSee('O pedido chega. Você só precisa responder.', false);
    $response->assertSee('Pedido #1042', false);
    $response->assertSee('Camiseta Basic', false);
    $response->assertSee('Bermuda Jeans', false);
    $response->assertSee('Total: R$ 239,70', false);
    $response->assertSee('João da Silva', false);
    $response->assertSee('copy-order-btn', false);
});

test('pricing section renders monthly and yearly plans with transparent pricing and benefits', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('id="precos"', false);
    $response->assertSee('Você vende. O lucro é seu.', false);
    $response->assertSee('29,90', false);
    $response->assertSee('299', false);
    $response->assertSee('MAIS POPULAR', false);
    $response->assertSee('economize 17%', false);
    $response->assertSee('Produtos ilimitados', false);
    $response->assertSee('Pedidos pelo WhatsApp', false);
    $response->assertSee('Subdomínio exclusivo', false);
    $response->assertSee('Métricas de cliques', false);
    $response->assertSee('0% de comissão', false);
    $response->assertSee('Pix ou cartão de crédito', false);
});

test('pricing ctas direct visitors to register and authenticated users to pagamento pending', function () {
    // Visitor check
    $visitorResponse = $this->get('/');
    $visitorResponse->assertStatus(200);
    $visitorResponse->assertSee(route('register'), false);

    // Authenticated user check
    $user = User::factory()->create([
        'status' => 'pending',
        'slug' => 'loja-preco-teste',
    ]);

    $authResponse = $this->actingAs($user)->get('/');
    $authResponse->assertStatus(200);
    $authResponse->assertSee(route('pagamento.pending', ['plan' => 'monthly']), false);
    $authResponse->assertSee(route('pagamento.pending', ['plan' => 'yearly']), false);
});

test('coupon form is rendered with discount verification support', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');
    $response->assertStatus(200);
    $response->assertSee(route('counpons.validate'), false);
    $response->assertSee('Tem um cupom de desconto?', false);
    $response->assertSee('name="code"', false);
});





