<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/15
 * Time: 16:29
 */

namespace app\api\exception;


class WeiXinException extends BaseException
{
    //HTTP状态码
    public $code = 400;

    //错误信息
    public $msg = "微信服务端接口调用失败";

    //自定义错误码
    public $errorCode = 9002;
}