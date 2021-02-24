<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Models\Permissions;
use App\Models\Roles;
use App\Service\PermissionService;
use App\Service\RoleService;
use Illuminate\Http\Request;
use Lauthz\Facades\Enforcer;

class RolesController extends Controller
{

    /**
     * 获取角色列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $page     = $request->get('page',1);
        $pageSize = $request->get('pageSize',20);

        $query = Roles::query();

        if($keyword = \request('keyword')){
            $query = $query->where('name','like',"%$keyword%");
        }

        $total = $query->count();

        $list = $query->forPage($page,$pageSize)->get();

        return response()->json([
            'code'=>200,
            'message'=>'success',
            'data'=>[
                'list'=>$list,
                'total'=>$total
            ]
        ],200);
    }

    /**
     * 添加角色
     * @param RoleStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RoleStoreRequest $request,PermissionService $service)
    {
        $name        = $request->get('name');
        $status      = $request->get('status');
        $description = $request->get('description');
        $node        = $request->get('node',[]);
        $id          = Roles::query()->insertGetId(compact('name','status','description'));

        abort_if(!$id,500,'添加角色错误');

        !empty($node) && $service->setPermissions($node,$id);

        return response()->json([
            'code'=>200,
            'message'=>'角色添加成功'
        ],200);

    }

    public function update($id)
    {

    }


    public function delete($id)
    {

    }

    /**
     * 获取所有的角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allRule(Request $request)
    {
        $list = Roles::query()->get(['id','name']);
        dd($list);
        return response()->json([
            'code'=>200,
            'message'=>'success',
            'data'=>$list
        ],200);
    }
}
