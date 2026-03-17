<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success($data = null, ?string $message = null, int $status = 200, array $meta = [])
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $status);
    }

    protected function error(string $message = 'Error', int $status = 400, array $errors = [])
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}