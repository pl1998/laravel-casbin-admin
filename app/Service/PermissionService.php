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

    protected function setIdentifier($id) {
       return explode('_',$id)[1];
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

    public function getPermissionMenu($id) :array
    {
        list($node,$permissions) = $this->getPermissions($id);

        if(auth('api')->user()->name == 'admin') {

            $query = Permissions::query()->where('status', 1)->where('is_menu',1);
            $permissions = $query->where(function ($query) use($permissions) {

                foreach ($permissions as $value){

                    $query->whereOr('id',$value[3]);
                }
            })->get(['id','p_id','path','name','title','icon','method','url'])->toArray();


            $permissionsMenu = get_tree($permissions);

            return [$permissionsMenu, $permissions];
        } else {
            if(!empty($permissions)) {
                $query = Permissions::query()->with('getPid')->where('status', 1)->where('is_menu',1);

                $permissions = $query->whereIn('id',$node)->get(['id','p_id','path','name','title','icon','method','url'])->toArray();

                $permissions_array = [];

                foreach ($permissions as $val) {
                    $get_pid = $val['get_pid'];
                    unset($val['get_pid']);
                    $permissions_array[$val['id']] = $val;
                    $permissions_array[$get_pid['id']] =$get_pid;
                }

                $permissionsMenu = get_tree($permissions_array);
                return [$permissionsMenu, $permissions_array];

            }else{
                return [[],[]];
            }
        }
    }




    public function getNodeId($permissions){
        $node = array_column($permissions,'0');
        $nodeId = [];
        foreach ($node as $value){
            $nodeId[] = $this->setIdentifier($value);
        }

        return $nodeId;
    }

    /**
     * 设置用户权限
     * @param $nodeId
     * @param $id
     */
    public function setPermissions($nodeId,$id)
    {
        $id = $this->getIdentifier($id);

        $permissions = Permissions::query()->with('getPid')->where('status', 1)
            ->where('p_id','<>',0)
            ->whereIn('id', $nodeId)
            ->groupBy('id')
            ->get(['path', 'method', 'p_id', 'id', 'name','is_menu','url'])->toArray();

        Enforcer::deletePermissionsForUser($id);

        foreach ($permissions as $value) {
            if($value['is_menu'] ==0) {
                $path = $value['url'];
            }else{
                $path = $value['path'];
            }
            Enforcer::addPermissionForUser($id, $path, $value['method'],$value['id']);
        }
    }


    /**
     * 根据角色id获取权限
     * @param $id
     * @return array
     */
    public function getPermissions($id) : array
    {

        $id = $this->getIdentifier($id);

         $permissions = Enforcer::getPermissionsForUser($id);


         if(empty($permissions)) return [[],[]];
         $node[] = array_map(function ($value)  {
            return (int)$value[3];
         },$permissions);

         sort($node[0]);
         return [$node[0],$permissions];
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
