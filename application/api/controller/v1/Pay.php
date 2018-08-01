<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24 0024
 * Time: 上午 11:52
 */

namespace app\api\controller\v1;


use app\api\service\WXConfig;
use app\api\service\WXNotify;
use app\api\Validate\PayOrder\PayOrderValidate;
use app\api\service\Pay as PayService;
use think\facade\Request;

class Pay extends BaseController
{
    protected $middleware = [
        'PayMiddleware' => ['only' => ['payOrder']]
    ];

    /**
     * 微信支付接口
     * @return array
     * @throws \app\api\exception\ValidateException
     * @throws \think\Exception
     */
    public function payOrder()
    {
        $id = Request::post('id');
        $this->validate(compact('id'), PayOrderValidate::class);
        $pay = new PayService($id);
        return $pay->pay();
    }

    /**
     * 微信支付回调
     */
    public function receiveNotify()
    {
        $notify = new WXNotify();
        $notify->Handle(new WXConfig(),true);
    }
}