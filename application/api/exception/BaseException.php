<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3 0003
 * Time: 上午 10:31
 */

namespace app\api\exception;


use think\Exception;

class BaseException extends Exception
{
    //HTTP状态码
    public $code = 400;

    //错误信息
    public $msg = "参数错误";

    //自定义错误码
    public $errorCode = 10000;

    //自定义异常都必须继承BaseController
}