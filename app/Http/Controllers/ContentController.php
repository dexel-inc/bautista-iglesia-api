<?php

namespace App\Http\Controllers;

use App\Actions\StoreOrUpdateContentAction;
use App\Http\Requests\Contents\StoreContentRequest;
use App\Http\Resources\Api\ContentResource;
use App\Models\Content;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    public function index(): JsonResponse
    {
        $contents = Content::all();
        return ApiResponse::successWithData(ContentResource::collection($contents));
    }

    public function store(StoreContentRequest $request, StoreOrUpdateContentAction $action): JsonResponse
    {
        $content = $action->execute(new Content(), $request->validated());
        return ApiResponse::created(ContentResource::make($content));
    }

    public function update(StoreContentRequest $request, Content $content, StoreOrUpdateContentAction $action): JsonResponse
    {
        $action->execute($content, $request->validated());
        return ApiResponse::updated($content->id);
    }

    public function show(Content $content): JsonResponse
    {
        return ApiResponse::successWithData(ContentResource::make($content));
    }

    public function destroy(Content $content): JsonResponse
    {
        $content->delete();
        return ApiResponse::successOnly();
    }
}
