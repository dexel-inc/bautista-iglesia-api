<?php

namespace Tests\Feature\Users;

use App\Models\User;

use Tests\BaseTestCase;

class UserIndexTest extends BaseTestCase
{    public function test_it_returns_all_users_successfully(): void
    {
        $this->actingAsUser();

        User::factory()->count(3)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertOk()
            ->assertJsonCount(4, 'data') // 3 + 1 del usuario autenticado
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => []
            ]);
    }

    public function test_it_returns_empty_array_when_no_users_exist(): void
    {
        $this->actingAsUser();

        $response = $this->getJson(route('users.index'));

        $response->assertOk()
            ->assertJsonCount(1, 'data') // 1 del usuario autenticado
            ->assertJson([
                'status' => [
                    'status' => 'OK'
                ]
            ]);
    }

    public function test_it_requires_authentication(): void
    {
        $this->assertRequiresAuthentication('GET', route('users.index'));
    }
}
