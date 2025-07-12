<?php

namespace Tests\Feature;

use App\Models\Testimony;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonyStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_testimony_successfully(): void
    {
        $testimonyData = [
            'name' => 'Juan Pérez',
            'content' => 'Este es un testimonio increíble sobre mi experiencia.',
            'rating' => 5,
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertCreated()
            ->assertJson([
                'testimony' => [
                    'name' => 'Juan Pérez',
                    'content' => 'Este es un testimonio increíble sobre mi experiencia.',
                    'rating' => 5,
                ],
                'message' => 'The testimony was created correctly',
            ]);

        $this->assertDatabaseHas('testimonies', [
            'name' => 'Juan Pérez',
            'content' => 'Este es un testimonio increíble sobre mi experiencia.',
            'rating' => 5,
        ]);
    }

    public function test_it_creates_testimony_with_default_rating(): void
    {
        $testimonyData = [
            'name' => 'María García',
            'content' => 'Otro testimonio maravilloso.',
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertCreated();

        $this->assertDatabaseHas('testimonies', [
            'name' => 'María García',
            'content' => 'Otro testimonio maravilloso.',
            'rating' => 5, // valor por defecto
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson(route('testimonies.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'content']);
    }

    public function test_it_validates_string_fields(): void
    {
        $testimonyData = [
            'name' => 123,
            'content' => 'Contenido válido',
            'rating' => 5,
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $testimonyData = [
            'name' => str_repeat('a', 256),
            'content' => str_repeat('b', 1001),
            'rating' => 5,
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'content']);
    }

    public function test_it_validates_rating_range(): void
    {
        $testimonyData = [
            'name' => 'Juan Pérez',
            'content' => 'Contenido válido',
            'rating' => 6,
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_it_validates_rating_minimum(): void
    {
        $testimonyData = [
            'name' => 'Juan Pérez',
            'content' => 'Contenido válido',
            'rating' => 0,
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $testimonyData = [
            'name' => 'Juan Pérez',
            'content' => 'Este es un testimonio increíble sobre mi experiencia.',
            'rating' => 5,
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertCreated()
            ->assertJsonStructure([
                'testimony' => [
                    'name',
                    'content',
                    'rating',
                ],
                'message'
            ]);
    }

    public function test_it_returns_404_for_invalid_route(): void
    {
        $response = $this->postJson('/api/invalid-route');

        $response->assertNotFound();
    }

    public function test_it_handles_special_characters_in_names(): void
    {
        $testimonyData = [
            'name' => 'María José García-López',
            'content' => 'Testimonio con caracteres especiales.',
            'rating' => 4,
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertCreated()
            ->assertJson([
                'testimony' => [
                    'name' => 'María José García-López',
                    'content' => 'Testimonio con caracteres especiales.',
                    'rating' => 4,
                ],
            ]);
    }
} 