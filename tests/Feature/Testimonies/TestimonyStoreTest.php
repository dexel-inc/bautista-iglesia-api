<?php

namespace Tests\Feature\Testimonies;

use App\Constants\Response;
use App\Constants\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestimonyStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_testimony_successfully(): void
    {
        Storage::fake('local');

        $testimonyData = [
            'name' => 'Juan Pérez',
            'content' => 'Este es un testimonio increíble sobre mi experiencia.',
            'rating' => 5,
            'image' => UploadedFile::fake()->image('blog-image.jpg'),
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    'id',
                    'name',
                    'content',
                    'image',
                    'rating',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'name' => 'Juan Pérez',
                    'content' => 'Este es un testimonio increíble sobre mi experiencia.',
                    'rating' => 5,
                ]
            ]);

        $this->assertDatabaseHas('testimonies', [
            'name' => 'Juan Pérez',
            'content' => 'Este es un testimonio increíble sobre mi experiencia.',
            'rating' => 5,
        ]);
    }

    public function test_it_creates_testimony_with_default_rating(): void
    {
        Storage::fake('local');

        $testimonyData = [
            'name' => 'María García',
            'content' => 'Otro testimonio maravilloso.',
            'image' => UploadedFile::fake()->image('blog-image.jpg'),
            'rating' => 4
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertStatus(Response::HTTP_CREATED);

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
            ->assertJsonValidationErrors(['name', 'content', 'image']);
    }

    public function test_it_validates_string_fields(): void
    {
        Storage::fake('local');

        $testimonyData = [
            'name' => 123,
            'content' => 'Contenido válido',
            'rating' => 5,
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        Storage::fake('local');

        $testimonyData = [
            'name' => str_repeat('a', 256),
            'content' => str_repeat('b', 1001),
            'rating' => 5,
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'content']);
    }

    public function test_it_validates_rating_range(): void
    {
        Storage::fake('local');

        $testimonyData = [
            'name' => 'Juan Pérez',
            'content' => 'Contenido válido',
            'rating' => 6,
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_it_validates_rating_minimum(): void
    {
        Storage::fake('local');

        $testimonyData = [
            'name' => 'Juan Pérez',
            'content' => 'Contenido válido',
            'rating' => 0,
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_it_validates_image_file_type(): void
    {
        Storage::fake('local');

        $testimonyData = [
            'name' => 'Juan Pérez',
            'content' => 'Contenido válido',
            'rating' => 5,
            'image' => UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->postJson(route('testimonies.store'), $testimonyData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }
}
