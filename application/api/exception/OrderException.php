<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/22
 * Time: 21:07
 */

namespace app\api\exception;


class OrderException extends BaseException
{
    //HTTP状态码
    public $code = 400;

    //错误信息
    public $msg = "订单错误";

    //自定义错误码
    public $errorCode = 8500;
}