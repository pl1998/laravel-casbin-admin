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
            if($permission['p_id']==0){
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
        $query = Permissions::query()->where('status', 1);
        $permissions = $this->getPermissions($id);

        if(!empty($permissions)) {
            $permissions = $query->where(function ($query) use($permissions) {
                $permissions = $permissions[1];
                foreach ($permissions as $value){
                    $query->whereOr('id',$value[3]);
                }
            })->get(['id','p_id','path','name','title','icon','method'])->toArray();

            $permissionsMenu = get_tree($permissions);

            return [$permissionsMenu, $permissions];

        }else{
            return [[],[]];
        }

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
            ->get(['path','method','p_id','id','name']);
        Enforcer::deletePermissionsForUser($id);
        foreach ($permissions as $value){
            Enforcer::addPermissionForUser($id, $value['path'], $value['method'],$value['id']);
        }
    }


    /**
     * 根据角色id获取权限
     * @param $id
     * @return array
     */
    public function getPermissions($id)
    {
        $id = $this->getIdentifier($id);
         $permissions = Enforcer::getPermissionsForUser($id);
         if(empty($permissions)) return [[],[]];
         $node[] = array_map(function ($value)  {
            return (int)$value[3];
         },$permissions);
         $node = array_column($node,'0');
         return [$node,$permissions];
    }
    //获取所有权限
    public function getAllPermission($keyword = null)
    {

        return Permissions::query()
            ->where('status',1)
            ->get(['id','name','icon','path','url','method','p_id','hidden','is_menu','title','status'])
            ->toArray();
    }

    /**
     * 删除所属角色的权限
     * @param $id
     */
    public function delPermissions($id){
        $id = $this->getIdentifier($id);
        Enforcer::deletePermissionsForUser($id);
    }
}
