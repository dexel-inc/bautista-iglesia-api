<?php

namespace App\Http\Controllers;

use App\Actions\StoreOrUpdateMissionaryAction;
use App\Helpers\FilesHelper;
use App\Http\Requests\Missionaries\MissionaryRequest;
use App\Http\Requests\Missionaries\UpdateMissionaryRequest;
use App\Http\Resources\Api\MissionaryResource;
use App\Models\Missionary;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class MissionaryController extends Controller
{
    public function index(): JsonResponse
    {
        $missionaries = Missionary::orderBy('order')->get();
        return ApiResponse::successWithData(MissionaryResource::collection($missionaries));
    }

    public function store(MissionaryRequest $request, StoreOrUpdateMissionaryAction $action): JsonResponse
    {
        $missionary = $action->execute(new Missionary(), $request->validated());
        return ApiResponse::created(MissionaryResource::make($missionary));
    }

    public function update(UpdateMissionaryRequest $request, Missionary $missionary, StoreOrUpdateMissionaryAction $action): JsonResponse
    {
        $action->execute($missionary, $request->validated());
        return ApiResponse::updated(MissionaryResource::make($missionary));
    }

    public function show(Missionary $missionary): JsonResponse
    {
        return ApiResponse::successWithData(MissionaryResource::make($missionary));
    }

    public function destroy(Missionary $missionary): JsonResponse
    {
        FilesHelper::delete($missionary->image);
        $missionary->delete();
        return ApiResponse::successOnly();
    }
}
