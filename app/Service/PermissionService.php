<?php


namespace App\Service;


use App\Models\Permissions;

class PermissionService
{



    public function permissionTreeNode($permission) :array
    {
        $permissions = get_tree($permission);


             # 处理一下跟路由的一些情况
        foreach ($permissions as &$permission) {
            $permission['root'] = true;
        }
        return $permissions;
    }




    protected function getIdentifier($id)
    {
        return "permission_".$id;
    }


    public function getPermissionMenu($id)
    {
        $id = $this->getIdentifier($id);
        return [];
    }

    public function getPermissions($id)
    {

    }

    //获取所有权限
    public function getAllPermission($keyword = null){
        return Permissions::query()
            ->where('status',1)
            ->get(['id','name','icon','path','url','method','p_id','hidden','is_menu','title','status'])
            ->toArray();
    }
}
