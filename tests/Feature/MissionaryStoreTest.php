<?php

namespace Tests\Feature;

use App\Models\Missionary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionaryStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_missionary_successfully(): void
    {
        $missionaryData = [
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
            'image' => 'https://example.com/africa-mission.jpg',
            'disable_at' => '2024-12-31 23:59:59',
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertCreated()
            ->assertJson([
                'missionary' => [
                    'title' => 'Misión en África',
                    'message' => 'Esta es una misión increíble para llevar esperanza a África.',
                    'image' => 'https://example.com/africa-mission.jpg',
                    'disable_at' => '2024-12-31 23:59:59',
                ],
                'message' => 'The missionary was created correctly',
            ]);

        $this->assertDatabaseHas('missionaries', [
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
            'image' => 'https://example.com/africa-mission.jpg',
        ]);
    }

    public function test_it_creates_missionary_without_disable_at(): void
    {
        $missionaryData = [
            'title' => 'Misión en Asia',
            'message' => 'Una misión permanente en Asia.',
            'image' => 'https://example.com/asia-mission.jpg',
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertCreated();

        $this->assertDatabaseHas('missionaries', [
            'title' => 'Misión en Asia',
            'message' => 'Una misión permanente en Asia.',
            'image' => 'https://example.com/asia-mission.jpg',
            'disable_at' => null,
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson(route('missionaries.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'message', 'image']);
    }

    public function test_it_validates_string_fields(): void
    {
        $missionaryData = [
            'title' => 123,
            'message' => 'Mensaje válido',
            'image' => 'https://example.com/image.jpg',
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $missionaryData = [
            'title' => str_repeat('a', 256),
            'message' => str_repeat('b', 2001),
            'image' => str_repeat('c', 256),
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'message', 'image']);
    }

    public function test_it_validates_disable_at_date_format(): void
    {
        $missionaryData = [
            'title' => 'Misión válida',
            'message' => 'Mensaje válido',
            'image' => 'https://example.com/image.jpg',
            'disable_at' => 'invalid-date',
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['disable_at']);
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $missionaryData = [
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
            'image' => 'https://example.com/africa-mission.jpg',
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertCreated()
            ->assertJsonStructure([
                'missionary' => [
                    'title',
                    'message',
                    'image',
                ],
                'message'
            ]);
    }

    public function test_it_returns_404_for_invalid_route(): void
    {
        $response = $this->postJson('/api/invalid-route');

        $response->assertNotFound();
    }

    public function test_it_handles_special_characters_in_fields(): void
    {
        $missionaryData = [
            'title' => 'Misión en São Paulo',
            'message' => 'Una misión especial con caracteres únicos: ñáéíóú.',
            'image' => 'https://example.com/são-paulo.jpg',
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertCreated()
            ->assertJson([
                'missionary' => [
                    'title' => 'Misión en São Paulo',
                    'message' => 'Una misión especial con caracteres únicos: ñáéíóú.',
                    'image' => 'https://example.com/são-paulo.jpg',
                ],
            ]);
    }
} 