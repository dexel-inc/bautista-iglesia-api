<?php

namespace App\Http\Controllers;

use App\Http\Requests\Missionaries\PrayLettersRequest;
use App\Helpers\FilesHelper;
use App\Jobs\SendPrayLetterJob;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class PrayLetterController extends Controller
{
    public function send(PrayLettersRequest $request): JsonResponse
    {
        $data = $request->validated();
        $filePath = FilesHelper::save('pray-letters', $data['file']);

        SendPrayLetterJob::dispatch($data['subject'], $data['description'], $filePath);

        return ApiResponse::successOnly();
    }
} 