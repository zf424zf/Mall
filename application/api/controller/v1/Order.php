<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17 0017
 * Time: 下午 9:11
 */

namespace app\api\controller\v1;

use app\api\Validate\Order\PlaceOrderValidate;
use think\facade\Request;

class Order extends BaseController
{
    //用户在选择商品后，提交所选商品信息到api
    //API检查库存量
    //如果库存不为0 则生成订单存入数据库，下单成功后返回客户端
    //客户端吊起支付接口
    //api检查库存，有库存吊起微信支付接口进行支付
    //微信异步返回支付结果
    //若成功 进行库存检查 然后库存量扣除

    protected $middleware = [
        'OrderPlaceMiddleware' => ['only' => ['placeorder'] ],
    ];

    public function placeOrder()
    {
        $products = json_decode(Request::post('products'),true);
        $this->validate(compact('products'),PlaceOrderValidate::class);
    }
}