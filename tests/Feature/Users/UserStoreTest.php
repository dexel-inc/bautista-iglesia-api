<?php

namespace Tests\Feature\Users;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\User;

use Tests\BaseTestCase;

class UserStoreTest extends BaseTestCase
{
    public function test_it_creates_user_successfully(): void
    {
        $this->actingAsUser();

        $userData = [
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertStatus(Response::HTTP_CREATED)
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
                    'status' => Status::OK,
                ],
                'data' => [
                    'name' => 'Juan',
                    'surname' => 'Pérez',
                    'email' => 'juan.perez@example.com',
                    'phone' => '1234567890',
                ]
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
        $this->actingAsUser();

        $response = $this->postJson(route('users.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_validates_email_format(): void
    {
        $this->actingAsUser();

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
        $this->actingAsUser();

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
        $this->actingAsUser();

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
        $this->actingAsUser();

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
        $this->actingAsUser();

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
        $this->actingAsUser();

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

    public function test_it_hashes_password_correctly(): void
    {
        $this->actingAsUser();

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
}
