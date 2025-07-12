<?php

namespace App\Http\Controllers;

use App\Actions\StoreMissionaryAction;
use App\Actions\UpdateMissionaryAction;
use App\Http\Requests\Missionaries\StoreMissionaryRequest;
use App\Http\Requests\Missionaries\UpdateMissionaryRequest;
use App\Models\Missionary;
use Illuminate\Http\JsonResponse;

class MissionaryController extends Controller
{
    public function index(): array
    {
        return Missionary::all()->toArray();
    }

    public function store(StoreMissionaryRequest $request, StoreMissionaryAction $action): JsonResponse
    {
        $action->execute($request->validated());

        return response()->json([
            'missionary' => $request->validated(),
            'message' => 'The missionary was created correctly',
        ], 201);
    }

    public function update(UpdateMissionaryRequest $request, Missionary $missionary, UpdateMissionaryAction $action): JsonResponse
    {
        $action->execute($request->validated(), $missionary);

        return response()->json([
            'missionary' => $request->validated(),
            'message' => 'The missionary was updated correctly',
        ]);
    }

    public function show(Missionary $missionary): Missionary
    {
        return $missionary;
    }

    public function destroy(Missionary $missionary): JsonResponse
    {
        $missionary->delete();

        return response()->json([
            'missionary deleted' => $missionary,
            'message' => 'The missionary was deleted correctly',
        ]);
    }
} 