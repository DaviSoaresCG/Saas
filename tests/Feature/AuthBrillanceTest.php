<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

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

test('login screen renders brillance split layout with toggle senha and register link', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Que bom ter você de volta', false);
    $response->assertSee('Entre na sua conta.', false);
    $response->assertSee('Acesse seu painel e continue cuidando da sua loja.', false);
    $response->assertSee('name="email"', false);
    $response->assertSee('voce@exemplo.com', false);
    $response->assertSee('name="password"', false);
    $response->assertSee('Sua senha', false);
    $response->assertSee('Entrar', false);
    $response->assertSee('Ainda não tem conta?', false);
    $response->assertSee(route('register'), false);
    $response->assertSee('mulher_comprando', false);
});

test('login screen displays validation error on failed authentication', function () {
    $response = $this->from('/login')->post('/login', [
        'email' => 'naoexiste@exemplo.com',
        'password' => 'senhaerrada123',
    ]);

    $response->assertRedirect('/login');

    $followResponse = $this->followRedirects($response);
    $followResponse->assertStatus(200);
    $followResponse->assertSee('Email ou senha errados', false);
});

test('user can log in successfully with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('minhasenha123'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'minhasenha123',
    ]);

    $this->assertAuthenticatedAs($user);
});

test('register screen renders brillance split layout with all onboarding fields', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertSee('Sua loja começa aqui', false);
    $response->assertSee('Crie sua conta.', false);
    $response->assertSee('Prepare sua vitrine digital e comece a receber pedidos pelo WhatsApp.', false);
    $response->assertSee('name="name"', false);
    $response->assertSee('name="email"', false);
    $response->assertSee('name="whatsapp"', false);
    $response->assertSee('name="store_name"', false);
    $response->assertSee('name="password"', false);
    $response->assertSee('name="password_confirmation"', false);
    $response->assertSee('Criar minha conta', false);
    $response->assertSee('Já tem uma conta?', false);
    $response->assertSee(route('login'), false);
    $response->assertSee('mulher_comprando', false);
});

test('register screen displays validation errors when submitting empty form', function () {
    $response = $this->from('/register')->post('/register', []);

    $response->assertRedirect('/register');

    $followResponse = $this->followRedirects($response);
    $followResponse->assertStatus(200);
    $followResponse->assertSee('Este campo é obrigatório', false);
});

test('new merchant registration creates user with cleaned whatsapp and unique slug', function () {
    $response = $this->post('/register', [
        'name' => 'Ana Clara Loja',
        'email' => 'anaclara@exemplo.com',
        'whatsapp' => '(11) 98765-4321',
        'store_name' => 'Boutique Ana Clara',
        'password' => 'Segredo@123',
        'password_confirmation' => 'Segredo@123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'anaclara@exemplo.com')->first();
    expect($user)->not->toBeNull();
    expect($user->whatsapp)->toBe('11987654321');
    expect($user->nome_loja)->toBe('Boutique Ana Clara');
    expect($user->slug)->toBe('boutique-ana-clara');
});

test('forgot-password screen renders brillance split layout with recovery fields', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
    $response->assertSee('Recuperação de acesso', false);
    $response->assertSee('Esqueceu a senha?', false);
    $response->assertSee('name="email"', false);
    $response->assertSee('Enviar link de recuperação', false);
    $response->assertSee(route('login'), false);
    $response->assertSee('mulher_comprando', false);
});

test('forgot-password can request reset password link', function () {
    Notification::fake();

    $user = User::factory()->create();

    $response = $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset-password screen renders brillance split layout with token and password fields', function () {
    Notification::fake();

    $user = User::factory()->create();
    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        $response->assertStatus(200);
        $response->assertSee('Nova credencial', false);
        $response->assertSee('Crie sua nova senha.', false);
        $response->assertSee('name="token"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="password_confirmation"', false);
        $response->assertSee('Redefinir senha', false);
        $response->assertSee('mulher_comprando', false);

        return true;
    });
});

test('user can reset password with valid token and is redirected to login', function () {
    Notification::fake();

    $user = User::factory()->create();
    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'NovaSenhaSegura123',
            'password_confirmation' => 'NovaSenhaSegura123',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('login'));

        return true;
    });
});
