<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12 0012
 * Time: 下午 8:39
 */

namespace app\api\Validate\Theme;


use app\api\Validate\CommonValidate;
use think\Validate;

class ThemeValidate extends Validate
{

    use CommonValidate;
    protected $rule = [
        'id' => 'require|integer|isPositiveInteger'
    ];

    protected $message = [
        "id.require" => 4003,//id必须传递
        "id.integer" => 4004,//id必须是整形
        "id.isPositiveInteger" => 4005 //id必须为正整数
    ];
}