<?php

namespace App\Services;

use App\Models\Roles;
use Lauthz\Facades\Enforcer;

class RoleService
{
    /**
     * @param $id
     *
     * @return string
     */
    public function getIdentifier($id)
    {
        return 'roles_'.$id;
    }

    /**
     * 设置用户角色.
     *
     * @param $roleId
     * @param $id
     */
    public function setRoles($roleId, $id): void
    {
        $id = $this->getIdentifier($id);

        Enforcer::deleteRolesForUser($id);

        Roles::query()
            ->where('status', Roles::STATUS_OK)
            ->whereIn('id', $roleId)
            ->get(['id', 'name'])->map(function ($value) use ($id): void {
                Enforcer::addRoleForUser($id, $value->id, $value->name);
            });
    }

    /**
     * 获取用户角色.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getRoles($id)
    {
        $id = $this->getIdentifier($id);
        $roles = Enforcer::getRolesForUser($id);

        if (empty($roles)) {
            return [];
        }

        return Roles::query()->where('status', 1)
            ->whereIn('id', $roles)
            ->get(['id', 'name'])
        ;
    }

    /**
     * 删除用户所有角色.
     *
     * @param $id
     */
    public function delRoles($id): void
    {
        $id = $this->getIdentifier($id);
        Enforcer::deleteRolesForUser($id);
    }
}
