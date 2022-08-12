<?php

use Illuminate\Support\Facades\Redis;

if (!function_exists('get_tree')) {
    /**
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int    $root
     *
     * @return array
     */
    function get_tree($list, $pk = 'id', $pid = 'p_id', $child = 'children', $root = 0)
    {
        $tree = [];
        foreach ($list as $key => $val) {
            if ($val[$pid] === $root) {
                // 获取当前$pid所有子类
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

if (!function_exists('redis')) {
    function redis()
    {
        return Redis::connection()->client();
    }
}
