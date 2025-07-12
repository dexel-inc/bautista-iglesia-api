<?php

namespace App\Http\Controllers;

use App\Actions\StoreContentAction;
use App\Actions\UpdateContentAction;
use App\Http\Requests\Contents\StoreContentRequest;
use App\Http\Requests\Contents\UpdateContentRequest;
use App\Models\Content;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    public function index(): array
    {
        return Content::all()->toArray();
    }

    public function store(StoreContentRequest $request, StoreContentAction $action): JsonResponse
    {
        $action->execute($request->validated());

        return response()->json([
            'content' => $request->validated(),
            'message' => 'The content was created correctly',
        ], 201);
    }

    public function update(UpdateContentRequest $request, Content $content, UpdateContentAction $action): JsonResponse
    {
        $action->execute($request->validated(), $content);

        return response()->json([
            'content' => $request->validated(),
            'message' => 'The content was updated correctly',
        ]);
    }

    public function show(Content $content): Content
    {
        return $content;
    }

    public function destroy(Content $content): JsonResponse
    {
        $content->delete();

        return response()->json([
            'content deleted' => $content,
            'message' => 'The content was deleted correctly',
        ]);
    }
} 