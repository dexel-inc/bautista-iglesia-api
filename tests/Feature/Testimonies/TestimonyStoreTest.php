<?php

namespace Tests\Feature\Testimonies;

use App\Constants\Response;
use App\Constants\Status;
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
            'image' => 'https://example.com/blog-image.jpg',
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertOk()
            ->assertJson([
                'body' => [
                    'status' => Status::OK,
                    'reason' => Response::HTTP_CREATED,
                    'message' => 'The testimony was created correctly',
                ],
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
            'image' => 'https://example.com/blog-image.jpg',
            'rating' => 4
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertOk();

        $this->assertDatabaseHas('testimonies', [
            'name' => 'María García',
            'content' => 'Otro testimonio maravilloso.',
            'rating' => 4,
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
}
