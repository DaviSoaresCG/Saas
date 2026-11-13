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
