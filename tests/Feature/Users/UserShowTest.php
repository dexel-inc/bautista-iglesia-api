<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_user_successfully(): void
    {
        $user = User::factory()->create([
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $response = $this->getJson(route('users.show', $user));

        $response->assertOk()
            ->assertJson([
                'id' => $user->id,
                'name' => 'Juan',
                'surname' => 'Pérez',
                'email' => 'juan.perez@example.com',
                'phone' => '1234567890',
            ]);
    }

    public function test_it_returns_404_for_nonexistent_user(): void
    {
        $response = $this->getJson(route('users.show', 999));

        $response->assertNotFound();
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson(route('users.show', $user));

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'name',
                'surname',
                'email',
                'phone',
                'email_verified_at',
                'created_at',
                'updated_at',
            ]);
    }
}
