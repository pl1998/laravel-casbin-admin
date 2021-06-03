<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2021/4/9
 * Time : 5:01 下午
 **/

//其他平台请保持参数一致 microsoft 注意还有这个参数 region
return [

    'github' => [
        'client_id'=>env('GITHUB_CLIENT_ID',''),
        'redirect_uri'=>env('GITHUB_CALLBACK',''),
        'client_secret'=>env('GITHUB_SECRET',''),
    ],
    'gitee' => [
        'client_id'=>env('GITEE_CLIENT_ID',''),
        'redirect_uri'=>env('GITEE_CALLBACK',''),
        'client_secret'=>env('GITEE_SECRET',''),
    ],
    'weibo' => [
        'client_id'=>env('WEIBO_CLIENT_ID',''),
        'redirect_uri'=>env('WEIBO_CALLBACK',''),
        'client_secret'=>env('WEIBO_SECRET',''),
    ]
];
