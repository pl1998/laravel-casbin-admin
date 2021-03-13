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

/**
 * 支持 laravel 服务注入
 * Class ServiceProvider.
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(SocialiteAuth::class, function ($app) {
            return new SocialiteAuth(config('services.$oauth'));
        });

        $this->app->alias(SocialiteAuth::class, 'SocialiteAuth');
    }

    public function provides()
    {
        return [SocialiteAuth::class, 'SocialiteAuth'];
    }
}
