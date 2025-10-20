<?php

namespace Tests\Feature\Missionaries;

use App\Constants\Response;
use App\Constants\Status;
use App\Constants\TypesPrayletter;
use App\Jobs\SendPrayLetterJob;
use App\Mail\PrayLetterMail;
use App\Models\Missionary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTestCase;
use Illuminate\Support\Facades\Config;

class PrayLetterTest extends BaseTestCase
{
    public function test_it_can_add_pray_letter_file_successfully(): void
    {
        $this->actingAsUser();
        Storage::fake('local');

        $missionary = Missionary::factory()->create([
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
            'image' => 'https://example.com/africa-mission.jpg',
            'disable_at' => '2024-12-31 23:59:59',
        ]);

        $prayLetterData = [
            'type' => 'file',
            'file' => UploadedFile::fake()->create('pray-letter.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->postJson(route('pray-letters.send', $missionary->id), $prayLetterData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status' => [
                    'status'
                ]
            ])
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ]
            ]);

        $missionary->refresh();
        $this->assertEquals(TypesPrayletter::FILE->value, $missionary->type);
        $this->assertNotNull($missionary->url);
    }

    public function test_it_can_add_pray_letter_link_successfully(): void
    {
        $this->actingAsUser();
        Storage::fake('local');

        $missionary = Missionary::factory()->create([
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
            'image' => 'https://example.com/africa-mission.jpg',
            'disable_at' => '2024-12-31 23:59:59',
        ]);

        $prayLetterData = [
            'type' => 'link',
            'link' => 'https://youtube.com',
        ];

        $response = $this->postJson(route('pray-letters.send', $missionary->id), $prayLetterData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status' => [
                    'status'
                ]
            ])
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ]
            ]);

        $missionary->refresh();
        $this->assertEquals(TypesPrayletter::LINK->value, $missionary->type);
        $this->assertEquals('https://youtube.com', $missionary->url);
    }

    public function test_it_validates_required_fields_pray_letter(): void
    {
        $missionary = Missionary::factory()->create([
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
            'image' => 'https://example.com/africa-mission.jpg',
            'disable_at' => '2024-12-31 23:59:59',
        ]);

        $this->actingAsUser();
        $response = $this->postJson(route('pray-letters.send', $missionary->id));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);
    }

//    public function test_it_validates_string_fields(): void
//    {
//        $this->actingAsUser();
//        Storage::fake('local');
//
//        $prayLetterData = [
//            'subject' => 123,
//            'description' => 'Descripción válida',
//            'file' => UploadedFile::fake()->create('pray-letter.pdf', 1024, 'application/pdf'),
//        ];
//
//        $response = $this->postJson(route('pray-letters.send'), $prayLetterData);
//
//        $response->assertUnprocessable()
//            ->assertJsonValidationErrors(['subject']);
//    }
//
//    public function test_it_requires_authentication(): void
//    {
//        $this->assertRequiresAuthentication('POST', route('pray-letters.send'), [
//            'subject' => 'Test',
//            'description' => 'Test description',
//            'file' => UploadedFile::fake()->create('test.pdf', 1024, 'application/pdf'),
//        ]);
//    }
}
