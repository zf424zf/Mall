<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17 0017
 * Time: 下午 9:04
 */

namespace app\api\exception;


class RuleForbiddenException extends BaseException
{
    //HTTP状态码
    public $code = 403;

    //错误信息
    public $msg = "当前用户组权限不够";

    //自定义错误码
    public $errorCode = 1002;
}