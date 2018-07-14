<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/14
 * Time: 15:15
 */

namespace app\api\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '指定类目不存在';
    public $errorCode = 6000;
}