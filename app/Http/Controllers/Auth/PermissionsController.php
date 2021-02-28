<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionStoreRequest;
use App\Models\Permissions;
use App\Service\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PermissionsController extends Controller
{
    /**
     * 获取权限列表
     * @param Request $request
     * @param PermissionService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request,PermissionService $service)
    {
        $keyword = $request->get('keyword');
        $allPermission = $service->getAllPermission($keyword);
        $list = $service->permissionTreeNode($allPermission);

        return response()->json([
            'code'=>200,
            'message'=>'success',
            'data'=>[
                'list'=>$list
            ]
        ]);
    }

    /**
     * 获取所有权限节点
     * @param Request $request
     * @param PermissionService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function allPermissions(Request $request,PermissionService $service)
    {
        $keyword = $request->get('keyword');
        $allPermission = $service->getAllPermission($keyword);
        $list = $service->permissionTreeNode($allPermission);

        return response()->json([
            'code'=>200,
            'message'=>'success',
            'data'=>[
                'list'=>$list
            ]
        ]);
    }

    /**
     * 添加权限
     * @param PermissionStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PermissionStoreRequest $request)
    {
        $hidden = $request->post('hidden',1);
        $icon   = $request->post('icon');
        $method = $request->post('method','*');
        $name   = $request->post('name');
        $p_id   = $request->post('p_id');
        $path   = $request->post('path');
        $is_menu = $request->post('is_menu');
        $url = $request->post('url');
        $title = $request->post('name');

        if($path && Permissions::query()->where(compact('path','method','p_id'))->exists()) {
            _error(403,'权限已存在');
        }
        Permissions::query()->insert(compact('hidden','icon','method','name','path','p_id','is_menu','method','url','title'));

        return $this->success();
    }

    /**
     * 更新权限
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id,Request $request)
    {
        $hidden = $request->post('hidden',1);
        $icon = $request->post('icon');
        $method = $request->post('method','*');
        $name = $request->post('name');
        $p_id = $request->post('p_id');
        $path = $request->post('path');
        $is_menu = $request->post('is_menu');
        $title = $request->post('name');
        $url = $request->post('url');

        if($path && Permissions::query()->where(compact('path','method'))->doesntExist()) {
            _error(403,'权限不存在');
        }

        Permissions::query()->where('id',$id)->update(compact('hidden','icon','method','name','path','p_id','is_menu','method','title','url'));

        return $this->success();
    }

    /**
     * 删除权限
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        Permissions::query()->where('id',$id)->delete();
        return $this->success();
    }
}
