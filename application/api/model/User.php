<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/14
 * Time: 16:30
 */

namespace app\api\model;


class User extends BaseModel
{
    public static function getUserByOpenID($openID){
        return $user = self::where('openid',$openID)->find();
    }
}