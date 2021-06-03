<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2021/6/3
 * Time : 2:25 下午
 **/

namespace App\Models;




use App\User;
use Illuminate\Database\Eloquent\Model;

class Ding extends Model
{
    public $table = 'dings';


    public function User()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
