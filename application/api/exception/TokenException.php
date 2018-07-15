<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/15
 * Time: 22:41
 */

namespace app\api\exception;


class TokenException extends BaseException
{
    //HTTP状态码
    public $code = 401;

    //错误信息
    public $msg = "Token已过期或者token无效";

    //自定义错误码
    public $errorCode = 1001;
}