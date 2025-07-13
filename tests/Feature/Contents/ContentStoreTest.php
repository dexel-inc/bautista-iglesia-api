<?php

namespace Tests\Feature\Contents;

use App\Constants\Response;
use App\Constants\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_content_successfully(): void
    {
        $contentData = [
            'type' => 'blog',
            'title' => 'Mi primer blog post',
            'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
            'image' => 'https://example.com/blog-image.jpg',
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
                    'image' => 'https://example.com/blog-image.jpg',
                ]
            ]);

        $this->assertDatabaseHas('contents', [
            'type' => 'blog',
            'title' => 'Mi primer blog post',
            'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
            'image' => 'https://example.com/blog-image.jpg',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson(route('contents.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'title', 'description', 'image']);
    }

    public function test_it_validates_string_fields(): void
    {
        $contentData = [
            'type' => 123,
            'title' => 'Título válido',
            'description' => 'Descripción válida',
            'image' => 'https://example.com/image.jpg',
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $contentData = [
            'type' => str_repeat('a', 256),
            'title' => str_repeat('b', 256),
            'description' => str_repeat('c', 2001),
            'image' => str_repeat('d', 256),
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'title', 'description', 'image']);
    }
}
