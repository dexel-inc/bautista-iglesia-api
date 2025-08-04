<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\TestimonyResource;
use App\Models\Testimony;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class TestimonyController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::successWithData(TestimonyResource::collection(Testimony::all()));
    }
}
