<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Admin;
use App\Service\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me(PermissionService $service)
    {
        $menu = $service->getPermissionMenu(auth('api')->id());
        $user = auth('api')->user();
        $user->menu = $menu;

        return response()->json([
            'code'=>200,
            'message'=>'success',
            'data'=>$user
        ]);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'code' => 200,
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    //更新用户信息
    public function update($id,UserUpdateRequest $request)
    {

    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'code'=>200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
