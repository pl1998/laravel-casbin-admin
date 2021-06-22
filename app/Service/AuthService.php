<?php


namespace App\Service;


use App\Models\Permissions;

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
       if(Permissions::query()->whereIn('id',$node_array[0])->where('is_menu',0)->where($where)
           ->where(function ($query) use($method){
               $query->where('method',$method)->orWhere('method',"*");
           })
           ->exists()){
           return true;
       }
       return false;
    }
}
