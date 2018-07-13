<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/1
 * Time: 17:36
 */

namespace app\api\Validate;


use think\Validate;

class TestValidate extends Validate
{
    protected $rule = [
        'name'  => 'require|max:10',
        'email' => 'email'
    ];
}