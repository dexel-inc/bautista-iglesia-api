<?php

namespace Tests\Feature\Testimonies;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Testimony;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestimonyUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_testimony_successfully(): void
    {
        Storage::fake('local');

        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
            'image' => 'testimony/images/blog-image.jpg',
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido actualizado',
            'rating' => 5,
            'image' => UploadedFile::fake()->image('blog-image.jpg'),
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'id' => $testimony->id,
                ],
            ]);

        $this->assertDatabaseHas('testimonies', [
            'id' => $testimony->id,
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido actualizado',
            'rating' => 5,
        ]);
    }

    public function test_it_updates_testimony_partially(): void
    {
        Storage::fake('local');

        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
            'image' => 'testimony/images/blog-image.jpg',
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
            'image' => UploadedFile::fake()->image('blog-image.jpg'),
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'id' => $testimony->id,
                ],
            ]);

        $this->assertDatabaseHas('testimonies', [
            'id' => $testimony->id,
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ]);
    }

    public function test_it_validates_string_fields(): void
    {
        Storage::fake('local');

        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => 123,
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        Storage::fake('local');

        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => str_repeat('a', 256),
            'content' => str_repeat('b', 1001),
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'content']);
    }

    public function test_it_validates_rating_range(): void
    {
        Storage::fake('local');

        $testimony = Testimony::factory()->create();

        $updateData = [
            'rating' => 6,
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_it_validates_rating_minimum(): void
    {
        Storage::fake('local');

        $testimony = Testimony::factory()->create();

        $updateData = [
            'rating' => 0,
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_it_validates_image_file_type(): void
    {
        Storage::fake('local');

        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => 'Juan Pérez',
            'content' => 'Contenido válido',
            'rating' => 5,
            'image' => UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }
}
