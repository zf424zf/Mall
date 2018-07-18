<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17 0017
 * Time: 上午 11:21
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    protected $hidden = [
      'delete_time','id','user_id'
    ];
}