<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;

trait ResponseApi
{
    /**
     * @param array|null $data
     * @param string $message
     * @param $httpCode
     * @return \Illuminate\Http\JsonResponse
     */

    public function success($data = [], string $message = 'success', $msgCode = 200,int $httpCode = 200){
        return response()->json([
            'code'    => $msgCode,
            'message' => $message,
            'data'    => $data
        ], $httpCode);
    }

    public function fail($message = 'error', $msgCode = 500, $data = [], $httpCode = 200)
    {
        return response()->json([
            'code' => $msgCode,
            'message' => $message,
            'data' => $data
        ], $httpCode);

    }
    /**
     * @param $token
     * @return JsonResponse
     */
    public function respondWithToken($token)
    {
        return response()->json([
            'code'=>200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

}
