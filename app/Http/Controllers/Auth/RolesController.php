<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Models\Roles;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * 获取角色列表.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, PermissionService $service)
    {
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);

        $query = Roles::query();

        if ($keyword = request('keyword')) {
            $query = $query->where('name', 'like', "%{$keyword}%");
        }
        $total = $query->count();

        $list = $query->forPage($page, $pageSize)->get();

        foreach ($list as &$value) {
            $value->node = $service->getRolePermissions([$value->id]);
        }

        return $this->success([
            'list' => $list,
            'total' => $total,
        ]);
    }

    /**
     * 添加角色.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RoleStoreRequest $request, PermissionService $service)
    {
        $name = $request->get('name');
        $status = $request->get('status');
        $description = $request->get('description');
        $node = $request->get('node', []);
        $created_at = now()->toDateTimeString();
        $updated_at = now()->toDateTimeString();

        if (Roles::query()->where(compact('name', 'status'))->exists()) {
            return $this->fail('角色已存在');
        }
        $id = Roles::query()->insertGetId(compact('name', 'status', 'description', 'created_at', 'updated_at'));

        abort_if(!$id, 500, '添加角色错误');

        !empty($node) && $service->setPermissions($node, $id);

        return $this->success([], '角色添加成功');
    }

    public function update($id, Request $request, PermissionService $service)
    {
        $name = $request->get('name');
        $status = $request->get('status');
        $description = $request->get('description');
        $node = $request->get('node', []);

        $updated_at = now()->toDateTimeString();

        if (Roles::query()->where(compact('id'))->doesntExist()) {
            return $this->fail('角色不存在');
        }

        Roles::query()->where(compact('id'))->update(compact('name', 'description', 'updated_at', 'status'));

        if (!empty($node)) {
            $service->setPermissions($node, $id);
        }

        return $this->success([], '更新成功');
    }

    /**
     * 删除角色.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, PermissionService $service)
    {
        Roles::destroy($id);
        $service->delPermissions($id);

        return $this->success();
    }

    /**
     * 获取所有的角色.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allRule()
    {
        $list = Roles::query()->where('status', 1)->get(['id', 'name']);

        return $this->success($list);
    }
}
