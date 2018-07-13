<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3 0003
 * Time: 上午 10:35
 */

namespace app\api\exception;


class BannerMissException extends BaseException{

    public $code = 404;
    public $msg = '请求的banner不存在';

    public $errorCode = 3000;
}