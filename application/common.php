<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    //设置请求地址
    curl_setopt($ch,CURLOPT_URL,$url);
    //获取的信息以字符串返回，而不是直接输出。
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    //禁止验证证书
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,config('app.curl_ssl_verify'));
    //在尝试连接时等待的秒数。
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,15);

    $file_contents = curl_exec($ch);
    //获取返回的http_code
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    //关闭curl
    curl_close($ch);
    return $file_contents;
}