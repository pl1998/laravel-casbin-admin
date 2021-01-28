<?php


namespace App\Http\Controllers\Auth;


trait ResponseApi
{
    /**
     * @param array|null $data
     * @param string $message
     * @param $httpCode
     * @return \Illuminate\Http\JsonResponse
     */

    public function success(array $data = null, string $message='success', $msgCode=200,int $httpCode=200)
    {
        return response()->json([
            'code' => $msgCode,
            'message' => $message,
            'data' => $data
        ], $httpCode);
    }

}
