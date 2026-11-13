<?php

use App\Models\User;

test('auth split layout renders split screen grid with typography and assets', function () {
    $view = $this->blade(
        '<x-auth-split-layout title="Teste Auth" eyebrow="Boas-vindas" heading="Acesse sua conta." subheading="Subtítulo teste.">
            <p>Formulário de teste</p>
        </x-auth-split-layout>'
    );

    // Grid split-screen
    $view->assertSee('lg:grid-cols-[1fr_1fr]', false);
    $view->assertSee('#f7f5f3', false);

    // Fonts & assets
    $view->assertSee('family=Instrument+Serif', false);
    $view->assertSee('family=Inter', false);
    $view->assertSee('lucide', false);

    // Left column: BrandMark, back link, security note, dynamic year
    $currentYear = date('Y');
    $view->assertSee('zap', false);
    $view->assertSee('catálogo', false);
    $view->assertSee('Voltar para o site', false);
    $view->assertSee(route('home'), false);
    $view->assertSee('Boas-vindas', false);
    $view->assertSee('Acesse sua conta.', false);
    $view->assertSee('Subtítulo teste.', false);
    $view->assertSee('Formulário de teste', false);
    $view->assertSee('Seus dados de acesso são protegidos', false);
    $view->assertSee("© {$currentYear} ZapCatálogo", false);

    // Right column: Photography, badge and headline
    $view->assertSee('mulher_comprando', false);
    $view->assertSee('Simples para você. Fácil para seus clientes.', false);
    $view->assertSee('Uma vitrine que transforma visitas em conversas.', false);
    $view->assertSee('Organize seu catálogo e receba cada pedido pronto para continuar no WhatsApp.', false);
});
