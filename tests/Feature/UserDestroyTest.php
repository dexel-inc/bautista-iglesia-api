<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_user_successfully(): void
    {
        $user = User::factory()->create([
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $response = $this->deleteJson(route('users.destroy', $user));

        $response->assertOk()
            ->assertJson([
                'user deleted' => [
                    'name' => 'Juan',
                    'surname' => 'Pérez',
                    'email' => 'juan.perez@example.com',
                    'phone' => '1234567890',
                ],
                'message' => 'The user was deleted correctly',
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_it_returns_404_for_nonexistent_user(): void
    {
        $response = $this->deleteJson(route('users.destroy', 999));

        $response->assertNotFound();
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson(route('users.destroy', $user));

        $response->assertOk()
            ->assertJsonStructure([
                'user deleted' => [
                    'id',
                    'name',
                    'surname',
                    'email',
                    'phone',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
                'message'
            ]);
    }
} 