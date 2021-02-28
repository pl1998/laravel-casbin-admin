<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permissions extends Model
{
    use SoftDeletes;

    protected $table = 'admin_permissions';

    public function getIsMenuAttribute($key)
    {
        if($key == 1){
            return true;
        }else{
            return  false;
        }
    }

    public function getPid(){
        return $this->hasOne(Permissions::class,'id','p_id');
    }

}
