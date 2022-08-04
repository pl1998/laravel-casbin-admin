<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2021/6/3
 * Time : 5:56 下午
 **/

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Pl1998\ThirdpartyOauth\SocialiteAuth;

class WeiBoController extends Controller
{
    public function weiboCallBack(RoleService $service)
    {
        $auth = new SocialiteAuth(config('oauth.weibo'));
        $user = $auth->driver('weibo')->user();

        $users = User::query()->where('oauth_id', $user->id)->first();

        if (!$users) {
            $users = User::query()->create([
                'name' => $user->name,
                'email' => '',
                'password' => Hash::make(123456), //默认给个密码呗
                'avatar' => $user->avatar_large,
                'oauth_id' => $user->id,
                'bound_oauth' => 1
            ]);
        }
        $service->setRoles([4], $users->id);

        //关于授权可以了解一下js的窗口通信 window.postMessage
        return view('loading', [
            'token' => auth('api')->login($users),
            'domain' => env('APP_CALLBACK', 'https://pltrue.top/'),
            'app_name' => '微博',
        ]);
    }
}
