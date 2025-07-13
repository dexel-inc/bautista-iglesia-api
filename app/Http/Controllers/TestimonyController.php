<?php

namespace App\Http\Controllers;

use App\Actions\StoreOrUpdateTestimonyAction;
use App\Http\Requests\Testimonies\TestimonyRequest;
use App\Http\Resources\Api\TestimonyResource;
use App\Models\Testimony;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class TestimonyController extends Controller
{
    public function index(): JsonResponse
    {
        $testimonies = Testimony::all();
        return ApiResponse::successWithData(TestimonyResource::collection($testimonies));
    }

    public function store(TestimonyRequest $request, StoreOrUpdateTestimonyAction $action): JsonResponse
    {
        $testimony = $action->execute(new Testimony(), $request->validated());
        return ApiResponse::created(TestimonyResource::make($testimony));
    }

    public function update(TestimonyRequest $request, Testimony $testimony, StoreOrUpdateTestimonyAction $action): JsonResponse
    {
        $action->execute($testimony, $request->validated());
        return ApiResponse::updated($testimony->id);
    }

    public function show(Testimony $testimony): JsonResponse
    {
        return ApiResponse::successWithData(TestimonyResource::make($testimony));
    }

    public function destroy(Testimony $testimony): JsonResponse
    {
        $testimony->delete();
        return ApiResponse::successOnly();
    }
} 