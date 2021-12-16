<?php


namespace App\Services;

use Lauthz\Facades\Enforcer;
use App\Models\Roles;

class RoleService
{
    protected function getIdentifier($id)
    {
        return "roles_".$id;
    }

    /**
     * 获取用户角色
     * @param $roleId
     * @param $id
     */
    public function setRoles($roleId,$id)
    {
        $id = $this->getIdentifier($id);
        $roles = Roles::query()->where('status',1)
            ->whereIn('id',$roleId)
            ->get(['id','name']);

        Enforcer::deleteRolesForUser($id);

        foreach ($roles as $value){
            Enforcer::addRoleForUser($id, $value['id'], $value['name']);
        }

    }

    /**
     * 获取用户角色
     * @param $id
     * @return mixed
     */
    public function getRoles($id){
        $id = $this->getIdentifier($id);
        $roles = Enforcer::getRolesForUser($id);

        if(empty($roles)) return [];

        $roles = Roles::query()->where('status',1)
            ->whereIn('id',$roles)
            ->get(['id','name']);

        return $roles;
    }

    /**
     * 删除用户所有角色
     * @param $id
     */
    public function delRoles($id,$roleId=[]){
        $id = $this->getIdentifier($id);
        Enforcer::deleteRolesForUser($id);
    }

}
