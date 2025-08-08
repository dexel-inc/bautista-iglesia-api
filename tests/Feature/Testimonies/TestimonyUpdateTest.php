<?php

namespace Tests\Feature\Testimonies;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Testimony;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTestCase;

class TestimonyUpdateTest extends BaseTestCase
{
    public function test_it_updates_testimony_successfully(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido actualizado',
            'rating' => 5,
        ];

        $response = $this->postJson(route('testimonies.update', $testimony), $updateData);

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
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ];

        $response = $this->postJson(route('testimonies.update', $testimony), $updateData);

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
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => 123,
        ];

        $response = $this->postJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => str_repeat('a', 256),
            'content' => str_repeat('b', 1001),
        ];

        $response = $this->postJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'content']);
    }

    public function test_it_validates_rating_range(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $testimony = Testimony::factory()->create();

        $updateData = [
            'rating' => 6,
        ];

        $response = $this->postJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_it_validates_rating_minimum(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $testimony = Testimony::factory()->create();

        $updateData = [
            'rating' => 0,
        ];

        $response = $this->postJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }
}
