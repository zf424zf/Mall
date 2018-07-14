<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/14
 * Time: 15:32
 */

namespace app\api\Validate\Product;


use app\api\Validate\CommonValidate;
use think\Validate;

class ProductInCategoryValidate extends Validate
{
    use CommonValidate;

    protected $rule = [
        'id' => 'require|integer|isPositiveInteger'
    ];

    protected $message = [
        'id.require' => 6001,//category id 必须传递
        'id.integer' => 6002,//category id必须是数值类型
        'id.isPositiveInteger' => 6003//category id必须是正整数
    ];


}