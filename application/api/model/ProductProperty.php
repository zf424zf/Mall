<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16 0016
 * Time: 下午 9:18
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden = ['delete_time','product_id','id'];
}