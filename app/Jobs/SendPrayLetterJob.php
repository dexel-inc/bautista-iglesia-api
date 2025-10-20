<?php

namespace App\Jobs;

use App\Models\Missionary;
use App\Helpers\FilesHelper;
use App\Mail\PrayLetterMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPrayLetterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(
        private readonly string $subject,
        private readonly ?string $description = '',
        private readonly ?string $filePath
    ) {}

    public function handle(): void
    {
        try {
            $missionaries = Missionary::all();
            if ($missionaries->isEmpty()) {
                Log::warning('No missionaries found to send pray letter');
                return;
            }

            $emails = $missionaries->pluck('contact_email')->filter()->toArray();
            if (empty($emails)) {
                Log::warning('No valid emails found for missionaries');
                return;
            }

            Mail::to(config('mail.from.address'))
            ->bcc($emails)
            ->send(new PrayLetterMail($this->description, $this->filePath, $this->subject));
            Log::info('Pray letter sent successfully to ' . count($emails) . ' missionaries in a single email');

        } catch (\Exception $e) {
            Log::error('Failed to send pray letter: ' . $e->getMessage());
            throw $e;
        } finally {
            FilesHelper::delete($this->filePath);
        }
    }
}
