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

class XiaomiOauth implements Handle
{
    protected $client;
    protected $config;
    protected $authorization_url = 'https://account.xiaomi.com/oauth2/authorize';
    protected $token_url = 'https://account.xiaomi.com/oauth2/token';
    protected $userinfo_url = 'https://open.account.xiaomi.com/user/profile';

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
            'scope' => '1 3',
            'state' => '',
        ]);

        $url = $this->authorization_url.'?'.http_build_query($query);

        header('Location:'.$url);
        exit();
    }

    public function getAccessToken()
    {
        $query = array_filter([
            'client_id' => $this->config['client_id'],
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code',
            'client_secret' => $this->config['client_secret'],
            'redirect_uri' => $this->config['redirect_uri'],
        ]);

        $ss = $this->client->request('get', $this->token_url, [
            'query' => $query,
        ])->getBody()->getContents();

        $res = \json_decode(str_replace('&&&START&&&', '', $ss));
        $this->openid = $res->openId;

        return $this->access_token = $res->access_token;
        exit;
    }

    public function getUserInfo($access_token)
    {
        $query = array_filter([
            'client_id' => $this->config['client_id'],
            'token' => $access_token,
        ]);

        $user = json_decode($this->client->request('GET', $this->userinfo_url, [
            'query' => $query,
        ])->getBody()->getContents())->data;
        $userinfo = new \stdClass();
        $userinfo->unionid = $user->unionId;
        $userinfo->openid = $this->openid;
        $userinfo->nikename = $user->miliaoNick;
        $userinfo->avatar = $user->miliaoIcon_120;

        return $userinfo;
    }
}
