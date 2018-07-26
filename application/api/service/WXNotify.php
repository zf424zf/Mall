<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26 0026
 * Time: 上午 10:08
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;

class WXNotify extends \WxPayNotify
{
    public function NotifyProcess($objData, $config, &$msg)
    {
        if ($objData['result_code'] == 'SUCCESS') {
            $tradeNo = $objData['out_trade_no'];
            try {
                $order = OrderModel::where('order_no', '=', $tradeNo)->find();
                if ($order->status == OrderModel::TO_BE_PAID) {
                    $orderSer = new OrderService();
                    $status = $orderSer->checkOrderStock($order->id);
                    if ($status['pass']) {
                        $this->updateOrderStatus();
                        $this->reduceStock();
                    }
                }
            } catch ()
        }
    }

    private function updateOrderStatus($orderId,$success)
    {
         $status = $success ? OrderModel::PAID : OrderModel::PAID_BUT_NO_STOCK;
    }
}