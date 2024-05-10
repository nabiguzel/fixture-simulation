<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponserTrait
{
    protected function responseSuccess($data, int $httpResponseCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success'    => true,
            'message'    => null,
            'data'       => $data,
            'errors'     => null,
        ], $httpResponseCode);
    }

    protected function responseError(string $message, ?array $errors = [], int $httpResponseCode = Response::HTTP_BAD_REQUEST): JsonResponse {
        return response()->json([
            'success'    => false,
            'message'    => $message ?? null,
            'data'       => null,
            'errors'     => $errors ?? null,
        ], $httpResponseCode);
    }
}
