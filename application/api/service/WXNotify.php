<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26 0026
 * Time: 上午 10:08
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\model\Product;
use app\api\service\Order as OrderService;
use think\Db;
use think\Exception;
use think\Log;

class WXNotify extends \WxPayNotify
{

    public function NotifyProcess($objData, $config, &$msg)
    {
        //如果微信支付橙锤
        if ($objData['result_code'] == 'SUCCESS') {
            //获取订单号
            $tradeNo = $objData['out_trade_no'];
            Db::startTrans();
            try {
                //查询订单
                $order = OrderModel::where('order_no', '=', $tradeNo)->find();
                //如果订单待支付
                if ($order->status == OrderModel::TO_BE_PAID) {
                    $orderSer = new OrderService();
                    //检查库存量
                    $status = $orderSer->checkOrderStock($order->id);
                    //如果库存量通过，更新订单状态，减去库存量，如果不存在 更新订单状态为已支付并且库存不足
                    if ($status['pass']) {
                        $this->updateOrderStatus($order->id, true);
                        $this->reduceStock($status);
                    } else {
                        $this->updateOrderStatus($order->id, false);
                    }
                }
                Db::commit();
                return true;
            } catch (Exception $exception) {
                Db::rollback();
                Log::error($exception);
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * 更新订单状态
     * @param $orderId
     * @param $success
     */
    private function updateOrderStatus($orderId, $success)
    {
        //更新订单状态 如果成功
        $status = $success ? OrderModel::PAID : OrderModel::PAID_BUT_NO_STOCK;
        OrderModel::where('id', '=', $orderId)
            ->update(['status' => $status]);
    }

    private function reduceStock($status)
    {
        foreach ($status['productStatus'] as $productStatus) {
            Product::where('id', '=', $productStatus['id'])->setDec('stock', $productStatus['count']);
        }
    }
}