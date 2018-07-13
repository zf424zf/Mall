<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12 0012
 * Time: 下午 9:46
 */

namespace app\api\Validate\Product;


use app\api\Validate\CommonValidate;
use think\Validate;

class RecentValidate extends Validate
{
    use CommonValidate;

    protected $rule = [
        'count' => 'integer|isPositiveInteger|maxValue:20'
    ];

    protected $message = [
        'count.integer' => 5001, //count必须是数值类型
        'count.isPositiveInteger' => 5002, //count必须是正整数
        'count.maxValue' => 5003//count最大值为20
    ];
}