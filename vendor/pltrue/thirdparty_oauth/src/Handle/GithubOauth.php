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

class GithubOauth implements Handle
{
    protected $client;
    protected $config;

    protected $scope = 'user:email';

    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client();
    }

    public function authorization()
    {
        $url = 'https://github.com/login/oauth/authorize';

        $query = array_filter([
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
        ]);

        $url = $url.'?'.http_build_query($query);

        header('Location:'.$url);
        exit();
    }

    public function getAccessToken()
    {
        $url = 'https://github.com/login/oauth/access_token';

        return $this->client->request('POST', $url, [
            'form_params' => [
                'client_secret' => $this->config['client_secret'],
                'code' => $_GET['code'],
                'client_id' => $this->config['client_id'],
                'redirect_uri' => $this->config['redirect_uri'],
            ],
        ])->getBody()->getContents();
    }

    public function getUserInfo($access_token)
    {
        $url = 'https://api.github.com/user';

        return $userinfo = json_decode($this->client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer '.$access_token,
            ],
        ])->getBody()->getContents());
    }
}
