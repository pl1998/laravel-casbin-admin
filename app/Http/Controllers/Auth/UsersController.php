<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Service\RoleService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    /**
     * 获取用户列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request,RoleService $service)
    {
        $page = $request->get('page', 1);
        $pageSize = $request->get('limit', 20);
        $email = $request->get('email');
        $name = $request->get('name');

        $query = User::query();
        if($email) {
            $query->where('email',$email);
        }

        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        $total = $query->count();

        $list = $query->forPage($page, $pageSize)->get();

        foreach ($list as  &$value){
            $value->roles_node = $service->getRoles($value->id);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data'=>[
                'list' => $list,
                'mate'=>[
                    'total' => $total,
                    'pageSize'=>$pageSize
                ]
            ]
        ], 200);
    }

    /**
     * 新增用户
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(UserStoreRequest $request,RoleService $service)
    {
        $avatar = $request->post('avatar');
        $email  = $request->post('email');
        $name   = $request->post('name');
        $roles  = $request->post('roles');
        $password = Hash::make($request->post('password'));

        $id = User::query()->insertGetId(compact('avatar','email','name','password'));

        abort_if(!$id,500,'添加用户错误');

        $service->setRoles($roles,$id);

        return response()->json([
            'code'=>200,
            'message'=>'success'
        ],200);

    }

    /**
     * 更新用户信息
     * @param $id
     * @param UserUpdateRequest $request
     * @param RoleService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id,UserUpdateRequest $request,RoleService $service)
    {
        $email  = $request->post('email');
        $name   = $request->post('name');
        $roles  = $request->post('roles');
        $password  = $request->post('password');
        if(!$password) {
            $password = Hash::make($password);
        }
        $user = User::query()->where(compact('id'))->first();

        $user->email = $email;
        $user->name = $name;
        !empty($password) && $user->password = $password;
        $user->save();
        !empty($roles) && $service->setRoles($roles,$id);

        return response()->json([
           'code'=>200,
           'message'=>'更新成功'
        ],200);

    }
}
