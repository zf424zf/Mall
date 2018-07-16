<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

return [
    'type'   => 'Redis',
    'host'   => '127.0.0.1',
    'port'   => '6379',
    'password' => '',
    'timeout'=> 3600,
    // 全局缓存有效期（0为永久有效）
    'expire'=>  0,
    // 缓存前缀
    'prefix'=>  '',
];
