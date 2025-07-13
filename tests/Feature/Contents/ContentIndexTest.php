<?php

namespace Tests\Feature\Contents;

use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_contents_successfully(): void
    {
        Content::factory()->count(3)->create();

        $response = $this->getJson(route('contents.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => []
            ]);
    }

    public function test_it_returns_empty_array_when_no_contents_exist(): void
    {
        $response = $this->getJson(route('contents.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'status' => [
                    'status' => 'OK'
                ],
                'data' => []
            ]);
    }

    public function test_it_returns_contents_with_correct_structure(): void
    {
        Content::factory()->create();

        $response = $this->getJson(route('contents.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'title',
                        'description',
                        'image',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }
}
