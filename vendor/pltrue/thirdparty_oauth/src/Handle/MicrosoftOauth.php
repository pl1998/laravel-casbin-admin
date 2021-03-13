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

class MicrosoftOauth implements Handle
{
    protected $client;
    protected $config;
    protected static $endpoint = [
        'cn' => [
            'authorization' => 'https://login.chinacloudapi.cn/common/oauth2/v2.0/authorize',
            'token' => 'https://login.chinacloudapi.cn/common/oauth2/v2.0/token',
            'userinfo' => 'https://microsoftgraph.chinacloudapi.cn/v1.0/me',
        ],
        'us' => [
            'authorization' => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
            'token' => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
            'userinfo' => 'https://graph.microsoft.com/v1.0/me',
        ],
    ];

    public function __construct($config)
    {
        $this->config = $config;

        if (!isset($this->config['region'])) {
            $this->config['region'] = 'us';
        }

        $this->client = new Client();
    }

    public function authorization()
    {
        $url = self::$endpoint[$this->config['region']]['authorization'];

        $query = array_filter([
            'response_type' => 'code',
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'scope' => 'User.Read openid profile',
            'prompt' => 'consent',
            'state' => 'https://6.mxin.ltd/login/microsoft',
        ]);

        $url = $url.'?'.http_build_query($query);

        header('Location:'.$url);
        exit();
    }

    public function getAccessToken()
    {
        $url = self::$endpoint[$this->config['region']]['token'];
        $query = array_filter([
            'client_id' => $this->config['client_id'],
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code',
            'client_secret' => $this->config['client_secret'],
            'redirect_uri' => $this->config['redirect_uri'],
        ]);

        $resp = ($this->client->request('POST', $url, [
            'form_params' => $query,
        ])->getBody()->getContents());
        $id_token = json_decode($resp);
        $s = explode('.', $id_token->id_token);

        $data['unionid'] = json_decode($this->base64UrlDecode($s[1]))->oid;

        return $data['$access_token'] = $id_token->access_token;

        return $data;
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

    public function getUserInfo($access_token)
    {
        $url = self::$endpoint[$this->config['region']]['userinfo'];
        $userinfo = json_decode($this->client->request('GET', $url, [
            'headers' => [
                'Authorization' => $access_token,
            ],
        ])->getBody()->getContents());

        //$userinfo->openid = $userinfo->sub;

        return $userinfo;
    }

    public function getUid($access_token)
    {
    }
}
