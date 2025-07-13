<?php

namespace Tests\Feature\Testimonies;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Testimony;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonyUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_testimony_successfully(): void
    {
        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
            'image' => 'https://example.com/blog-image.jpg',
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido actualizado',
            'rating' => 5,
            'image' => 'https://example.com/blog-image.jpg',
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertOk()
            ->assertJson([
                'body' => [
                    'status' => Status::OK,
                    'reason' => Response::HTTP_OK,
                    'message' => 'The testimony was updated correctly',
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
        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
            'image' => 'https://example.com/blog-image.jpg',
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
            'image' => 'https://example.com/blog-image.jpg',
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertOk()
            ->assertJson([
                'body' => [
                    'status' => Status::OK,
                    'reason' => Response::HTTP_OK,
                    'message' => 'The testimony was updated correctly',
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
        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => 123,
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => str_repeat('a', 256),
            'content' => str_repeat('b', 1001),
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'content']);
    }

    public function test_it_validates_rating_range(): void
    {
        $testimony = Testimony::factory()->create();

        $updateData = [
            'rating' => 6,
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_it_validates_rating_minimum(): void
    {
        $testimony = Testimony::factory()->create();

        $updateData = [
            'rating' => 0,
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }
}
