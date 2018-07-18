<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4 0004
 * Time: 上午 12:32
 */

namespace app\api\controller\v1;


use app\api\exception\ValidateException;
use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    protected $failException = true;

    /**
     * 重写validate方法,修改抛出的异常为自定义的validate验证异常
     * 验证数据
     * @access protected
     * @param  array $data 数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array $message 提示信息
     * @param  bool $batch 是否批量验证
     * @param  mixed $callback 回调方法（闭包）
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate($data, $validate, $message = [], $batch = true, $callback = null)
    {
        if (is_array($validate)) {
            $v = $this->app->validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $v = $this->app->validate($validate);
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }


        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        if (is_array($message)) {
            $v->message($message);
        }

        if ($callback && is_callable($callback)) {
            call_user_func_array($callback, [$v, &$data]);
        }

        if (!$v->check($data)) {
            if ($this->failException) {
                throw new ValidateException($v->getError());
            }
            return $v->getError();
        }

        return true;
    }


}