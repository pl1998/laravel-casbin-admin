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

class QqOauth extends SimpleOauth implements Handle
{
    protected $client;
    protected $config;
    protected $authorization_url = 'https://graph.qq.com/oauth2.0/authorize';
    protected $token_url = 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code';
    protected $userinfo_url = 'https://graph.qq.com/user/get_user_info';

    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client();
    }

    public function authorization()
    {
        $query = array_filter([
            'response_type' => 'code',
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'scope' => '',
            'state' => 'https://6.mxin.ltd/login/qq',
        ]);

        $url = $this->authorization_url.'?'.http_build_query($query);

        header('Location:'.$url);
        exit();
    }

    public function getAccessToken()
    {
        if (isset($_GET['access_token'])) {//兼容app授权登陆 dcloud返回access_token;
            return $_GET['access_token'];
        }
        $query = array_filter([
            'client_id' => $this->config['client_id'],
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code',
            'client_secret' => $this->config['client_secret'],
            'redirect_uri' => $this->config['redirect_uri'],
            'fmt' => 'json',
        ]);

        $res = $this->client->request('get', $this->token_url, [
            'query' => $query,
        ])->getBody()->getContents();
        $data = json_decode($res);
        if (isset($data->access_token)) {
            return $data->access_token;
        } else {
            exit('获取腾讯QQ ACCESS_TOKEN 出错：'.$res);
        }

        return json_decode($res)->access_token;
        exit;
    }

    public function getUserInfo($access_token): object
    {
        $result = $this->getUid($access_token);
        $query = array_filter([
            'openid' => $result->openid,
            'oauth_consumer_key' => $result->client_id,
            'access_token' => $access_token,
        ]);
        $this->getUnionid($access_token);
        $userinfo = json_decode($this->client->request('GET', $this->userinfo_url, [
            'query' => $query,
        ])->getBody()->getContents());
        if (0 != $userinfo->ret) {
            exit('qq获取用户信息出错');
        }

        $userinfo->openid = $this->getUid($access_token)->openid;

        $userinfo->unionid = $this->getUnionid($access_token)->unionid;

        $user = new \stdClass();
        $user->openid = $userinfo->openid;
        $user->unionid = $this->getUnionid($access_token)->unionid ?? '';
        $user->email = $user->openid.'@open.qq.com';
        $user->nickname = $userinfo->nickname;
        $user->avatar = $userinfo->figureurl_2;

        return $user;
    }

    private function getUnionid($access_token): object
    {
        $url = 'https://graph.qq.com/oauth2.0/me?access_token='.$access_token.'&unionid=1&fmt=json';
        $str = $this->client->get($url)->getBody()->getContents();

        return json_decode($str);
    }

    public function getUid($access_token)
    {
        $url = 'https://graph.qq.com/oauth2.0/me?access_token='.$access_token.'&fmt=json';
        $str = $this->client->get($url)->getBody()->getContents();

        $user = json_decode($str);
        if (isset($user->openid)) {
            return $user;
        } else {
            exit('获取用户openid出错：'.$user > error_description);
        }
    }
}
