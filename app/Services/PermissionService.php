<?php

namespace App\Services;

use App\Models\CasbinRules;
use App\Models\Permissions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Lauthz\Facades\Enforcer;

class PermissionService
{
    protected $allPermissions =[];

    public function __construct()
    {
        if(empty($allPermissions)) {
            $this->allPermissions = Permissions::query()->pluck('is_menu','id')->toArray();
        }
    }

    public function permissionTreeNode($permission): array
    {
        $permissions = get_tree($permission);
        foreach ($permissions as &$permission) {
            if (0 === $permission['p_id']) {
                $permission['root'] = true;
            } else {
                $permission['root'] = false;
            }
        }

        return $permissions;
    }

    public function getPermissionMenu($id): array
    {
        [$node, $permissions] = $this->getPermissions($id);
        Log::info('test',$node);
        if ('demo' === auth('api')->user()->name) {
            $query = Permissions::with('getPid')
                ->where('status', Permissions::STATUS_OK)
                ->where('is_menu', Permissions::IS_MENU_YES)
            ;
            $permissions = $query->where(function ($query) use ($permissions): void {
                foreach ($permissions as $value) {
                    $query->whereOr('id', $value[2]);
                }
            })->get(['id', 'p_id', 'path', 'name', 'title', 'icon', 'method', 'url'])->toArray();

            $permissionsMenu = get_tree($permissions);

            return [$permissionsMenu, $permissions];
        }
        if (!empty($permissions)) {
            $query = Permissions::query()
                ->where('status', Permissions::STATUS_OK)
                ->where('is_menu', Permissions::IS_MENU_YES)
            ;
            $permissionsMap = [];
            $query->whereIn('id', $node)
                ->get(['id', 'p_id', 'path', 'name', 'title', 'icon', 'method', 'url'])
                ->map(function ($val) use (&$permissionsMap): void {
                        $getPid = $val->get_pid;
                        $val->get_pid = null;
                        $permissionsMap[$val->id] = $val->toArray();
                        if ($getPid) {
                            $permissionsMap[$getPid->id] = $getPid;
                        }
                    })->toArray();

            $permissionsMenu = get_tree($permissionsMap);

            return [$permissionsMenu, $permissionsMap];
        }

        return [[], []];
    }

    /**
     * 获取节点数据.
     *
     * @param $permissions
     *
     * @return array
     */
    public function getNodeId($permissions)
    {
        $node = array_column($permissions, '0');
        $nodeId = [];
        foreach ($node as $value) {
            $nodeId[] = $this->setIdentifier($value);
        }

        return $nodeId;
    }

    /**
     * 设置用户权限.
     *
     * @param $nodeId
     * @param $id
     */
    public function setPermissions($nodeId, $id): void
    {
        $id = $this->getIdentifier($id);

        $permissions = Permissions::query()->with('getPid')
            ->whereIn('id', $nodeId)
            ->groupBy('id')
            ->get(['path', 'method', 'p_id', 'id', 'name', 'is_menu', 'url']);

        Enforcer::deletePermissionsForUser($id);

        $permissions->map(function ($value) use ($id): void {
            $path = Permissions::IS_MENU_NO === $value->is_menu ? $value->url : $value->path;
            Enforcer::addPermissionForUser($id, $path ?? '', $value['method'], $value['id']);
        });
    }

    /**
     * 根据角色id获取权限.
     *
     * @param $id
     */
    public function getPermissions($id): array
    {
        $id = $this->getIdentifier($id);
        $permissions = Enforcer::getPermissionsForUser($id);
        if (empty($permissions)) {
            return [[], []];
        }
        return [array_column($permissions,3), $permissions];
    }

    /**
     * 根据角色获取权限
     * @param  $roleIds
     * @return \Illuminate\Support\Collection
     */
    public function getRolePermissions( $roleIds)
    {
        $permissions=[];
        foreach ($roleIds as $roleId) {
            $permissions[]=$this->getIdentifier($roleId);
        }
        $nodes = CasbinRules::query()->whereIn('v0',$permissions)
            ->where('p_type','p')
            ->pluck('v3');
        return $nodes;

    }


    /**
     * 获取角色的菜单和权限
     * @param $roleId
     * @return array[]
     */
    public function getRolePermissionAndMenu( $roleId)
    {

        $nodes = $this->getRolePermissions([$roleId]);

        $permissions=[];
        $menus=[];

        foreach ($nodes as $node) {
            if(!isset($this->allPermissions[$node])) continue;
            if($this->allPermissions[$node] == Permissions::IS_MENU_NO) {
                $permissions[]=$node;
            }else{
                $menus[]=$node;
            }
        }
        return [$menus,$permissions];
    }

    // 获取所有权限
    public function getAllPermission($keyword = null)
    {
        return Permissions::query()
            ->where('status', Permissions::STATUS_OK)
            ->get(['id', 'name', 'icon', 'path', 'url', 'method', 'p_id', 'hidden', 'is_menu', 'title', 'status'])
            ->toArray()
        ;
    }

    /**
     * 删除所属角色的权限.
     *
     * @param $id
     */
    public function delPermissions($id): void
    {
        $id = $this->getIdentifier($id);
        Enforcer::deletePermissionsForUser($id);
    }

    protected function getIdentifier($id)
    {
        return 'permission_'.$id;
    }

    protected function setIdentifier($id)
    {
        return explode('_', $id)[1];
    }
}
