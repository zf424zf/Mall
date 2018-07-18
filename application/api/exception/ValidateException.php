<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4 0004
 * Time: 上午 12:58
 */

namespace app\api\exception;


class ValidateException extends BaseException
{
    public $code = 400;

    public $msg = '验证参数错误';

    public $errorCode = 9998;

    public function __construct(array $error, $errorCode = 9998)
    {

        $error = current($error);
        if (is_numeric($error)) {
            //如果是自定义异常且重写了message属性，则error为int类型的错误码 定义在ApiCode文件中
            $this->errorCode = $error;
            //加载ApiCode文件
            $apiCode = require $_SERVER['DOCUMENT_ROOT'] . '/../helper/ApiCode.php';
            //获取错误码对应的错误提示
            $this->msg = isset($apiCode[$error]) ? $apiCode[$error] : $this->msg;
        } else {
            //如果没有重写message属性
            $this->msg = is_array($error) ? implode("\n\r", $error) : $error;
            $this->errorCode = $errorCode;
        }
    }
}