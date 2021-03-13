<?php

/*
 * This file is part of the pl1998/thirdparty_oauth.
 *
 * (c) pl1998<pltruenine@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pl1998\ThirdpartyOauth\Handle;

use GuzzleHttp\Client;

class HuaweiOauth implements Handle
{
    protected $client;
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client();
    }

    public function authorization()
    {
        $url = 'https://oauth-login.cloud.huawei.com/oauth2/v2/authorize';
        $query = array_filter([
            'response_type' => 'code',
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'access_type' => 'offline',
            'scope' => 'https://www.huawei.com/auth/account/base.profile https://www.huawei.com/auth/account/mobile.number email openid',
            'state' => 'https://6.mxin.ltd/login/huawei',
        ]);

        $url = $url.'?'.http_build_query($query);

        header('Location:'.$url);
        exit();
    }

    public function getAccessToken()
    {
        $url = 'https://oauth-login.cloud.huawei.com/oauth2/v3/token';

        $query = array_filter([
            'client_id' => $this->config['client_id'],
            'code' => $_GET['code'] ?? $_GET['authorization_code'],
            'grant_type' => 'authorization_code',
            'client_secret' => $this->config['client_secret'],
            'redirect_uri' => $this->config['redirect_uri'],
        ]);

        $res = json_decode($this->client->request('post', $url, [
            'form_params' => $query,
        ])->getBody()->getContents());
        $this->access_token = $res->access_token;
        $this->refresh_token = $res->refresh_token;
        $this->id_token = $res->id_token;
        $s = explode('.', $this->id_token);
        $userinfo = json_decode($this->base64UrlDecode($s[1]));
        dump($userinfo);

        return $res->access_token;
    }

    public function getUserInfo($access_token)
    {
        $url = 'https://api.cloud.huawei.com/rest.php?nsp_fmt=JSON&nsp_svc=huawei.oauth2.user.getTokenInfo';

        $query = array_filter([
            'openid' => 'OPENID',

            'access_token' => $access_token,
        ]);

        $userinfo = json_decode($this->client->request('POST', $url, [
            'form_params' => $query,
        ])->getBody()->getContents());

        $userinfo->unionid = $userinfo->union_id;

        return $userinfo;
    }

    private function getUnionid($access_token)
    {
        $url = 'https://graph.qq.com/oauth2.0/me?access_token='.$access_token.'&unionid=1&fmt=json';
        $str = $this->client->get($url)->getBody()->getContents();

        return json_decode($str);
    }

    public function getUid($access_token)
    {
    }

    public function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $input .= str_repeat('=', $addlen);
        }

        return base64_decode(strtr($input, '-_', '+/'));
    }
}
