<?php

use App\Mail\WelcomeEmail;
use App\Models\User;

test('welcome email mailable renders brillance branding and merchant details', function () {
    $user = User::factory()->create([
        'name' => 'Mariana Silveira',
        'email' => 'mariana@lojadafe.com',
        'nome_loja' => 'Loja da Fé',
        'slug' => 'loja-da-fe',
    ]);

    $mailable = new WelcomeEmail($user);

    $mailable->assertHasSubject('Email de boas vindas!');
    $mailable->assertSeeInHtml('Mariana Silveira');
    $mailable->assertSeeInHtml('Loja da Fé');
    $mailable->assertSeeInHtml('mariana@lojadafe.com');
    $mailable->assertSeeInHtml('zap');
    $mailable->assertSeeInHtml('catálogo');
    $mailable->assertSeeInHtml(route('dashboard', ['slug' => $user->slug]));
    $mailable->assertSeeInHtml('Acessar meu Painel');
});

test('both welcome view files exist and render valid html with merchant dashboard url', function () {
    $user = User::factory()->create([
        'name' => 'Carlos Alberto',
        'email' => 'carlos@calcados.com',
        'nome_loja' => 'Calçados Alberto',
        'slug' => 'calcados-alberto',
    ]);

    $emailsView = view('emails.welcome', ['user' => $user])->render();
    $emailView = view('email.welcome', ['user' => $user])->render();

    expect($emailsView)->toContain(route('dashboard', ['slug' => $user->slug]));
    expect($emailsView)->toContain('Carlos Alberto');
    expect($emailsView)->toContain('Calçados Alberto');

    expect($emailView)->toContain(route('dashboard', ['slug' => $user->slug]));
    expect($emailView)->toContain('Carlos Alberto');
});
