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

    public function success($data = [], string $message='success', $msgCode=200,int $httpCode=200)
    {
        return response()->json([
            'code' => $msgCode,
            'message' => $message,
            'data' => $data
        ], $httpCode);
    }

    public function fail($message='error',$msgCode=500,$data=[],$httpCode=200)
    {
        return response()->json([
            'code' => $msgCode,
            'message' => $message,
            'data' => $data
        ], $httpCode);
    }

}
