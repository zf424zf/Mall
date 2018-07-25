<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24 0024
 * Time: 下午 1:28
 */

namespace app\api\service;


use app\api\exception\OrderException;
use app\api\exception\TokenException;
use think\Exception;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\service\Token as TokenService;
use think\facade\Log;

class Pay
{
    private $orderId;
    private $orderNo;

    public function __construct($orderId)
    {
        if (!$orderId) {
            throw new Exception('订单号不允许为空');
        }
        $this->orderId = $orderId;
    }

    public function pay()
    {
        //检查验证订单状态
        $this->validateOrder();
        $orderService = new OrderService();
        //检查库存，获取订单信息
        $status = $orderService->checkOrderStock($this->orderId);
        if (!$status['pass']) {
            //如果订单检测不通过 直接 返回
            return $status;
        }
        //todo list 微信支付
        return $this->wxPay($status['orderPrice']);
    }

    /**
     * 微信支付设置该条订单基本信息
     * @param $totalPrice
     * @throws Exception
     * @throws TokenException
     * @throws \WxPayException
     */
    private function wxPay($totalPrice)
    {
        //获取openId
        $openId = TokenService::getTokenValue('openid');
        if (!$openId) {
            //openId为空 则openId过期
            throw new TokenException();
        }
        $wxConfig = new WXConfig();
        $wxOrder = new \WxPayUnifiedOrder();
        //设置订单号
        $wxOrder->SetOut_trade_no($this->orderNo);
        //设置交易类型
        $wxOrder->SetTrade_type('JSAPI');
        //设置支付总金额
        $wxOrder->SetTotal_fee($totalPrice * 100);
        //设置描述
        $wxOrder->SetBody('商品支付');
        //设置用户openId
        $wxOrder->SetOpenid($openId);
        //设置微信回调通知
        //todo
        $wxOrder->SetNotify_url('');

        return $this->getPaySign($wxConfig, $wxOrder);
    }

    /**
     * 向微信发送预支付订单
     * @param $wxOrder
     * @throws \WxPayException
     */
    private function getPaySign($wxConfig, $wxOrder)
    {
        $result = \WxPayApi::unifiedOrder($wxConfig, $wxOrder);

        if ($result['return_code'] != 'SUCCESS' ||
            $result['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取微信预支付订单失败', 'error');
        }
        //保存prepay ID 用户微信推送
        $this->prePayId($result);
        //生成签名
        $sign = $this->makeSign($wxConfig, $result);
        return $sign;
    }

    private function makeSign($wxConfig, $result)
    {
        $jsApiData = new \WxPayJsApiPay();
        //设置小程序id
        $jsApiData->SetAppid(config('wx.app_id'));
        //设置时间轴 字符串格式
        $jsApiData->SetTimeStamp((string)time());
        //生成随机字符串 mt_rand相较于rand速度快4倍
        $randStr = md5(config('app.app_mall_key') . time() . mt_rand(0, 1000));
        //设置随机字符串
        $jsApiData->SetNonceStr($randStr);
        //设置订单详情扩展字符串
        $jsApiData->SetPackage('prepay_id=' . $result['prepay_id']);
        //设置签名算法为md5加密
        $jsApiData->SetSignType('md5');
        //根据微信配置生成签名
        $sign = $jsApiData->MakeSign($wxConfig);
        //设置签名
        $jsApiData->SetPaySign($sign);
        $rawData = $jsApiData->GetValues();
        //appid不返回给客户端
        unset($rawData['appId']);
        return $rawData;
    }

    /**
     * 保存对应订单的prepay ID
     * @param $wxResult
     */
    private function prePayId($wxResult)
    {
        OrderModel::where('id', $this->orderId)->update(['prepay_id' => $wxResult['prepay_id']]);
    }

    /**
     * 检查验证订单状态
     * @return mixed
     * @throws OrderException
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function validateOrder()
    {
        //根据orderId查询订单，不存在 抛出异常
        $order = OrderModel::where('id', '=', $this->orderId)->find();
        if (!$order) {
            throw new OrderException();
        }
        //订单uid与当前登录uid进行匹配 不一致则抛出异常
        if (TokenService::getCurrentUid() !== $order->user_id) {
            throw new TokenException([
                'msg' => '订单与当前用户信息不匹配',
                'errorCode' => 8501
            ]);
        }

        //判断订单是否被支付
        if ($order->status != OrderModel::TO_BE_PAID) {
            throw new OrderException([
                'msg' => '该订单已经被支付',
                'errorCode' => 8502
            ]);
        }
        //设置订单号
        $this->orderNo = $order->order_no;
        return true;
    }
}