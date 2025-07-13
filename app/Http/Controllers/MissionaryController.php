<?php

namespace App\Http\Controllers;

use App\Actions\StoreOrUpdateMissionaryAction;
use App\Http\Requests\Missionaries\MissionaryRequest;
use App\Http\Resources\Api\MissionaryResource;
use App\Models\Missionary;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MissionaryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return MissionaryResource::collection(Missionary::all());
    }

    public function store(MissionaryRequest $request, StoreOrUpdateMissionaryAction $action): JsonResponse
    {
        $action->execute(new Missionary(), $request->validated());
        return ApiResponse::quickCreated('The missionary was created correctly');
    }

    public function update(MissionaryRequest $request, Missionary $missionary, StoreOrUpdateMissionaryAction $action): JsonResponse
    {
        $action->execute($missionary, $request->validated());

        return ApiResponse::quickOk('The missionary was updated correctly');
    }

    public function show(Missionary $missionary): MissionaryResource
    {
        return MissionaryResource::make($missionary);
    }

    public function destroy(Missionary $missionary): JsonResponse
    {
        $missionary->delete();

        return ApiResponse::quickOk('The missionary was deleted correctly');
    }
}
