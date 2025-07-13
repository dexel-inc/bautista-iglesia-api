<?php

namespace App\Responses;

use App\Constants\Response;
use App\Constants\Status;
use Illuminate\Http\JsonResponse;

abstract class ApiResponse
{
    public static function quick(string $status, string $message, array $data = [], int $reason = Response::HTTP_OK): JsonResponse
    {
        return response()->json(
            [
                'body' => [
                    'status' => $status,
                    'reason' => $reason,
                    'message' => $message,
                    'date' => now()->toIso8601String(),
                ],
            ] + $data,
        );
    }

    public static function quickOk(string $message, array $data = [], int $reason = Response::HTTP_OK): JsonResponse
    {
        return self::quick(Status::OK, $message, $data, $reason);
    }

    public static function quickError(string $message, array $data = [], int $reason = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return self::quick(Status::ERROR, $message, $data, $reason);
    }

    public static function quickCreated(string $message, array $data = []): JsonResponse
    {
        return self::quick(Status::OK, $message, $data, Response::HTTP_CREATED);
    }
}
