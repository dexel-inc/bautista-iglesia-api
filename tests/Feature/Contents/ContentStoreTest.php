<?php

namespace Tests\Feature\Contents;

use App\Constants\Response;
use App\Constants\Status;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTestCase;

class ContentStoreTest extends BaseTestCase
{

    public function test_it_creates_content_successfully(): void
    {
        Storage::fake('local');

        $this->actingAsUser();

        $contentData = [
            'type' => 'blog',
            'title' => 'Mi primer blog post',
            'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
            'image' => UploadedFile::fake()->image('blog-image.jpg'),
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    'id',
                    'type',
                    'title',
                    'description',
                    'image',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'type' => 'blog',
                    'title' => 'Mi primer blog post',
                    'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
                ]
            ]);

        $this->assertDatabaseHas('contents', [
            'type' => 'blog',
            'title' => 'Mi primer blog post',
            'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $this->actingAsUser();

        $response = $this->postJson(route('contents.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'title', 'description', 'image']);
    }

    public function test_it_validates_string_fields(): void
    {
        Storage::fake('local');

        $this->actingAsUser();

        $contentData = [
            'type' => 123,
            'title' => 'Título válido',
            'description' => 'Descripción válida',
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        Storage::fake('local');

        $this->actingAsUser();

        $contentData = [
            'type' => str_repeat('a', 256),
            'title' => str_repeat('b', 256),
            'description' => str_repeat('c', 2001),
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'title', 'description']);
    }

    public function test_it_validates_image_file_type(): void
    {
        Storage::fake('local');
        $this->actingAsUser();

        $contentData = [
            'type' => 'blog',
            'title' => 'Título válido',
            'description' => 'Descripción válida',
            'image' => UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }

    public function test_it_requires_authentication(): void
    {
        Storage::fake('local');

        $contentData = [
            'type' => 'blog',
            'title' => 'Test Content',
            'description' => 'Test Description',
            'image' => UploadedFile::fake()->image('test.jpg'),
        ];

        $this->assertRequiresAuthentication('POST', route('contents.store'), $contentData);
    }
}
