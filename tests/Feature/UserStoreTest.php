<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_user_successfully(): void
    {
        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertCreated()
            ->assertJson([
                'user' => [
                    'name' => 'Juan',
                    'surname' => 'Pérez',
                    'email' => 'juan.perez@example.com',
                    'phone' => '1234567890',
                ],
                'message' => 'The user was created correctly',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson(route('users.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'surname', 'email', 'phone', 'password']);
    }

    public function test_it_validates_email_format(): void
    {
        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'invalid-email',
            'phone' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_email_uniqueness(): void
    {
        User::factory()->create(['email' => 'juan.perez@example.com']);

        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_phone_uniqueness(): void
    {
        User::factory()->create(['phone' => '1234567890']);

        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    }

    public function test_it_validates_password_minimum_length(): void
    {
        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
            'password' => '123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_validates_string_fields(): void
    {
        $userData = [
            'name' => 123,
            'surname' => 456,
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'surname']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $userData = [
            'name' => str_repeat('a', 256),
            'surname' => str_repeat('b', 256),
            'email' => str_repeat('c', 250) . '@example.com',
            'phone' => str_repeat('1', 21),
            'password' => 'password123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'surname', 'email', 'phone']);
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertCreated()
            ->assertJsonStructure([
                'user' => [
                    'name',
                    'surname',
                    'email',
                    'phone',
                ],
                'message'
            ]);
    }

    public function test_it_hashes_password_correctly(): void
    {
        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
        ];

        $this->postJson(route('users.store'), $userData);

        $user = User::where('email', 'juan.perez@example.com')->first();
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(password_verify('password123', $user->password));
    }

    public function test_it_returns_404_for_invalid_route(): void
    {
        $response = $this->postJson('/api/invalid-route');

        $response->assertNotFound();
    }

    public function test_it_handles_special_characters_in_names(): void
    {
        $userData = [
            'name' => 'María José',
            'surname' => 'García-López',
            'email' => 'maria.garcia@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertCreated()
            ->assertJson([
                'user' => [
                    'name' => 'María José',
                    'surname' => 'García-López',
                    'email' => 'maria.garcia@example.com',
                    'phone' => '1234567890',
                ],
            ]);
    }
}
