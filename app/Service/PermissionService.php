<?php


namespace App\Service;


use App\Models\Permissions;
use Lauthz\Facades\Enforcer;

class PermissionService
{
    protected function getIdentifier($id)
    {
        return "permission_".$id;
    }

    public function permissionTreeNode($permission) :array
    {
        $permissions = get_tree($permission);
        foreach ($permissions as &$permission) {
            if($permission['is_menu']==1){
                $permission['root'] = true;
            }else{
                $permission['root'] = false;
            }
        }
        return $permissions;
    }

    public function getPermissionMenu($id)
    {
        $id = $this->getIdentifier($id);
        return [];
    }

    /**
     * 设置用户权限
     * @param $nodeId
     * @param $id
     */
    public function setPermissions($nodeId,$id)
    {
        $id = $this->getIdentifier($id);
        $permissions = Permissions::query()->where('status',1)
            ->whereIn('id',$nodeId)
            ->get(['path','method']);
        Enforcer::deletePermissionsForUser($id);
        foreach ($permissions as $value){
            Enforcer::addPermissionForUser($id, $value['path'], $value['method']);
        }
    }

    public function getPermissions($id)
    {

    }
    //获取所有权限
    public function getAllPermission($keyword = null)
    {
        return Permissions::query()
            ->where('status',1)
            ->get(['id','name','icon','path','url','method','p_id','hidden','is_menu','title','status'])
            ->toArray();
    }
}
