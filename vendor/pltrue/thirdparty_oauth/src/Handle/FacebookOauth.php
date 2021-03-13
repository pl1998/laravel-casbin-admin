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

class FacebookOauth implements Handle
{
    protected $client;
    protected $config;
    protected $authorization_url = 'https://www.facebook.com/v3.1/dialog/oauth';
    protected $token_url = 'https://graph.facebook.com/v3.1/oauth/access_token';
    protected $userinfo_url = 'https://graph.facebook.com/v3.1/me';

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
            'scope' => 'user_about_me,email,read_stream',
            'state' => 'https://6.mxin.ltd/login/qq',
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

        $res = $this->client->request('get', $this->token_url, [
            'query' => $query,
        ])->getBody()->getContents();

        return json_decode($res)->access_token;
        exit;
    }

    public function getUserInfo($access_token)
    {
        $query = array_filter([
            'access_token' => $access_token,
            'filds' => 'id,name,email,picture.width(400)',
        ]);
        $this->getUnionid($access_token);
        $userinfo = json_decode($this->client->request('GET', $this->userinfo_url, [
            'query' => $query,
        ])->getBody()->getContents());

        $userinfo->openid = $userinfo->id;
        $userinfo->unionid = $userinfo->id;
        $userinfo->nikename = $userinfo->name;
        $userinfo->email = $userinfo->email ?? '';

        return $userinfo;
    }
}
