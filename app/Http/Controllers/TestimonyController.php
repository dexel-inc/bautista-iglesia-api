<?php

namespace App\Http\Controllers;

use App\Actions\StoreTestimonyAction;
use App\Actions\UpdateTestimonyAction;
use App\Http\Requests\Testimonies\StoreTestimonyRequest;
use App\Http\Requests\Testimonies\UpdateTestimonyRequest;
use App\Models\Testimony;
use Illuminate\Http\JsonResponse;

class TestimonyController extends Controller
{
    public function index(): array
    {
        return Testimony::all()->toArray();
    }

    public function store(StoreTestimonyRequest $request, StoreTestimonyAction $action): JsonResponse
    {
        $action->execute($request->validated());

        return response()->json([
            'testimony' => $request->validated(),
            'message' => 'The testimony was created correctly',
        ], 201);
    }

    public function update(UpdateTestimonyRequest $request, Testimony $testimony, UpdateTestimonyAction $action): JsonResponse
    {
        $action->execute($request->validated(), $testimony);

        return response()->json([
            'testimony' => $request->validated(),
            'message' => 'The testimony was updated correctly',
        ]);
    }

    public function show(Testimony $testimony): Testimony
    {
        return $testimony;
    }

    public function destroy(Testimony $testimony): JsonResponse
    {
        $testimony->delete();

        return response()->json([
            'testimony deleted' => $testimony,
            'message' => 'The testimony was deleted correctly',
        ]);
    }
} 