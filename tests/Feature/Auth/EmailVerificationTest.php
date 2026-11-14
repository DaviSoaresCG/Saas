<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

test('email verification screen can be rendered with brillance layout', function () {
    $user = User::factory()->unverified()->create([
        'email' => 'lojista@exemplo.com',
    ]);

    $response = $this->actingAs($user)->get('/verify-email');

    $response->assertStatus(200);
    $response->assertSee('Quase lá', false);
    $response->assertSee('Confirme seu endereço de e-mail.', false);
    $response->assertSee('lojista@exemplo.com', false);
    $response->assertSee(route('verification.send'), false);
    $response->assertSee(route('logout'), false);
    $response->assertSee('mulher_comprando1.jpg', false);
});

test('email verification notification can be resent with status feedback', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)
        ->from('/verify-email')
        ->post(route('verification.send'));

    Notification::assertSentTo($user, VerifyEmail::class);
    $response->assertRedirect('/verify-email');
    $response->assertSessionHas('status', 'verification-link-sent');

    $followResponse = $this->followRedirects($response);
    $followResponse->assertStatus(200);
    $followResponse->assertSee('Um novo link de verificação foi enviado', false);
});

test('email can be verified and renders brillance success screen', function () {
    $user = User::factory()->unverified()->create([
        'name' => 'Lojista Sucesso',
        'email' => 'sucesso@exemplo.com',
    ]);

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertOk();
    $response->assertViewIs('auth.verify-email-success');
    $response->assertSee('Tudo pronto', false);
    $response->assertSee('Conta verificada com sucesso!', false);
    $response->assertSee(route('plans'), false);
    $response->assertSee('Escolher meu plano', false);
    $response->assertSee('mulher_comprando1.jpg', false);
});

test('email is not verified with invalid hash', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('unauthenticated user verifying email is redirected to login with success alert', function () {
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success', 'Email Verificado com sucesso! Faça o seu login.');
});
