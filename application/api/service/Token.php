<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/15
 * Time: 17:37
 */

namespace app\api\service;


use app\api\exception\BaseException;
use app\api\exception\RuleForbiddenException;
use app\api\exception\TokenException;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;

class Token
{
    public static function generateToken()
    {
        //生成32位随机字符串
        $randStr = randStr(32);
        //获取当前时间戳
        $timestamp = $_SERVER['REQUEST_TIME'];
        $salt = config('app.app_mall_key');
        return md5($randStr . $timestamp . $salt);
    }

    public static function getTokenValue($key)
    {
        $token = Request::header('token');
        $cache = Cache::get($token);
        if (!$cache) {
            throw new TokenException();
        } else {
            if (!is_array($cache)) {
                $cache = json_decode($cache, true);
            }
            if (array_key_exists($key, $cache)) {
                return $cache[$key];
            }
            throw new Exception('尝试获取的token变量不存在');
        }


    }

    public static function getCurrentUid()
    {
        return self::getTokenValue('uid');
    }

    /**
     * 检查当前用户权限，只有用户权限才能访问
     * @return bool
     * @throws Exception
     * @throws RuleForbiddenException
     * @throws TokenException
     */
    public static function needOnlyUserScope()
    {
        $scope = self::getTokenValue('scope');
        if ($scope) {
            if ($scope == \Scope::APP_User) {
                return true;
            }
            throw new RuleForbiddenException();
        }
        throw new TokenException();
    }


    /**
     * 检查当前用户权限，至少是用户才能访问
     * @return bool
     * @throws Exception
     * @throws RuleForbiddenException
     * @throws TokenException
     */
    public static function needMinRuleUserScope()
    {
        $scope = self::getTokenValue('scope');
        if ($scope) {
            if ($scope >= \Scope::APP_User) {
                return true;
            }
            throw new RuleForbiddenException();
        }
        throw new TokenException();
    }

    public static function checkUid($checkUid)
    {
        if (!$checkUid) {
            throw new BaseException([
                'errorCode' => '1003',
                'msg' => '确实待检查uid参数'
            ]);
        }
        return self::getCurrentUid() == $checkUid ? true : false;
    }
}