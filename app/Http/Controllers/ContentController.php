<?php

namespace App\Http\Controllers;

use App\Actions\StoreOrUpdateContentAction;
use App\Http\Requests\Contents\StoreContentRequest;
use App\Http\Resources\Api\ContentResource;
use App\Models\Content;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ContentResource::collection(Content::all());
    }

    public function store(StoreContentRequest $request, StoreOrUpdateContentAction $action): JsonResponse
    {
        $action->execute(new Content(),$request->validated());
        return ApiResponse::quickCreated('The content was created correctly');
    }

    public function update(StoreContentRequest $request, Content $content, StoreOrUpdateContentAction $action): JsonResponse
    {
        $action->execute($content, $request->validated());

        return ApiResponse::quickOk('The content was updated correctly');
    }

    public function show(Content $content): ContentResource
    {
        return ContentResource::make($content);
    }

    public function destroy(Content $content): JsonResponse
    {
        $content->delete();

        return ApiResponse::quickOk('The content was deleted correctly');
    }
}
