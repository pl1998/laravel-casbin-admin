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
use function PHPUnit\Framework\isEmpty;

class RolesController extends Controller
{

    /**
     * 获取角色列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request,PermissionService $service)
    {
        $page     = $request->get('page',1);
        $pageSize = $request->get('pageSize',10);

        $query = Roles::query();

        if($keyword = \request('keyword')){
            $query = $query->where('name','like',"%$keyword%");
        }
        $total = $query->count();

        $list = $query->forPage($page,$pageSize)->get();

        foreach ($list as &$value){

            list($node_id,$nodes) = $service->getPermissions($value->id);

            $value->node = $node_id;
            $value->nodes = $nodes;
        }

        return $this->success([
            'list'=>$list,
            'total'=>$total
        ]);
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
        $created_at  =  now()->toDate();
        $updated_at  =  now()->toDate();

        if(Roles::query()->where(compact('name','status'))->exists()) {
            return $this->fail('角色已存在');
        }

        $id        = Roles::query()->insertGetId(compact('name','status','description','created_at','updated_at'));

        abort_if(!$id,500,'添加角色错误');

        !empty($node) &&  $service->setPermissions($node,$id);

        return $this->success([],'角色添加成功');
    }

    public function update($id,Request $request,PermissionService $service)
    {
        $name        = $request->get('name');
        $status      = $request->get('status');
        $description = $request->get('description');
        $node        = $request->get('node',[]);

        $updated_at  =  now()->toDate();

        if(Roles::query()->where(compact('id'))->doesntExist()) {
            return $this->fail('角色不存在');
        }

        Roles::query()->where(compact('id'))->update(compact('name','description','updated_at','status'));



        if(!empty($node)) {
            $service->setPermissions($node,$id);
        }


        return $this->success([],'更新成功');

    }

    /**
     * 删除角色
     * @param $id
     * @param PermissionService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id,PermissionService $service)
    {
        Roles::query()->where('id',$id)->delete();
        $service->delPermissions($id);
        return $this->success();
    }

    /**
     * 获取所有的角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allRule()
    {
        $list = Roles::query()->where('status',1)->get(['id','name']);

        return $this->success($list);
    }
}
