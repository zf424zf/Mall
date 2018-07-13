<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3 0003
 * Time: 上午 10:30
 */

namespace app\api\exception;


use Exception;
use think\exception\Handle;
use think\facade\Log;
use think\facade\Request;

/**
 * 重写自定义异常处理类
 * 修改config中app.exception_handle为当前类
 * Class ExceptionHandle
 * @package app\api\exception
 */
class ExceptionHandle extends Handle

{
    private $code;

    private $msg;

    private $errorCode;

    //需要返回客户端当前请求地址

    /**
     * 自定义异常处理类 重写render方法
     * @param Exception $e
     * @return \think\Response|\think\response\Json
     */
    public function render(\Exception $e)
    {
        $request = Request::instance();
        if ($e instanceof BaseException) {
            //若是自定义的异常，则抛出自定义异常信息
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            //若是服务器或者框架自己抛出的异常
            if(!empty(config('app.show_custom_exception'))){
                //若配置显示自定义异常
                $this->code = 500;
                $this->msg = '服务器错误';
                $this->errorCode = 9999;
                //记录日志
                $this->recordErrorLog($e, $request);
            }else{
                //否则执行父类render 抛出自带异常
                return parent::render($e);
            }
        }
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result, $this->code);
    }

    /**
     * 全局异常日志记录
     * @param Exception $e
     * @param \think\Request $request
     */
    private function recordErrorLog(\Exception $e, \think\Request $request)
    {
        $param = join('', $request->param());
        $msg = <<<EOT
错误信息:{$e->getMessage()}
当前请求参数:{$param}
当前请求地址:{$request->url()}
EOT;
        Log::write($msg, 'error');
    }
}