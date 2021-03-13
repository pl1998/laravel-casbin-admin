<?php

/*
 * This file is part of the pl1998/thirdparty_oauth.
 *
 * (c) pl1998<pltruenine@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pl1998\ThirdpartyOauth;

class Helpers
{
    /**
     * @param $oauth
     * @param string $key
     *
     * @return mixed
     */
    public static function getAccessToken($deiver, $oauth, $key = 'access_token')
    {
        switch ($deiver) {
            case 'github':
                $params = explode('=', $oauth);
                $access_token = $params[1];
                $access_token = explode('&', $access_token);
                $access_token = $access_token[0];

                return $access_token;
                break;
            case 'gitlab':
                $oauth = json_decode($oauth, true);

                return 'Bearer '.$oauth[$key];
                break;
            case 'gitee':
                $oauth = json_decode($oauth, true);

                return $oauth[$key];
                break;
            case 'google':
                $oauth = json_decode($oauth, true);

                return $oauth[$key];
                break;
            default:
                return $oauth;
                break;
        }
    }

    /**
     * 判断两个数组是否相同.
     *
     * @return bool
     */
    public static function intendedEffect(array $array, $effect_array)
    {
        if ([] == array_diff($array, $effect_array)) {
            return true;
        } else {
            return false;
        }
    }
}
