<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17 0017
 * Time: 上午 11:02
 */

namespace app\api\exception;


class UserException extends BaseException
{
    //HTTP状态码
    public $code = 404;

    //错误信息
    public $msg = "用户不存在";

    //自定义错误码
    public $errorCode = 9100;
}