<?php

namespace Tests\Unit\Actions;

use App\Actions\Users\LoginUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LoginUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $action = new LoginUserAction();
        $data = [
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $result = $action->execute($data);

        $this->assertArrayHasKey('access_token', $result);
        $this->assertArrayHasKey('token_type', $result);
        $this->assertArrayHasKey('user', $result);
        $this->assertEquals('Bearer', $result['token_type']);
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertEquals($user->id, $result['user']->id);
    }

    public function test_throws_exception_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $action = new LoginUserAction();
        $data = [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ];

        $this->expectException(ValidationException::class);

        $action->execute($data);
    }

    public function test_throws_exception_with_nonexistent_email(): void
    {
        $action = new LoginUserAction();
        $data = [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ];

        $this->expectException(ValidationException::class);

        $action->execute($data);
    }
} 