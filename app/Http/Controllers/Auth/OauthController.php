<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2021/3/9
 * Time : 4:15 下午
 **/

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Pl1998\ThirdpartyOauth\SocialiteAuth;

class OauthController extends Controller
{
    public function giteeCallback()
    {
        $auth = [
            'client_id' => '684a49aa60ce60372463',
            'redirect_uri' => 'http://adminapi.test/api/auth/giteeCallback',
            'client_secret' => '9de1e862504a9836041e2156b87afc146aeb1c09'
        ];

        $api = new SocialiteAuth($auth);

        $user = $api->driver('github')->user();

        var_dump($user);

        //判断用户是否存在表中 然后存入session 或者颁发token 返回给前端
    }
}
