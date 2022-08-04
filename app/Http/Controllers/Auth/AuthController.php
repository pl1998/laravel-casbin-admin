<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'DingLogin']]);
    }

    public function login(LoginRequest $request)
    {
        if (!captcha_api_check(\request('captcha'), $request->input('key'))) {
            return $this->fail('验证码错误', 40001);
        }
        $credentials = $request->all(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return $this->fail('账号或密码错误');
        }

        return $this->respondWithToken($token);
    }

    /**
     * 获取用户信息
     * @param PermissionService $permissionService
     * @param RoleService $roleService
     * @return JsonResponse
     */
    public function me(PermissionService $permissionService, RoleService $roleService)
    {
        $menu = $roleService->getRoles(auth('api')->id());
        $permissions_menu = [];
        $nodes = [];

        foreach ($menu as $value) {

            list($permissionsMenu, $permissions) = $permissionService->getPermissionMenu($value->id);
            $permissions_menu[] = $permissionsMenu;
            list($node_id, $node) = $permissionService->getPermissions($value->id);
            $nodes = array_merge($nodes, $node);

        }

        $user = auth('api')->user();
        $user->node = $nodes;
        $user->menu = array_reduce($permissions_menu, 'array_merge', []);

        return $this->success($user);

    }

    public function logout()
    {
        auth('api')->logout();
        return $this->success([], 'Successfully logged out');
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * @param UserUpdateRequest $request
     * @return JsonResponse
     * 更新用户信息
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['min:2', 'max:20'],
            'password' => ['min:6', 'max:20', 'confirmed'],
            'old_password' => ['min:6', 'max:20'],
            'password_confirmation' => ['min:6', 'max:20', 'same:password'],
        ], [
            'name.confirmed' => '昵称应该在2-20个字符之间',
            'name.password' => '新密码应该在6-20个字符之间',
            'password.confirmed' => '确认密码不一致'
        ]);

        if (!empty($request->old_password) && !empty($request->password)) {
            $credentials = ['email' => auth('api')->user()->email, 'password' => $request->old_password];
            if (!$token = auth('api')->attempt($credentials)) {
                return $this->fail('旧密码错误');
            }
            $update['password'] = Hash::make($request->password);
        }

        $update['name'] = $request->name;
        $update['avatar'] = $request->avatar;

        if ((int)auth('api')->user()->email != 'admin@gmail.com') {
            User::query()->where('id', auth('api')->user()->id)
                ->update($update);
        }

        return $this->success([
            'name' => $request->name,
            'avatar' => $request->avatar
        ]);
    }


}
