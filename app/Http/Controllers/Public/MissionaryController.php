<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\MissionaryResource;
use App\Http\Resources\Public\TestimonyResource;
use App\Models\Missionary;
use App\Models\Testimony;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class MissionaryController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::successWithData(MissionaryResource::collection(Missionary::whereNull('disable_at')->get()));
    }
}
