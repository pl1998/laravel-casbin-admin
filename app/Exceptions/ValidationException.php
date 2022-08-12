<?php

namespace App\Exceptions;

class ValidationException extends \Illuminate\Foundation\Exceptions\Handler
{
    /**
     * 错误响应.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, \Illuminate\Validation\ValidationException $exception)
    {
        return response()->json([
            'message' => $exception->getMessage(),
            'errors' => $exception->errors(),
        ], $exception->status);
    }
}
