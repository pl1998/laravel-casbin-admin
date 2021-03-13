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

class GitlabOauth implements Handle
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
        $url = 'https://gitlab.example.com/oauth/authorize';

        $query = array_filter([
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'response_type' => 'code',
        ]);

        $url = $url.'?'.http_build_query($query);

        header('Location:'.$url);
        exit();
    }

    public function getAccessToken()
    {
        $url = 'https://gitlab.example.com/oauth/token';

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
        $url = 'https://gitlab.example.com/api/v4/user';

        return json_decode($this->client->request('POST', $url, [
            'headers' => [
                'Authorization' => $access_token,
            ],
        ])->getBody()->getContents());
    }
}
