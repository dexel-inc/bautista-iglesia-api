<?php

namespace Tests\Feature\Auth;

use App\Constants\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_logs_in_user_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'juan@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.login'), $loginData);

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'token_type' => 'Bearer',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'surname' => $user->surname,
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ],
                ],
            ])
            ->assertJsonStructure([
                'status' => ['status'],
                'data' => [
                    'access_token',
                    'token_type',
                    'user' => ['id', 'name', 'surname', 'email', 'phone', 'created_at', 'updated_at'],
                ],
            ]);

        $this->assertNotNull($response->json('data.access_token'));
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson(route('auth.login'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_it_validates_email_format(): void
    {
        $loginData = [
            'email' => 'invalid-email',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.login'), $loginData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'juan@example.com',
            'password' => 'wrong-password',
        ];

        $response = $this->postJson(route('auth.login'), $loginData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_fails_with_nonexistent_user(): void
    {
        $loginData = [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.login'), $loginData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
} 