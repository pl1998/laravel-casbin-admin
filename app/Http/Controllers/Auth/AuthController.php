<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Admin;
use App\Service\PermissionService;
use App\Service\RoleService;
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

    /**
     * 获取用户信息
     * @param PermissionService $permissionService
     * @param RoleService $roleService
     * @return JsonResponse
     */
    public function me(PermissionService $permissionService,RoleService $roleService)
    {
        $menu = $roleService->getRoles(auth('api')->id());
        $permissions_menu_array = [];
        $permissions_array = [];

        foreach ($menu as $value){
            list($permissionsMenu, $permissions) = $permissionService->getPermissionMenu($value->id);
            $permissions_menu_array[] = $permissionsMenu;
            $permissions_array[] = $permissions;
        }

        //将这个数组合并
        $permissions_menu_array = array_reduce($permissions_menu_array,'array_merge',[]);


        $user = auth('api')->user();
        $user->menu = $permissions_menu_array;

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
