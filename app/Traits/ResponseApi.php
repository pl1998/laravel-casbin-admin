<?php

namespace App\Traits;

use App\Enum\MessageCode;
use Illuminate\Http\JsonResponse;

trait ResponseApi
{
    /**
     * @param null|array $data
     * @param $httpCode
     * @param mixed $msgCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], string $message = 'success', $msgCode = MessageCode::HTTP_OK, int $httpCode = MessageCode::HTTP_OK)
    {
        return response()->json([
            'code' => $msgCode,
            'message' => $message,
            'data' => $data,
        ], $httpCode);
    }

    public function fail($message = 'error', $msgCode = MessageCode::HTTP_ERROR, $data = [], $httpCode = MessageCode::HTTP_OK)
    {
        return response()->json([
            'code' => $msgCode,
            'message' => $message,
            'data' => $data,
        ], $httpCode);
    }

    /**
     * @param $token
     *
     * @return JsonResponse
     */
    public function respondWithToken($token)
    {
        return response()->json([
            'code' => MessageCode::HTTP_OK,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
