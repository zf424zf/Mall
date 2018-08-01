<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/1 0001
 * Time: 下午 11:18
 */

namespace app\api\Validate\Order;


use app\api\Validate\BaseValidate;
use app\api\Validate\CommonValidate;

class OrderListValidate extends BaseValidate
{
    use CommonValidate;
    protected $rule = [
        'page' => 'isPositiveInteger',
        'pagesize' => 'isPositiveInteger'
    ];

    protected $message = [
        'page.isPositiveInteger' => 1004,
        'pagesize.isPositiveInteger' => 1005,
    ];
}