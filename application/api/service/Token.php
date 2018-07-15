<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/15
 * Time: 17:37
 */

namespace app\api\service;


class Token
{
    public static function generateToken(){
        //生成32位随机字符串
        $randStr = randStr(32);
        //获取当前时间戳
        $timestamp = $_SERVER['REQUEST_TIME'];
        $salt = config('app.app_mall_key');
        return md5($randStr . $timestamp . $salt);
    }


}