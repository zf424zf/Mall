<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18 0018
 * Time: 下午 9:27
 */

namespace app\api\Validate\Order;


use app\api\exception\ValidateException;
use app\api\Validate\BaseValidate;
use app\api\Validate\CommonValidate;

class PlaceOrderValidate extends BaseValidate
{
    use CommonValidate;
    protected $rule = [
        'products' => 'require|checkProducts'
    ];

    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];

    protected $message = [
      'products.require' => 8000
    ];

    /**
     * 商品参数验证
     * @param $values
     * @return bool
     * @throws ValidateException
     */
    protected function checkProducts($values)
    {

        if (empty($values)) {
            throw new ValidateException(['商品列表不能为空'], 8001);
        }
        if (!is_array($values)) {
            throw new ValidateException(['商品必须是数组'], 8002);
        }

        foreach ($values as $value) {
            //单独检查商品列表下的每个商品的参数
            $this->checkProduct($value);
        }
        return true;
    }

    /**
     * 单个商品参数验证
     * @param $value
     * @throws ValidateException
     */
    protected function checkProduct($value)
    {
        //将自定义验证规则传入基础验证器
        $validate = new BaseValidate($this->singleRule);
        //进行参数验证并接受返回结果
        $result = $validate->check($value);
        if (!$result) {
            throw new ValidateException(['商品列表参数错误'], 8003);
        }
    }
}