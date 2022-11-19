<?php

namespace App\Services;

use App\Models\Permissions;
use Illuminate\Support\Facades\Log;

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
        $roles = $this->roleService->getUserRoles();
        if($roles->isEmpty()) return false;
        $nodeMaps = $this->permissionService->getRolePermissions($roles);
        if($nodeMaps->isEmpty()) return false;
        $where['url'] = $route;
        return
           Permissions::query()->whereIn('id', $nodeMaps)
               ->where('is_menu', Permissions::IS_MENU_NO)->where($where)
               ->where(function ($query) use ($method): void {
                   $query->where('method', $method)->orWhere('method', '*');
               })
               ->exists()
           ;
    }
}
