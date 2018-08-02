<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2 0002
 * Time: 下午 4:58
 */

namespace app\api\Validate\Order;


use app\api\Validate\BaseValidate;
use app\api\Validate\CommonValidate;

class OrderInfoValidate extends BaseValidate
{
    use CommonValidate;

    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'id.require' => 8401,
        'id.isPositiveInteger' => 8403
    ];
}