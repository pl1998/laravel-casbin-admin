<?php

/*
 * This file is part of the pl1998/thirdparty_oauth.
 *
 * (c) pl1998<pltruenine@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pl1998\ThirdpartyOauth\Api;

use Pl1998\ThirdpartyOauth\Handle\GiteeOauth;
use Pl1998\ThirdpartyOauth\Handle\Line;
use Pl1998\ThirdpartyOauth\Handle\QqOauth;
use Pl1998\ThirdpartyOauth\Handle\GoogleOauth;
use Pl1998\ThirdpartyOauth\Handle\GithubOauth;
use Pl1998\ThirdpartyOauth\Handle\GitlabOauth;
use Pl1998\ThirdpartyOauth\Handle\WeiXinOauth;
use Pl1998\ThirdpartyOauth\Handle\WeiboOauth;
use Pl1998\ThirdpartyOauth\Handle\XiaomiOauth;
use Pl1998\ThirdpartyOauth\Handle\HuaweiOauth;
use Pl1998\ThirdpartyOauth\Handle\MicrosoftOauth;
use Pl1998\ThirdpartyOauth\Handle\JdOauth;
use Pl1998\ThirdpartyOauth\Helpers;

class SocialiteApi implements OauthLinterface
{
    protected $api;

    protected $deliver;

    public function __construct($deiver, array $config)
    {
        $this->deliver = $deiver;
        switch ($deiver) {
            case 'jd':
                return $this->api = new JdOauth($config);
                break;
            case 'alipayapp':
                return $this->api = new AlipayOauth($config);
                break;
            case 'github':
                return $this->api = new GithubOauth($config);
                break;
            case 'weibo':
                return $this->api = new WeiboOauth($config);
                break;
            case 'gitlab':
                return $this->api = new GitlabOauth($config);
                break;
            case 'gitee':
                return $this->api = new GiteeOauth($config);
                break;
            case 'weixin':
                return $this->api = new WeiXinOauth($config);
                break;
            case 'qq':
                return $this->api = new QqOauth($config);
                break;
                 break;
            case 'qqapp':
                return $this->api = new QqOauth($config);
                break;
            case 'microsoft':
                return $this->api = new MicrosoftOauth($config);
                break;
            case 'xiaomi':
                return $this->api = new XiaomiOauth($config);
                break;
            case 'google':
                return $this->api = new GoogleOauth($config);
                break;
            case 'huawei':
                return $this->api = new HuaweiOauth($config);
                break;
            case 'line':
                return $this->api = new Line($config);
                break;
        }
    }

    public function authorization()
    {
        return $this->api->authorization();
    }

    public function getAccessToken(): string
    {
        return $this->api->getAccessToken();
    }

    public function getUserInfo(): object
    {
        $oauth = $this->getAccessToken();

        if ('weixin' == $this->deliver) {
            return $this->api->getUserInfo(json_decode($oauth, true));
        } else {
            $access_token = Helpers::getAccessToken($this->deliver, $oauth);
            return $this->api->getUserInfo($access_token);
        }
    }
}
