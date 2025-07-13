<?php

namespace Tests\Feature\Auth;

use App\Constants\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_registers_user_successfully(): void
    {
        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertCreated()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'name' => 'Juan',
                    'surname' => 'Pérez',
                    'email' => 'juan@example.com',
                    'phone' => '123456789',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson(route('auth.register'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'surname', 'email', 'phone', 'password']);
    }

    public function test_it_validates_email_format(): void
    {
        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'invalid-email',
            'phone' => '123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_email_uniqueness(): void
    {
        User::factory()->create(['email' => 'juan@example.com']);

        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_phone_uniqueness(): void
    {
        User::factory()->create(['phone' => '123456789']);

        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    }

    public function test_it_validates_password_confirmation(): void
    {
        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_validates_password_minimum_length(): void
    {
        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
            'password' => '123',
            'password_confirmation' => '123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }
} 