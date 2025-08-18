<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subscriptions\NewsLetterRequest;
use App\Helpers\FilesHelper;
use App\Jobs\SendNewsLetterJob;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class NewsLetterController extends Controller
{
    public function send(NewsLetterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $filePath = null;

        if (isset($data['file'])) {
            $filePath = FilesHelper::save('newsletters', $data['file']);
        }

        SendNewsLetterJob::dispatchSync($data['subject'], $data['description'], $filePath);

        return ApiResponse::successOnly();
    }
}
