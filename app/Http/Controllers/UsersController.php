<?php


namespace App\Http\Controllers;


use App\Http\Requests\UserStoreRequest;
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
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 20);

        $query = User::query();

        if ($name = \request('name')) {
            $query->where('name', 'like', "%$name%");
        }
        $total = $query->count();

        $list = $query->forPage($page, $pageSize)->get();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data'=>[
                'list' => $list,
                'total' => $total
            ]
        ], 200);
    }

    /**
     * 新增用户
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        $params = $request->all();
        $params['password'] = Hash::make($params['password']);
        $result = User::query()->create($params);
        abort_if(!$result,500,'添加用户错误');
        return response()->json([
            'code'=>200,
            'message'=>'success'
        ],200);
    }

    public function update($id){

    }
}
