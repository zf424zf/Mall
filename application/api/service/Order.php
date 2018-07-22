<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19 0019
 * Time: 下午 11:35
 */

namespace app\api\service;

use app\api\exception\OrderException;
use app\api\model\Product as ProductModel;

class Order
{
    //客户端订单商品列表
    protected $orderProducts;

    //database中的商品列表
    protected $products;

    protected $uid;


    public function order($uid, $orderProducts)
    {
        $this->uid = $uid;
        $this->orderProducts = $orderProducts;
        //根据订单参数获取数据库中对应商品信息状态列表
        $this->products = $this->getByOrder($orderProducts);
        //获取订单状态
        $status = $this->getOrderStatus();
        //检查订单是否通过验证，如果不通过，设置order_id 为 -1 并且直接返回给client
        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }
        //todo 创建订单

    }

    /**
     * 根据订单参数中的product_id获取数据库中的商品信息
     * @param array $orderProducts
     * @return mixed
     */
    private function getByOrder(array $orderProducts)
    {
        $pids = array_column($orderProducts, 'product_id');
        return ProductModel::getListByPidArr($pids);
    }

    /**
     * 获取并设置订单状态
     * @return array
     * @throws OrderException
     */
    private function getOrderStatus()
    {
        //订单是否通过验证，订单总价格，商品状态
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'productStatus' => []
        ];
        foreach ($this->orderProducts as $orderProduct) {
            //获取每个商品的详细信息
            $productStatus = $this->getProductStatus($orderProduct['product_id'], $orderProduct['count'], $this->products);
            if (!$productStatus['isStock']) {
                //如果某一个商品的库存量小于购买量，则订单不通过
                $status['pass'] = false;
            }
            //订单总价为商品总价之和
            $status['orderPrice'] += $productStatus['count'];
            array_push($status['productStatus'], $productStatus);
        }
        return $status;
    }

    /**
     * 获取并设置指定id商品状态
     * @param $pid
     * @param $count
     * @param $products
     * @return array
     * @throws OrderException
     */
    private function getProductStatus($pid, $count, $products)
    {
        //请求的商品id,是否有库存,请求的商品数量,商品名，该商品总价格
        $productStatus = [
            'id' => null,
            'isStock' => false,
            'count' => 0,
            'name' => '',
            'sumPrice' => ''
        ];
        $index = -1;//订单pid在products中的下标
        for ($i = 0; $i < count($products); $i++) {
            if ($pid == $products[$i]['id']) {
                //如果查找到商品 则更新下标
                $index = $i;
            }
        }
        if ($index == -1) {
            //商品不存在 抛出异常
            throw new OrderException([
                'msg' => 'id为' . $pid . '的商品不存在，订单创建失败'
            ]);
        }
        //获取商品信息 填充数组
        $product = $products[$index];
        $productStatus['id'] = $product['id'];
        $productStatus['count'] = $count;
        $productStatus['name'] = $product['name'];
        $productStatus['sumPrice'] = $product['price'] * $count;
        //如果商品库存量小于购买量，则设置isStock为false 表示库存不够
        if ($product['stock'] - $count >= 0) {
            $productStatus['isStock'] = true;
        }
        return $productStatus;
    }


}