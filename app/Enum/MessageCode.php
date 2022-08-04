<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2022/8/4
 * Time : 22:54
 **/

namespace App\Enum;

class MessageCode
{
    public const HTTP_OK=200; //正常响应
    public const HTTP_ERROR=500; // 服务端异常
    public const HTTP_PERMISSION=401; // 无效的访问令牌
    public const HTTP_REFUSED=403; // 拒绝访问


    public const CODE_ERROR = 10001; // 代码异常
    public const DATA_ERROR = 10003; // 数据异常
    public const FUNCTION_EXITS = 10005; // 方法不存在
    public const ROUTE_EXITS = 10004; // 路由404
    public const TOKEN_EXITS = 10006; // TOKEN不存在
    public const PERMISSION_EXITS = 10007; // 无权限访问

    public const USER_ERROR = 40001;
}
