<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19 0019
 * Time: 下午 11:35
 */

namespace app\api\service;

use app\api\exception\OrderException;
use app\api\exception\UserException;
use app\api\model\OrderProduct;
use app\api\model\Product as ProductModel;
use app\api\model\UserAddress;
use app\api\model\Order as OrderModel;
use think\Db;
use think\Exception;

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
        //生成订单快照
        $snap = $this->makeOrderSnap($status);
        //create order
        $order = $this->makeOrder($snap);
        $order['pass'] = true;
        return $order;
    }

    /**
     * 创建订单
     * @param $orderSnap
     * @return array
     * @throws Exception
     */
    private function makeOrder($orderSnap)
    {
        Db::startTrans();
        try {
            //生成订单号
            $orderNo = self::makeOrderId();
            //订单创建
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $orderSnap['orderPrice'];
            $order->total_count = $orderSnap['totalCount'];
            $order->snap_img = $orderSnap['mainImage'];
            $order->snap_name = $orderSnap['title'];
            $order->snap_address = $orderSnap['userAddress'];
            $order->snap_items = json_encode($orderSnap['productStatus']);
            $order->save();
            //获取order_id
            $orderId = $order->id;
            //获取订单创建时间
            $createTime = $order->create_time;
            foreach ($this->orderProducts as &$product) {
                $product['order_id'] = $orderId;
            }

            //保存订单商品表
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->orderProducts);
            Db::commit();
            //返回订单信息
            return [
                'order_no' => $orderNo,
                'order_id' => $orderId,
                'create_time' => $createTime
            ];
        } catch (Exception $exception) {
            Db::rollback();
            throw $exception;
        }
    }


    /**
     * 生成18位订单号
     * @return string
     */
    public static function makeOrderId()
    {
        $first_str = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        return $first_str[(intval(date('Y')) - 2018) % 10]
            . strtoupper(dechex(date('m')))
            . date('d') . substr(time(), -5)
            . substr(microtime(), 2, 7)
            . sprintf('%02d', rand(0, 99));
    }

    /**
     * 生成订单快照
     * @param array $orderStatus
     * @return array
     * @throws UserException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function makeOrderSnap(array $orderStatus)
    {
        //初始化快照数组
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'productStatus' => [],
            'userAddress' => null,
            'title' => '',
            'mainImage' => ''
        ];

        //订单快照赋值
        $snap['orderPrice'] = $orderStatus['orderPrice'];
        $snap['totalCount'] = $orderStatus['totalCount'];
        $snap['productStatus'] = $orderStatus['productStatus'];
        $snap['userAddress'] = json_encode($this->getAddress());
        $snap['title'] = $this->products[0]['name'];
        $snap['mainImage'] = $this->products[0]['main_img_url'];

        if (count($this->products) > 1) {
            //如果商品列表大于一件 则标题加等
            $snap['title'] .= '等';
        }
        return $snap;
    }

    /**
     * 获取用户地址
     * @return array
     * @throws UserException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getAddress()
    {
        //查询当前用户的地址
        $address = UserAddress::where('user_id', '=', $this->uid)->find();
        if (!$address) {
            throw new UserException([
                'msg' => '用户收货地址不存在，订单生产失败',
                'errorCode' => 8600
            ]);
        }
        return $address->toArray();
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
            'totalCount' => 0,
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
            $status['orderPrice'] += $productStatus['sumPrice'];
            //计算商品总数量
            $status['totalCount'] += $productStatus['count'];
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

    /**
     * 检查库存量
     * @param $orderId
     * @return array
     * @throws OrderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkOrderStock($orderId)
    {
        //根据orderId查询orderProduct
        $orderProduct = OrderProduct::where('order_id', '=', $orderId)->select()->toArray();
        $this->orderProducts = $orderProduct;
        //根据订单获取商品信息
        $this->products = $this->getByOrder($orderProduct);
        //获取订单状态
        $status = $this->getOrderStatus();
        return $status;
    }

}