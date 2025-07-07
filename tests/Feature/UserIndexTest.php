<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_users_successfully()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertOk()
            ->assertJsonCount(3);
    }

    public function test_it_returns_empty_array_when_no_users_exist()
    {
        $response = $this->getJson(route('users.index'));

        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }

    public function test_it_returns_users_with_correct_structure()
    {
        User::factory()->create();

        $response = $this->getJson(route('users.index'));

        $response->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'surname',
                    'email',
                    'phone',
                    'email_verified_at',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_it_returns_404_for_invalid_route()
    {
        $response = $this->getJson('/api/invalid-route');

        $response->assertNotFound();
    }

    public function test_it_handles_large_number_of_users()
    {
        User::factory()->count(100)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertOk();
        $this->assertLessThanOrEqual(100, count($response->json()));
    }

    public function test_it_excludes_password_field_from_response()
    {
        User::factory()->create();

        $response = $this->getJson(route('users.index'));

        $response->assertOk();
        $user = $response->json()[0];
        $this->assertArrayNotHasKey('password', $user);
    }

    public function test_it_excludes_remember_token_field_from_response()
    {
        User::factory()->create();

        $response = $this->getJson(route('users.index'));

        $response->assertOk();
        $user = $response->json()[0];
        $this->assertArrayNotHasKey('remember_token', $user);
    }

    public function test_it_handles_case_insensitive_search()
    {
        User::factory()->create(['name' => 'Juan']);

        $response = $this->getJson(route('users.index', ['search' => 'juan']));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => 'Juan']);
    }

    public function test_it_returns_correct_http_status_codes()
    {
        $response = $this->getJson(route('users.index'));
        $response->assertStatus(200);

        $response = $this->getJson('/api/nonexistent');
        $response->assertStatus(404);
    }
}
