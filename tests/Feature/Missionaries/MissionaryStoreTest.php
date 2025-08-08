<?php

namespace Tests\Feature\Missionaries;

use App\Constants\Response;
use App\Constants\Status;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTestCase;

class MissionaryStoreTest extends BaseTestCase

{
    public function test_it_creates_missionary_successfully(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionaryData = [
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
            'image' => UploadedFile::fake()->image('africa-mission.jpg'),
            'disable_at' => '2024-12-31 23:59:59',
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    'id',
                    'title',
                    'message',
                    'image',
                    'isEnabled',
                    'user',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'title' => 'Misión en África',
                    'message' => 'Esta es una misión increíble para llevar esperanza a África.',
                ]
            ]);

        $this->assertDatabaseHas('missionaries', [
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
        ]);
    }

    public function test_it_creates_missionary_without_disable_at(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionaryData = [
            'title' => 'Misión en Asia',
            'message' => 'Una misión permanente en Asia.',
            'image' => UploadedFile::fake()->image('asia-mission.jpg'),
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('missionaries', [
            'title' => 'Misión en Asia',
            'message' => 'Una misión permanente en Asia.',
            'disable_at' => null,
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $this->actingAsUser();
        $response = $this->postJson(route('missionaries.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'image']);
    }

    public function test_it_validates_string_fields(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionaryData = [
            'title' => 123,
            'message' => 'Mensaje válido',
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionaryData = [
            'title' => str_repeat('a', 256),
            'message' => str_repeat('b', 2001),
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'message']);
    }

    public function test_it_validates_image_file_type(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionaryData = [
            'title' => 'Misión válida',
            'message' => 'Mensaje válido',
            'image' => UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->postJson(route('missionaries.store'), $missionaryData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }
}
