<?php

namespace App\Responses;

use App\Constants\Response;
use App\Constants\Status;
use Illuminate\Http\JsonResponse;

abstract class ApiResponse
{
    public static function success(array $data = []): JsonResponse
    {
        return response()->json([
            'status' => [
                'status' => Status::OK,
            ],
            'data' => $data,
        ]);
    }

    public static function successWithData($data): JsonResponse
    {
        return response()->json([
            'status' => [
                'status' => Status::OK,
            ],
            'data' => $data,
        ]);
    }

    public static function successOnly(): JsonResponse
    {
        return response()->json([
            'status' => [
                'status' => Status::OK,
            ],
        ]);
    }

    public static function updated(int $id): JsonResponse
    {
        return response()->json([
            'status' => [
                'status' => Status::OK,
            ],
            'data' => [
                'id' => $id,
            ],
        ]);
    }

    public static function created($data): JsonResponse
    {
        return response()->json([
            'status' => [
                'status' => Status::OK,
            ],
            'data' => $data,
        ], Response::HTTP_CREATED);
    }
}
