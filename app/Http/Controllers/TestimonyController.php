<?php

namespace App\Http\Controllers;

use App\Actions\StoreOrUpdateTestimonyAction;
use App\Http\Requests\Testimonies\TestimonyRequest;
use App\Http\Resources\Api\TestimonyResource;
use App\Models\Testimony;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TestimonyController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return TestimonyResource::collection(Testimony::all());
    }

    public function store(TestimonyRequest $request, StoreOrUpdateTestimonyAction $action): JsonResponse
    {
        $action->execute(new Testimony(), $request->validated());
        return ApiResponse::quickCreated('The testimony was created correctly');
    }

    public function update(TestimonyRequest $request, Testimony $testimony, StoreOrUpdateTestimonyAction $action): JsonResponse
    {
        $action->execute($testimony, $request->validated());

        return ApiResponse::quickOk('The testimony was updated correctly');
    }

    public function show(Testimony $testimony): TestimonyResource
    {
        return TestimonyResource::make($testimony);
    }

    public function destroy(Testimony $testimony): JsonResponse
    {
        $testimony->delete();

        return ApiResponse::quickOk('The testimony was deleted correctly');
    }
} 