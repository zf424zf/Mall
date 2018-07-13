<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/1
 * Time: 18:12
 */

namespace app\api\Validate\Banner;


use app\api\Validate\CommonValidate;
use think\Validate;

class GetBannerValidate extends Validate
{
    use CommonValidate;

    protected $rule = [
        'id' => 'require|integer|isPositiveInteger'
    ];

    protected $message = [
        "id.require" => 3001,//banner id必须传递
        "id.integer" => 3002,//banner id必须是数值类型
        "id.isPositiveInteger" => 3003//banner id必须是正整数
    ];

}