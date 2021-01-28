<?php


namespace App\Exceptions;


class ValidationException extends \Illuminate\Foundation\Exceptions\Handler
{
    protected function invalidJson($request, \Illuminate\Validation\ValidationException $exception)
    {
        return response()->json([
            'message' => $exception->getMessage(),
            'errors' => $exception->errors(),
        ], $exception->status);
    }
}
