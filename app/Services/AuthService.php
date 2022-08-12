<?php

namespace App\Services;

use App\Models\Permissions;

class AuthService
{
    public $permissionService;
    public $roleService;

    public function __construct()
    {
        $this->permissionService = new PermissionService();
        $this->roleService = new RoleService();
    }

    public function getRoles($id)
    {
        return $this->roleService->getRoles($id);
    }

    public function checkPermission($id, $method, $route): bool
    {
        $role = $this->getRoles($id);
        if (empty($role)) {
            return false;
        }

        $role = $role->map(fn ($val) => $val['id'])->toArray();

        $node_array = [];
        foreach ($role as $value) {
            [$node, $permissions] = $this->permissionService->getPermissions($value);
            $node_array[] = $node;
        }
        $where['url'] = $route;

        return
           Permissions::query()->whereIn('id', $node_array[0])
               ->where('is_menu', Permissions::IS_MENU_NO)->where($where)
               ->where(function ($query) use ($method): void {
                   $query->where('method', $method)->orWhere('method', '*');
               })
               ->exists()
           ;
    }
}
