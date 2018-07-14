<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/14
 * Time: 16:22
 */

namespace app\api\Validate\Token;


use app\api\Validate\CommonValidate;
use think\Validate;

class TokenGetValidate extends Validate
{
    use CommonValidate;

    protected $rule = [
        'code' => 'require|notEmpty'
    ];

    protected $message = [
        'code.require' => 9000,//code必须传递
        'code.notEmpty' => 9001//code不能为空
    ];
}