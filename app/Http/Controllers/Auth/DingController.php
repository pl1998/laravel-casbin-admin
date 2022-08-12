<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2021/6/3
 * Time : 2:48 下午.
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Ding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use ThinkCar\DingTalk\Facades\DingAuth;

class DingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['dingLogin', 'bindQrcode']]);
    }

    public function bindQrcode()
    {
        $redirect_uri = env('DING_REDIRECT_URL');

        return view('code', compact('redirect_uri'));
    }

    /**
     * 用户绑定钉钉.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function DingBing(Request $request)
    {
        if ($request->code) {
            $res = $this->httpDing($request->code);
            $data = $res->json();

            if ($res->successful() && 0 === $data['errcode']) {
                $user = $res['user_info'];
                $d = Ding::where('unionid', $user['unionid'])->first();

                if ($d) {
                    return $this->fail('钉钉账号 该账号已绑定');
                }

                $userid = DingAuth::getUseridByUnionid($user['unionid']);

                $id = auth('api')->id();

                $ding = Ding::create([
                    'openid' => $user['openid'],
                    'nick' => $user['nick'],
                    'ding_id' => $user['dingId'],
                    'user_id' => $id,
                    'unionid' => $user['unionid'],
                    'ding_user_id' => $userid,
                ]);

                $ding_user = DingAuth::user($userid);

                if (0 === $ding_user['errcode']) {
                    $user = $ding->User;
                    if (isset($ding_user['email']) && $email = $ding_user['email']) {
                        $user->email = $email;
                    }
                    if (isset($ding_user['mobile']) && $mobile = $ding_user['mobile']) {
                        $user->phone = $mobile;
                    }
                    if (isset($ding_user['avatar']) && $avatar = $ding_user['avatar']) {
                        if (!$user->avatar) {
                            $user->avatar = $avatar;
                        }
                    }
                    $user->save();
                }

                return $this->success([], '钉钉账号 绑定成功');
            }

            return $this->fail('钉钉账号 绑定失败');
        }

        return $this->fail('相关凭证错误');
    }

    /**
     * 钉钉授权登录.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|void
     */
    public function DingLogin(Request $request)
    {
        if ($request->code) {
            $res = $this->httpDing($request->code);
            $data = $res->json();

            if ($res->successful() && 0 === $data['errcode']) {
                $user = $res['user_info'];
                $ding = Ding::where('unionid', $user['unionid'])->first();
                if ($ding && $ding->User) {
                    $token = auth('api')->login($ding->User);

                    return view('loading', [
                        'token' => $token,
                        'domain' => env('APP_CALLBACK', 'https://pltrue.top/'),
                        'app_name' => '钉钉',
                    ]);
                }
            }

            return $this->fail('用户不存在', 403, [
                'redirect_url' => 'login',
            ]);
        }
    }

    /**
     * 请求钉钉api.
     *
     * @param $code
     *
     * @return \Illuminate\Http\Client\Response
     */
    protected function httpDing($code)
    {
        $time = (int) now()->getPreciseTimestamp(3);
        $gateway = 'https://oapi.dingtalk.com/sns/getuserinfo_bycode';
        $sign = hash_hmac('sha256', $time, env('DT_AUTH_SECRET'), true);
        $signature = base64_encode($sign);
        $urlencode_signature = urlencode($signature);
        $key = env('DT_AUTH_APPID');
        $url = "{$gateway}?accessKey={$key}&timestamp={$time}&signature={$urlencode_signature}";

        return Http::post($url, [
            'tmp_auth_code' => $code,
        ]);
    }
}
