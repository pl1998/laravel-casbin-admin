<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lauthz\Facades\Enforcer;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','avatar', 'email', 'password','created_at','ding_id','oauth_id','oauth_type'
    ];

    public $appends = [
        'roles',
        'introduction'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * 获取会储存到 jwt 声明中的标识
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 返回包含要添加到 jwt 声明中的自定义键值对数组
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['role' => 'user'];
    }

    /**
     * @param $key
     * @return string
     */
    public function getAvatarAttribute($key)
    {
        if(empty($key)) $key= env('APP_URL').'/storage/default-avatar.jpg';
         return $key;
    }

    /**
     * 赋予用户角色
     * @param $key
     * @return string
     */
    public function getRolesAttribute($key)
    {
        $roles = Enforcer::getRolesForUser($this->id);
        if(!empty($roles)) return explode(',',$roles);

        if(empty($key) && $this->name =='admin' || $this->name='test1') {
            $key = 'admin';
        }
        if(empty($key)) {
            $key='users';
        }
        return $key;
    }

    public function getIntroductionAttribute($key){
     return $key;
    }

    public function getUseridByUnionid()
    {

    }
}
