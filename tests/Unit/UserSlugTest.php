<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_clean_hyphenated_slug(): void
    {
        $slug = User::generateUniqueSlug('Loja do Davi');
        $this->assertEquals('loja-do-davi', $slug);
    }

    public function test_handles_empty_or_special_chars_with_fallback(): void
    {
        $slug = User::generateUniqueSlug('!!!');
        $this->assertEquals('loja', $slug);
    }

    public function test_avoids_slug_collision_incrementally(): void
    {
        User::factory()->create(['slug' => 'minha-loja']);

        $newSlug1 = User::generateUniqueSlug('Minha Loja');
        $this->assertEquals('minha-loja-1', $newSlug1);

        User::factory()->create(['slug' => 'minha-loja-1']);

        $newSlug2 = User::generateUniqueSlug('Minha Loja');
        $this->assertEquals('minha-loja-2', $newSlug2);
    }

    public function test_ignores_own_id_when_generating_slug(): void
    {
        $user = User::factory()->create(['slug' => 'loja-propria']);

        $slug = User::generateUniqueSlug('Loja Propria', $user->id);
        $this->assertEquals('loja-propria', $slug);
    }
}
