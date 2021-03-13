<h1> thirdparty_oauth </h1>

<p> 这是一个PHP社会化登录的第三方登录扩展包</p>

<a href="https://packagist.org/packages/pltrue/thirdparty_oauth"><img src="https://img.shields.io/badge/license-MIT-green" /></a> 
[![Build Status](https://travis-ci.org/pl1998/thirdparty_oauth.svg?branch=master)](https://travis-ci.org/pl1998/thirdparty_oauth)
![StyleCI build status](https://github.styleci.io/repos/295677202/shield)
<a href="https://packagist.org/packages/pltrue/thirdparty_oauth"><img src="https://img.shields.io/badge/php-v7.0+-blue" /></a> 
<a href="https://packagist.org/packages/pltrue/thirdparty_oauth"><img src="https://img.shields.io/badge/downloads-37-brightgreen" /></a> 


## 兼容
> * 支持php >=7.2
> 兼容laravel* 


```shell
# phpunit版本低 需要兼容php7<= laravel高版本安装
 composer require pltrue/thirdparty_oauth  --with-all-dependencies
```



## 目前支持第三方登录

    
 * 1.QQ(app/h5/web)
 * 2.微信(web扫码)
 * 3.微博(app/h5/web)
 * 4.小米(web/h5)
 * 5.抖音
 * 6.世纪互联(微软)
 * 7.微软
 * 8.gitee
 * 9.github
 * 10.gitlab
 * 11.google(有墙)
 * 12.line

## 安装

```shell
$ composer require pltrue/thirdparty_oauth 
```

<hr>

## 贡献
你可以通过以下三种方式做出贡献:

1. bug反馈   [issue tracker](https://github.com/pl1998/thirdparty_oauth/issues).
2. 回答问题或修复错误 [issue tracker](https://github.com/pl1998/thirdparty_oauth/issues).
3. 贡献新特性或更新wiki。

## 贡献者🎉、以及合并日志

| 日期   | 更新级别 | 更新内容      | 贡献者 | 当前状态 |
| ------| -------- | --------- | ---- | ---- |
| 2020-12-06|   fix 、feat   | 新增`Microsoft`登录 修复微信、QQ的bug   | [742481030](https://github.com/742481030)     | 已合并到master分支     |
| 2020-12-08|   feat         | 新增`小米账户`登录    | [742481030](https://github.com/742481030)  | 已合并到master分支     |
| 2020-12-09|   feat         | 新增`google账户`登录    | [742481030](https://github.com/742481030)  | 已合并到master分支     |
| 2020-12-10|   feat         | 新增`华为账户`登录    | [742481030](https://github.com/742481030)  | 已合并到master分支     |
| 2020-12-11|   fix         | qq统一使用json接口    | [742481030](https://github.com/742481030)  | 已合并到master分支     |
| 2020-12-12|   feat         | 新增`抖音账户`登录    | [742481030](https://github.com/742481030)  | 已合并到master分支     |
| 2020-12-13|   feat         | 新增`Line账户`登录    | [742481030](https://github.com/742481030)  | 已合并到master分支     |
| 2020-12-29|   fix         | 增加兼容支付宝qq app混合应用兼容   | [742481030](https://github.com/742481030)  | 已合并到master分支     |
| 2020-12-29|   feat         | 新增`京东账户`登录    | [742481030](https://github.com/742481030)  | 已合并到master分支     |
| 2020-12-29|   fix          | 兼容laravel7*    | [pl1998](https://github.com/pl1998)  | 已合并到master分支     |



## 如何使用

> * [php项目中如何使用?](#测试1)
> * [在Thinkphp中如何使用?](#测试2)
> * [在laravel中如何使用?](#测试3)


<hr>

## 如何申请应用授权？
    
   * [github应用创建地址](https://github.com/settings/developers)
   * [gitee应用创建地址](https://gitee.com/oauth/applications)
   * [gitlab应用创建地址](https://gitlab.com/oauth/applications)
   * [微博应用创建地址](https://open.weibo.com/)
   * [microcoft应用创建地址](https://azure.com/)
   * [QQ互联创建地址](https://connect.qq.com/index.html)
   * [小米应用](https://dev.mi.com/console/)
   * [google应用](https://console.developers.google.com)
   * [京东应用](https://jos.jd.com/)
   

##### 参数说明 

>   <kbd>redirect_url</kbd>   回调地址将使用方法写到回调接口即可 获取到用户的一些基础信息 <br/>
> <kbd>client_id</kbd>     应用授权id <br/>
>  <kbd>client_secret</kbd>  应用授权key <br/>
>  所有支持平台的类型 `github` `gitee` `gitlab` `weibo` `qq` `weixin` `alipay` `microsoft` 配置文件下标一致



##### 建议

> 前后端分离下建议前端直接请求授权接口，后端负责回调接口即可

## <a id="测试1">php项目中如何使用</a>

<hr>

<br/>
<br/>

#### 授权方法  



```php

require __DIR__ .'/vendor/autoload.php';

use Pl1998\ThirdpartyOauth\SocialiteAuth;

$api = new SocialiteAuth([
    'client_id' => '74ee75f10437b4862d653a682111e5ddca1d24422f00ec884453ad232ae07ac9',
    'redirect_uri' => 'http://oauth.test/test.php',
    'client_secret'=>''
]);

return $api->redirect('github');

```
    
#### 回调接口方法

```php

require __DIR__ .'/vendor/autoload.php';

use Pl1998\ThirdpartyOauth\SocialiteAuth;

$api = new SocialiteAuth([
    'client_id' => '74ee75f10437b4862d653a682111e5ddca1d24422f00ec884453ad232ae07ac9',
    'redirect_uri' => 'http://oauth.test/test.php',
    'client_secret' => ''
]);

$user = $api->driver('github')->user();

var_dump($user);die;

```

<br/>

## <a id="测试2">在Thinkphp中如何使用?</a>


<hr>

```php

//在路由文件新建两条路由
Route::get('authorization','api/TestController/authorization')->name('请求授权');
Route::get('gitee/callback','api/TestController/giteeCallback')->name('授权回调接口');

```

## 配置文件

```php
return [
    'github' => [
            'client_id' => '2365a07a73dc25a27e5c7a968248b96beb53a1ad300de7ba6bf4ffe247a4b386',
            'redirect_uri' => 'http://test.test/gitee/callback',
            'client_secret' => ''
        ]
];

```



```php

<?php
/**
 * Created by PhpStorm
 * User: pl
 * Date: 2020/9/17
 * Time: 14:56
 */

namespace app\api\controller;

use Pl1998\ThirdpartyOauth\SocialiteAuth;
use think\facade\Config;

class TestController
{


    /**
     * 该方法重定向后会执行giteeCallback() 方法
     * @return int
     */
    public function authorization()
    {
        $auth = new SocialiteAuth(Config::get('oauth.github'));

        return $auth->redirect('github');

    }

    /**
     * @throws \Pl1998\ThirdpartyOauth\Exceptions\InvalidArgumentException
     */
    public function giteeCallback()
    {
        $api = new SocialiteAuth(Config::get('oauth.github'));

        $user = $api->driver('github')->user();

        var_dump($user);

        //判断用户是否存在表中 然后存入session 或者颁发token 返回给前端
    }
}

```

## <a id="测试3">在laravel中如何使用?</a>


> 在laravle的 <kbd>service.php</kbd>配置文件中加入配置

```php

  'oauth' => [
         'github' => [
             'client_id'    => env('GITHUB_CLIENT_ID'),
             'redirect_uri' => env('GITHUB_REDIRECT_URI'),
             'client_secret'=>env('GITHUB_CLIENT_SECRET')
         ]
  ]
```

#### 在 <kbd>.env</kdb>中配置

```shell
GITHUB_CLIENT_ID=684a49aa60ce
GITHUB_CLINET_SECRETS=86c3800b0da6b6687e7572a9251860
GITHUB_CALLBACK_URL=http://test.test/callback/github
```

## 创建路由

```php
Route::get('auth/github-redirect','OauthController@redirect');
Route::get('callback/github','OauthController@auth');
```


##如果要使用laravel app 注入。先在`config/app.php`注册服务提供者 

```php
   ....
    'providers' => [
       \Pl1998\ThirdpartyOauth\ServiceProvider::class
    ]
```


## 控制器方法

```php
<?php


namespace App\Http\Controllers;
use Pl1998\ThirdpartyOauth\SocialiteAuth;

class OauthController extends Controller
{
    public function redirect()
    {
        //return   app('SocialiteAuth')->redirect('weibo');
        $api = new SocialiteAuth(config('services.oauth.github'));
        return $api->redirect('github');
    }
    public function auth()
    {
       // $api =    app('SocialiteAuth')->driver('weibo');
        //dd($api->user())
        $api = new SocialiteAuth(config('services.oauth.github'));
        $user = $api->driver('github')->user();
        dd($user);
    }
}



```

## 返回示例

![在这里插入图片描述](https://img-blog.csdnimg.cn/20210115174351473.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzQyMDMyMTE3,size_16,color_FFFFFF,t_70#pic_center)

<br/>

## License
<hr>
MIT
