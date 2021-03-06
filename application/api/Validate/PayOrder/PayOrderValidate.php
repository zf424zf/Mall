<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24 0024
 * Time: 下午 12:31
 */

namespace app\api\Validate\PayOrder;


use app\api\Validate\BaseValidate;
use app\api\Validate\CommonValidate;

class PayOrderValidate extends BaseValidate
{
    use CommonValidate;

    protected $rule = [
        'id' => 'require|integer|isPositiveInteger'
    ];

    protected $message = [
        "id.require" => 8401,//id必须传递
        "id.integer" => 8402,// id必须是数值类型
        "id.isPositiveInteger" => 8403// id必须是正整数
    ];

}