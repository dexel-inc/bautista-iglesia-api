<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

abstract class BaseTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * Create and authenticate a user using Sanctum
     */
    protected function actingAsUser(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        Sanctum::actingAs($user);
        return $user;
    }

    /**
     * Create a user and return their authentication token
     */
    protected function createUserWithToken(array $attributes = []): array
    {
        $user = User::factory()->create($attributes);
        $token = $user->createToken('test-token')->plainTextToken;
        
        return [
            'user' => $user,
            'token' => $token,
            'headers' => ['Authorization' => 'Bearer ' . $token]
        ];
    }

    /**
     * Make an authenticated request
     */
    protected function authenticatedJson(string $method, string $uri, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        $auth = $this->createUserWithToken();
        $headers = array_merge($auth['headers'], $headers);
        
        return $this->json($method, $uri, $data, $headers);
    }

    /**
     * Test that unauthenticated requests return 401
     */
    protected function assertRequiresAuthentication(string $method, string $uri, array $data = []): void
    {
        $response = $this->json($method, $uri, $data);
        $response->assertUnauthorized();
    }
} 