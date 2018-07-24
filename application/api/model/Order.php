<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/23 0023
 * Time: 下午 9:37
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $autoWriteTimestamp = true;
    //订单隐藏字段
    protected $hidden = ['user_id', 'delete_time', 'update_time'];

    const TO_BE_PAID = 1;//待支付

    const PAID = 2;//已支付

    const DELIVERED = 3;//已发货

    const PAID_BUT_NO_STOCK = 4;//已支付但是库存不够
}