<?php

namespace Tests\Feature\Contents;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_content_successfully(): void
    {
        $content = Content::factory()->create([
            'type' => 'blog',
            'title' => 'Título original',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
        ]);

        $updateData = [
            'type' => 'article',
            'title' => 'Título actualizado',
            'description' => 'Descripción actualizada',
            'image' => 'https://example.com/updated.jpg',
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertOk()
            ->assertJson([
                'body' => [
                    'status' => Status::OK,
                    'reason' => Response::HTTP_OK,
                    'message' => 'The content was updated correctly',
                ],
            ]);

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'type' => 'article',
            'title' => 'Título actualizado',
            'description' => 'Descripción actualizada',
            'image' => 'https://example.com/updated.jpg',
        ]);
    }

    public function test_it_updates_content_partially(): void
    {
        $content = Content::factory()->create([
            'type' => 'blog',
            'title' => 'Título original',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
        ]);

        $updateData = [
            'title' => 'Título parcialmente actualizado',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
            'type' => 'blog',
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertOk()
            ->assertJson([
                'body' => [
                    'status' => Status::OK,
                    'reason' => Response::HTTP_OK,
                    'message' => 'The content was updated correctly',
                ],
            ]);

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'title' => 'Título parcialmente actualizado',
            'type' => 'blog',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
        ]);
    }

    public function test_it_validates_string_fields(): void
    {
        $content = Content::factory()->create();

        $updateData = [
            'type' => 123,
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $content = Content::factory()->create();

        $updateData = [
            'type' => str_repeat('a', 256),
            'title' => str_repeat('b', 256),
            'description' => str_repeat('c', 2001),
            'image' => str_repeat('d', 256),
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'title', 'description', 'image']);
    }
}
