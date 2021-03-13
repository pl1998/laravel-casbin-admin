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

class Line implements Handle
{
    protected $client;
    protected $config;
    protected $authorization_url = 'https://access.line.me/oauth2/v2.1/authorize';
    protected $token_url = 'https://api.line.me/oauth2/v2.1/token';
    protected $userinfo_url = 'https://api.line.me/v2/profile';

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
            'scope' => 'profile openid',
            'state' => 'https://6.mxin.ltd/login/qqcallback',
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
            'fmt' => 'json',
        ]);

        $res = $this->client->request('get', $this->token_url, [
            'form_params' => $query,
        ])->getBody()->getContents();

        return json_decode($res)->access_token;
        exit;
    }

    public function getUserInfo($access_token)
    {
        $userinfo = json_decode($this->client->request('GET', $this->userinfo_url, [
            'headers' => [
                'Authorization' => 'Bearer'.$access_token,
            ],
        ])->getBody()->getContents());

        $userinfo->openid = $userinfo->userId;
        $userinfo->unionid = $userinfo->userId;
        $userinfo->nikename = $userinfo->displayName;

        return $userinfo;
    }
}
