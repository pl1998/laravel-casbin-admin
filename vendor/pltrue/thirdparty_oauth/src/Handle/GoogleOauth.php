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

class GoogleOauth implements Handle
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
        $redirect_uris = urlencode($this->config['redirect_uri']);
        $client_id = $this->config['client_id'];
        $scope = urlencode('https://www.googleapis.com/auth/userinfo.profile');
        $url = "https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=offline&client_id={$client_id}&redirect_uri={$redirect_uris}&state&scope={$scope}&approval_prompt=auto";
        header('Location:'.$url);

        exit();
    }

    public function getAccessToken()
    {
        $url = 'https://accounts.google.com/o/oauth2/token';

        $query = array_filter([
            'client_id' => $this->config['client_id'],
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code',
            'client_secret' => $this->config['client_secret'],
            'redirect_uri' => $this->config['redirect_uri'],
        ]);

        return $this->client->request('post', $url, [
            'query' => $query,
        ])->getBody()->getContents();
    }

    public function getUserInfo($access_token)
    {
        $url = 'https://www.googleapis.com/oauth2/v1/userinfo';

        $query = array_filter([
            'access_token' => $access_token,
        ]);

        return json_decode($this->client->request('GET', $url, [
            'query' => $query,
        ])->getBody()->getContents());
    }
}
