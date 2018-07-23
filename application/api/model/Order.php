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
    //订单隐藏字段
    protected $hidden = ['user_id', 'delete_time', 'update_time'];
}