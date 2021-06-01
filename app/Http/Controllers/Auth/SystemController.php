<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2021/5/31
 * Time : 3:52 下午
 **/

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    public function info()
    {
        $server_config = [
            ['name' => '服务器IP地址', 'value' => $_SERVER['SERVER_ADDR']],
            ['name' => '服务器域名', 'value' => $_SERVER['SERVER_NAME']],
            ['name' => '服务器端口', 'value' => $_SERVER['SERVER_PORT']],
            ['name' => '服务器版本', 'value' => php_uname('s') . php_uname('r')],
            ['name' => '服务器操作系统', 'value' => php_uname()],
            ['name' => '服务器IP地址', 'value' => $_SERVER['SERVER_ADDR']],
            ['name' => 'PHP版本', 'value' => PHP_VERSION],
            ['name' => '获取PHP安装路径', 'value' => DEFAULT_INCLUDE_PATH],
            ['name' => 'Zend版本', 'value' => Zend_Version()],
            ['name' => 'Laravel版本', 'value' => $laravel = app()::VERSION],
            ['name' => 'PHP运行方式', 'value' => php_sapi_name()],
            ['name' => '服务器当前时间', 'value' => date("Y-m-d H:i:s")],
            ['name' => '最大上传限制', 'value' => get_cfg_var("upload_max_filesize") ? get_cfg_var("upload_max_filesize") : "不允许"],
            ['name' => '最大执行时间', 'value' => get_cfg_var("max_execution_time") . "秒 "],
            ['name' => '脚本运行占用最大内存', 'value' => get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : "无"],
            ['name' => '服务器解译引擎', 'value' => $_SERVER['SERVER_SOFTWARE']],
            ['name' => '服务器CPU数量', 'value' => $_SERVER['PROCESSOR_IDENTIFIER']],
            ['name' => '服务器Web端口', 'value' => $_SERVER['SERVER_PORT']],
            ['name' => '请求页面时通信协议的名称和版本', 'value' => $_SERVER['SERVER_PROTOCOL']]
            ];

        return $this->success($server_config);
    }
}
