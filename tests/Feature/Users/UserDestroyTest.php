<?php

namespace Tests\Feature\Users;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_destroys_user_successfully(): void
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
                'body' => [
                    'status' => Status::OK,
                    'reason' => Response::HTTP_OK,
                    'message' => 'The user was deleted correctly',
                ],
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
}
