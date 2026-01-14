<?php

namespace Tests\Feature\Contents;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_content_successfully(): void
    {
        Storage::fake('local');

        $content = Content::factory()->create([
            'type' => 'blog',
            'title' => 'Título original',
            'description' => 'Descripción original',
            'image' => 'content/images/original.jpg',
        ]);

        $updateData = [
            'type' => 'article',
            'title' => 'Título actualizado',
            'description' => 'Descripción actualizada',
            'image' => UploadedFile::fake()->image('updated.jpg'),
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'id' => $content->id,
                ],
            ]);

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'type' => 'article',
            'title' => 'Título actualizado',
            'description' => 'Descripción actualizada',
        ]);
    }

    public function test_it_updates_content_partially(): void
    {
        Storage::fake('local');

        $content = Content::factory()->create([
            'type' => 'blog',
            'title' => 'Título original',
            'description' => 'Descripción original',
            'image' => 'content/images/original.jpg',
        ]);

        $updateData = [
            'title' => 'Título parcialmente actualizado',
            'description' => 'Descripción original',
            'image' => UploadedFile::fake()->image('original.jpg'),
            'type' => 'blog',
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'id' => $content->id,
                ],
            ]);

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'title' => 'Título parcialmente actualizado',
            'type' => 'blog',
            'description' => 'Descripción original',
        ]);
    }

    public function test_it_validates_string_fields(): void
    {
        Storage::fake('local');

        $content = Content::factory()->create();

        $updateData = [
            'type' => 123,
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        Storage::fake('local');

        $content = Content::factory()->create();

        $updateData = [
            'type' => str_repeat('a', 256),
            'title' => str_repeat('b', 256),
            'description' => str_repeat('c', 2001),
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'title', 'description']);
    }

    public function test_it_validates_image_file_type(): void
    {
        Storage::fake('local');

        $content = Content::factory()->create();

        $updateData = [
            'type' => 'blog',
            'title' => 'Título válido',
            'description' => 'Descripción válida',
            'image' => UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }
}
