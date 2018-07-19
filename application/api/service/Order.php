<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19 0019
 * Time: 下午 11:35
 */

namespace app\api\service;

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
        return $this->products = $this->getByOrder($orderProducts);
    }

    private function getByOrder(array $orderProducts)
    {
          $pids = array_column($orderProducts,'product_id');
          return ProductModel::select($pids)->visible(['id','price','stock','name','main_img_url']);
    }
}