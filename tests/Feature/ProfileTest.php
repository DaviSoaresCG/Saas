<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();
    $url = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test') . '/profile';

    $response = $this
        ->actingAs($user)
        ->get($url);

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();
    $url = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test') . '/profile';

    $response = $this
        ->actingAs($user)
        ->patch($url, [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect($url);

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();
    $url = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test') . '/profile';

    $response = $this
        ->actingAs($user)
        ->patch($url, [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect($url);

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();
    $url = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test') . '/profile';

    $response = $this
        ->actingAs($user)
        ->delete($url, [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();
    $url = 'http://' . $user->slug . '.' . env('APP_DOMAIN', 'saas.test') . '/profile';

    $response = $this
        ->actingAs($user)
        ->from($url)
        ->delete($url, [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect($url);

    $this->assertNotNull($user->fresh());
});
