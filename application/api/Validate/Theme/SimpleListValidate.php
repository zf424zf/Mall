<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12 0012
 * Time: 上午 11:41
 */

namespace app\api\Validate\Theme;


use app\api\Validate\CommonValidate;
use think\Validate;

class SimpleListValidate extends Validate
{
    use CommonValidate;

    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        "ids.require" => 4001,//ids必须传递
        "ids.checkIDs" => 4002//ids格式错误，必须为正整数且以;分隔
    ];
}