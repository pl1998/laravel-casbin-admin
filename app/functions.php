<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;


if(!function_exists('test')) {
    function test(){
        echo 111;
    }
}

if(!function_exists('_error')) {
    /**
     * 响应报错信息
     * @param $status
     * @param string $message
     * @param array $data]
     */
    function _error($status, string $message,array $data = []) {
        $response = new JsonResponse(compact('status','message','data'));
        throw new HttpResponseException($response);
    }
}

if (!function_exists('recursive_make_tree')) {

    /**
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     */
    function get_tree($list, $pk = 'id', $pid = 'p_id', $child = 'children', $root = 0)
    {
        $tree = [];
        foreach ($list as $key => $val) {
            if ($val[$pid] == $root) {
                //获取当前$pid所有子类
                unset($list[$key]);
                if (!empty($list)) {
                    $child = get_tree($list, $pk, $pid, $child, $val[$pk]);
                    if (!empty($child)) {
                        $val['children'] = $child;
                    }
                }
                $tree[] = $val;
            }
        }
        return $tree;
    }
}
