<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success($message, $data = null, $code = 200, $extra = [])
    {
        return response()->json(array_merge([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $extra), $code);
    }

    protected function error($message, $code = 500, $errors = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}