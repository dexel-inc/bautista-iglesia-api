<?php

namespace Tests\Feature\Auth;

use App\Constants\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthProtectedTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_gets_authenticated_user_successfully(): void
    {
        $user = User::factory()->create([
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(route('auth.me'));

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'id' => $user->id,
                    'name' => 'Juan',
                    'surname' => 'Pérez',
                    'email' => 'juan@example.com',
                    'phone' => '123456789',
                ],
            ]);
    }

    public function test_it_requires_authentication_for_me(): void
    {
        $response = $this->getJson(route('auth.me'));

        $response->assertUnauthorized();
    }

    public function test_it_logs_out_user_successfully(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('auth.logout'));

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
            ]);
    }

    public function test_it_requires_authentication_for_logout(): void
    {
        $response = $this->postJson(route('auth.logout'));

        $response->assertUnauthorized();
    }

    public function test_token_is_invalidated_after_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson(route('auth.me'));

        $response->assertOk();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson(route('auth.logout'));

        $response->assertOk();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
    }
}
