<?php

namespace App\Http\Controllers;

use App\Actions\Missionaries\SavePrayLetterMissionaryAction;
use App\Http\Requests\Missionaries\PrayLettersRequest;
use App\Models\Missionary;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class PrayLetterController extends Controller
{
    public function send(
        PrayLettersRequest $request,
        Missionary $missionary,
        SavePrayLetterMissionaryAction $savePrayLetterMissionaryAction
    ): JsonResponse
    {
        $savePrayLetterMissionaryAction->execute($request->validated(), $missionary);
        return ApiResponse::successOnly();
    }
}
