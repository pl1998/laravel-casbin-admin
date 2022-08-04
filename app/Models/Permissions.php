<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permissions extends Model
{
    use SoftDeletes;

    protected $table = 'admin_permissions';

    const IS_MENU_YES =1;
    const IS_MENU_NO =0;

    const STATUS_DOWN =0;
    const STATUS_OK =1;

    const HTTP_REQUEST_ALL = '*';
    const HTTP_REQUEST_GET = 'GET';
    const HTTP_REQUEST_POST = 'POST';
    const HTTP_REQUEST_PUT = 'PUT';
    const HTTP_REQUEST_PATCH = 'PATCH';
    const HTTP_REQUEST_DELETE = 'DELETE';

    public function getIsMenuAttribute($key)
    {
        if($key == 1){
            return true;
        }else{
            return  false;
        }
    }

    public function getPid(){
        return $this->hasOne(Permissions::class,'id','p_id')->select(['id','p_id','path','name','title','icon','method','url']);
    }

}
