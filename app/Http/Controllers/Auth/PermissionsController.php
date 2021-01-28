<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionStoreRequest;
use App\Models\Permissions;
use App\Service\PermissionService;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    /**
     * 获取所有权限节点
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
     * 添加权限
     * @param PermissionStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PermissionStoreRequest $request)
    {
        $data = $request->all();
        $path = $data['path'];
        $method = $data['method'];
        if($path && Permissions::query()->where(compact('path','method'))->exists()) {
            _error(403,'权限已存在');
        }
        Permissions::query()->insert($data);

        return $this->success();
    }

    public function update($id,Request $request){

    }
}
