<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17 0017
 * Time: 上午 9:23
 */

namespace app\api\Validate\Address;


use app\api\Validate\BaseValidate;
use app\api\Validate\CommonValidate;

class AddressPostValidate extends BaseValidate
{
    use CommonValidate;

    protected $rule = [
        'name' => 'require|notEmpty',
        'mobile' => 'require|mobile',
        'province' => 'require|notEmpty',
        'city' => 'require|notEmpty',
        'country' => 'require|notEmpty',
        'detail' => 'require|notEmpty'
    ];

    protected $message = [
        'name.require' => 7001,//name参数必须
        'name.notEmpty' => 7002,//name参数不能为空
        'mobile.require' => 7003,//mobile参数必须
        'mobile.mobile' => 7004,//mobile参数必须为手机号码
        'province.require' => 7005,//province参数必须
        'province.notEmpty' => 7006,//province参数不能为空
        'city.require' => 7007,//city参数必须
        'city.notEmpty' => 7008,//city参数不能为空
        'country.require' => 7009,//country参数必须
        'country.notEmpty' => 7010,//country参数不能为空
        'detail.require' => 7011,//detail参数必须
        'detail.notEmpty' => 7012,//detail参数不能为空
    ];
}