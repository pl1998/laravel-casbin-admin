<?php


namespace App\Services;


use App\Models\Permissions;
use Lauthz\Facades\Enforcer;

class AuthService
{
    public $permissionService;
    public $roleService;

    public function __construct()
    {
        $this->permissionService = new PermissionService();
        $this->roleService = new RoleService;
    }

    public function getRoles($id)
    {
      return  $this->roleService->getRoles($id);
    }


    public function checkPermission($id,$method,$route) :bool
    {
//        if(Enforcer::enforce($this->roleService->getIdentifier($id),$route,$method)) {
//            return true;
//        } else {
//            return false;
//        }
       $role =  $this->getRoles($id);
       if(empty($role)) return false;

       $role = $role->map(function ($val){
           return $val['id'];
       })->toArray();

       $node_array = [];
       foreach ($role as $value) {
          list($node,$permissions) = $this->permissionService->getPermissions($value);
           $node_array[] = $node;
       }

       $where['url'] = $route;

       return
           Permissions::query()->whereIn('id',$node_array[0])->where('is_menu',0)->where($where)
               ->where(function ($query) use($method){
                   $query->where('method',$method)->orWhere('method',"*");
               })
               ->exists();
    }
}