<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/1
 * Time: 18:38
 */

namespace app\api\Validate;


trait CommonValidate
{
    protected function isPositiveInteger($value, $rule = '', $data = [], $field = '', $desc = '')
    {
        return ($value + 0) > 0 && is_int($value + 0) ? true : false;
    }

    protected function checkIDs($value, $rule, $data = [], $field = '', $desc = '')
    {

        $values = explode(',', $value);
        foreach ($values as $id) {

            if (!($this->isPositiveInteger($id) === true)) {
                return false;
            }
        }
        return true;
    }

    protected function maxValue($value, $rule = '', $data = [], $field = '', $desc = ''){
        return ($value + 0) > ($rule + 0) ? false : true;
    }
}