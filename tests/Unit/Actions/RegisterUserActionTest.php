<?php

namespace Tests\Unit\Actions;

use App\Actions\Users\RegisterUserAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_user(): void
    {
        $action = new RegisterUserAction();
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '123456789',
            'password' => 'password123',
        ];

        $user = $action->execute($data);

        $this->assertInstanceOf(\App\Models\User::class, $user);
        $this->assertEquals('John', $user->name);
        $this->assertEquals('Doe', $user->surname);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('123456789', $user->phone);
        $this->assertTrue(password_verify('password123', $user->password));
    }

    public function test_user_is_saved_to_database(): void
    {
        $action = new RegisterUserAction();
        $data = [
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '987654321',
            'password' => 'password123',
        ];

        $user = $action->execute($data);

        $this->assertDatabaseHas('users', [
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '987654321',
        ]);
    }
} 