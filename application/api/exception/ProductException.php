<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12 0012
 * Time: 下午 10:30
 */

namespace app\api\exception;


class ProductException extends BaseException
{
    public $code = 404;
    public $msg = '指定的产品不存在';
    public $errorCode = 5000;
}