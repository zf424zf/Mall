<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17 0017
 * Time: 上午 9:46
 */

namespace app\api\Validate;


use app\api\exception\ValidateException;
use think\Validate;

class BaseValidate extends Validate
{
    use CommonValidate;
    public function getDataByRule(array $params)
    {
        if (array_key_exists('uid',$params) || array_key_exists('user_id',$params)) {
            throw  new ValidateException([
                'msg' => '含有非法参数uid或者user_id'
            ]);
        }
        $data = [];
        foreach ($this->rule as $key => $value) {
            $data[$key] = $params[$key];
        }
        return $data;
    }
}