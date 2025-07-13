<?php

namespace Tests\Feature\Users;

use App\Models\User;

use Tests\BaseTestCase;

class UserShowTest extends BaseTestCase
{
    public function test_it_shows_user_successfully(): void
    {
        $this->actingAsUser();

        $user = User::factory()->create([
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $response = $this->getJson(route('users.show', $user));

        $response->assertOk()
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    'id',
                    'name',
                    'surname',
                    'email',
                    'phone',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'status' => [
                    'status' => 'OK',
                ],
                'data' => [
                    'id' => $user->id,
                    'name' => 'Juan',
                    'surname' => 'Pérez',
                    'email' => 'juan.perez@example.com',
                    'phone' => '1234567890',
                ]
            ]);
    }

    public function test_it_returns_404_for_nonexistent_user(): void
    {
        $this->actingAsUser();

        $response = $this->getJson(route('users.show', 999));

        $response->assertNotFound();
    }
}
