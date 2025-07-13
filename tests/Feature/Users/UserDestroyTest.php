<?php

namespace Tests\Feature\Users;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\User;

use Tests\BaseTestCase;

class UserDestroyTest extends BaseTestCase
{public function test_it_destroys_user_successfully(): void
    {
        $this->actingAsUser();

        $user = User::factory()->create([
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $response = $this->deleteJson(route('users.destroy', $user));

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_it_returns_404_for_nonexistent_user(): void
    {
        $this->actingAsUser();

        $response = $this->deleteJson(route('users.destroy', 999));

        $response->assertNotFound();
    }
}
