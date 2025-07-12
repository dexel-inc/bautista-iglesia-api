<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_user_successfully(): void
    {
        $user = User::factory()->create([
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $updateData = [
            'name' => 'Juan Carlos',
            'surname' => 'Pérez García',
            'email' => 'juan.carlos@example.com',
            'phone' => '0987654321',
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertOk()
            ->assertJson([
                'user' => [
                    'name' => 'Juan Carlos',
                    'surname' => 'Pérez García',
                    'email' => 'juan.carlos@example.com',
                    'phone' => '0987654321',
                ],
                'message' => 'The user was created correctly',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Juan Carlos',
            'surname' => 'Pérez García',
            'email' => 'juan.carlos@example.com',
            'phone' => '0987654321',
        ]);
    }

    public function test_it_updates_user_partially(): void
    {
        $user = User::factory()->create([
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $updateData = [
            'name' => 'Juan Carlos',
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertOk()
            ->assertJson([
                'user' => [
                    'name' => 'Juan Carlos',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Juan Carlos',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);
    }

    public function test_it_validates_email_format(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'email' => 'invalid-email',
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_email_uniqueness(): void
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $updateData = [
            'email' => 'user1@example.com',
        ];

        $response = $this->putJson(route('users.update', $user2), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_allows_same_email_for_same_user(): void
    {
        $user = User::factory()->create(['email' => 'juan.perez@example.com']);

        $updateData = [
            'name' => 'Juan Carlos',
            'email' => 'juan.perez@example.com',
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertOk()
            ->assertJson([
                'user' => [
                    'name' => 'Juan Carlos',
                    'email' => 'juan.perez@example.com',
                ],
            ]);
    }

    public function test_it_validates_phone_uniqueness(): void
    {
        $user1 = User::factory()->create(['phone' => '1234567890']);
        $user2 = User::factory()->create(['phone' => '0987654321']);

        $updateData = [
            'phone' => '1234567890',
        ];

        $response = $this->putJson(route('users.update', $user2), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    }

    public function test_it_allows_same_phone_for_same_user(): void
    {
        $user = User::factory()->create(['phone' => '1234567890']);

        $updateData = [
            'name' => 'Juan Carlos',
            'phone' => '1234567890',
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertOk()
            ->assertJson([
                'user' => [
                    'name' => 'Juan Carlos',
                    'phone' => '1234567890',
                ],
            ]);
    }

    public function test_it_validates_password_minimum_length(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'password' => '123',
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_validates_string_fields(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 123,
            'surname' => 456,
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'surname']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => str_repeat('a', 256),
            'surname' => str_repeat('b', 256),
            'email' => str_repeat('c', 250) . '@example.com',
            'phone' => str_repeat('1', 21),
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'surname', 'email', 'phone']);
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Juan Carlos',
            'surname' => 'Pérez García',
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertOk()
            ->assertJsonStructure([
                'user' => [
                    'name',
                    'surname',
                ],
                'message'
            ]);
    }

    public function test_it_hashes_password_correctly(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'password' => 'newpassword123',
        ];

        $this->putJson(route('users.update', $user), $updateData);

        $user->refresh();
        $this->assertNotEquals('newpassword123', $user->password);
        $this->assertTrue(password_verify('newpassword123', $user->password));
    }

    public function test_it_returns_404_for_nonexistent_user(): void
    {
        $updateData = [
            'name' => 'Juan Carlos',
        ];

        $response = $this->putJson(route('users.update', 999), $updateData);

        $response->assertNotFound();
    }

    public function test_it_handles_special_characters_in_names(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'María José',
            'surname' => 'García-López',
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertOk()
            ->assertJson([
                'user' => [
                    'name' => 'María José',
                    'surname' => 'García-López',
                ],
            ]);
    }

    public function test_it_updates_only_provided_fields(): void
    {
        $user = User::factory()->create([
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $updateData = [
            'name' => 'Juan Carlos',
            'surname' => 'Pérez García',
        ];

        $response = $this->putJson(route('users.update', $user), $updateData);

        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Juan Carlos',
            'surname' => 'Pérez García',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);
    }

    public function test_it_accepts_empty_update_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $response = $this->putJson(route('users.update', $user), []);

        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);
    }
}
