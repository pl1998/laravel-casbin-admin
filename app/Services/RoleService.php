<?php


namespace App\Services;

use Lauthz\Facades\Enforcer;
use App\Models\Roles;

class RoleService
{
    /**
     * @param $id
     * @return string
     */
    public function getIdentifier($id)
    {
        return "roles_" . $id;
    }

    /**
     * 设置用户角色
     * @param $roleId
     * @param $id
     * @return void
     */
    public function setRoles($roleId, $id)
    {
        $id = $this->getIdentifier($id);

        Enforcer::deleteRolesForUser($id);

        Roles::query()
            ->where('status', Roles::STATUS_OK)
            ->whereIn('id', $roleId)
            ->get(['id', 'name'])->map(function ($value) use ($id) {
                Enforcer::addRoleForUser($id, $value->id, $value->name);
            });
    }

    /**
     * 获取用户角色
     * @param $id
     * @return mixed
     */
    public function getRoles($id)
    {
        $id = $this->getIdentifier($id);
        $roles = Enforcer::getRolesForUser($id);

        if (empty($roles)) return [];

        $roles = Roles::query()->where('status', 1)
            ->whereIn('id', $roles)
            ->get(['id', 'name']);

        return $roles;
    }

    /**
     * 删除用户所有角色
     * @param $id
     */
    public function delRoles($id)
    {
        $id = $this->getIdentifier($id);
        Enforcer::deleteRolesForUser($id);
    }

}
