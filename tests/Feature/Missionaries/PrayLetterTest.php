<?php

namespace Tests\Feature\Missionaries;

use App\Constants\Response;
use App\Constants\Status;
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
    public function test_it_sends_pray_letter_successfully(): void
    {
        $this->actingAsUser();
        Storage::fake('local');
        Queue::fake();
        Mail::fake();

        $prayLetterData = [
            'subject' => 'Carta de Oración - Enero 2024',
            'description' => 'Esta es una carta de oración importante para todos los misioneros.',
            'file' => UploadedFile::fake()->create('pray-letter.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->postJson(route('pray-letters.send'), $prayLetterData);

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

        Queue::assertPushed(SendPrayLetterJob::class);
    }

    public function test_it_validates_required_fields(): void
    {
        $this->actingAsUser();
        $response = $this->postJson(route('pray-letters.send'));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['subject', 'file']);
    }

    public function test_it_validates_string_fields(): void
    {
        $this->actingAsUser();
        Storage::fake('local');

        $prayLetterData = [
            'subject' => 123,
            'description' => 'Descripción válida',
            'file' => UploadedFile::fake()->create('pray-letter.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->postJson(route('pray-letters.send'), $prayLetterData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['subject']);
    }

    public function test_it_requires_authentication(): void
    {
        $this->assertRequiresAuthentication('POST', route('pray-letters.send'), [
            'subject' => 'Test',
            'description' => 'Test description',
            'file' => UploadedFile::fake()->create('test.pdf', 1024, 'application/pdf'),
        ]);
    }

    public function test_job_sends_email_to_all_active_missionaries(): void
    {
        Storage::fake('local');
        Mail::fake();

        Missionary::factory()->create([
            'contact_email' => 'missionary1@example.com',
            'disable_at' => null,
        ]);

        Missionary::factory()->create([
            'contact_email' => 'missionary2@example.com',
            'disable_at' => null,
        ]);

        Missionary::factory()->create([
            'contact_email' => 'disabled@example.com',
            'disable_at' => now(),
        ]);

        $filePath = 'pray-letters/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $job = new SendPrayLetterJob('Test Subject', 'Test Description', $filePath);
        $job->handle();

        Mail::assertSent(PrayLetterMail::class);
        $this->assertFalse(Storage::disk('local')->exists($filePath));
    }

    public function test_job_handles_no_missionaries(): void
    {
        Storage::fake('local');
        Mail::fake();

        $filePath = 'pray-letters/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $job = new SendPrayLetterJob('Test Subject', 'Test Description', $filePath);
        $job->handle();

        Mail::assertNothingSent();
        $this->assertFalse(Storage::disk('local')->exists($filePath));
    }

    public function test_job_handles_missionaries_without_email(): void
    {
        Storage::fake('local');
        Mail::fake();

        Missionary::factory()->create([
            'contact_email' => null,
            'disable_at' => null,
        ]);

        $filePath = 'pray-letters/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $job = new SendPrayLetterJob('Test Subject', 'Test Description', $filePath);
        $job->handle();

        Mail::assertNothingSent();
        $this->assertFalse(Storage::disk('local')->exists($filePath));
    }
//    public function test_sendsPrayLetterToMissionaries(): void
//    {
//        Mail::fake();
//        Log::spy();
//
//        Missionary::factory()->count(3)->create(['contact_email' => 'test@example.com']);
//        config()->set('mail.from.address', 'sender@example.com');
//
//        $job = new SendPrayLetterJob('Subject', 'Description', '/path/to/file');
//        $job->handle();
//
//        Mail::assertSent(PrayLetterMail::class, function ($mail) {
//            return $mail->hasTo('sender@example.com') &&
//                $mail->bcc === ['test@example.com', 'test@example.com', 'test@example.com'];
//        });
//
//        Log::shouldHaveReceived('info')->with('Pray letter sent successfully to 3 missionaries in a single email');
//    }
//
//    public function test_doesNotSendPrayLetterWhenNoMissionaries()
//    {
//        Mail::fake();
//        Log::spy();
//
//        $job = new SendPrayLetterJob('Subject', 'Description', '/path/to/file');
//        $job->handle();
//
//        Mail::assertNothingSent();
//        Log::shouldHaveReceived('warning')->with('No missionaries found to send pray letter');
//    }
//
//    public function test_doesNotSendPrayLetterWhenNoValidEmails()
//    {
//        Mail::fake();
//        Log::spy();
//
//        Missionary::factory()->count(3)->create(['contact_email' => null]);
//
//        $job = new SendPrayLetterJob('Subject', 'Description', '/path/to/file');
//        $job->handle();
//
//        Mail::assertNothingSent();
//        Log::shouldHaveReceived('warning')->with('No valid emails found for missionaries');
//    }
//
//    public function test_deletesFileAfterSending()
//    {
//        Mail::fake();
//        $filePath = '/path/to/file';
//        FilesHelper::shouldReceive('delete')->once()->with($filePath);
//
//        Missionary::factory()->count(1)->create(['contact_email' => 'test@example.com']);
//        Config::set('mail.from.address', 'sender@example.com');
//
//        $job = new SendPrayLetterJob('Subject', 'Description', $filePath);
//        $job->handle();
//    }
//
//    public function test_logsErrorWhenMailFails()
//    {
//        Mail::fake();
//        Log::spy();
//
//        Missionary::factory()->count(1)->create(['contact_email' => 'test@example.com']);
//        Config::set('mail.from.address', 'sender@example.com');
//
//        Mail::shouldReceive('to->bcc->send')->andThrow(new \Exception('Mail error'));
//
//        $this->expectException(\Exception::class);
//
//        $job = new SendPrayLetterJob('Subject', 'Description', '/path/to/file');
//        $job->handle();
//
//        Log::shouldHaveReceived('error')->with('Failed to send pray letter: Mail error');
//    }
}
