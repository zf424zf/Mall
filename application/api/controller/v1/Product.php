<?php

namespace app\api\controller\v1;


use app\api\exception\ProductException;
use app\api\Validate\Product\ProductInCategoryValidate;
use app\api\Validate\Product\RecentValidate;
use app\api\model\Product as ProductModel;
class Product extends BaseController
{
    public function recent($count = 15)
    {
        $this->validate(compact('count'), RecentValidate::class);
        $recents = ProductModel::getRecent($count);
        if ($recents->isEmpty()) {
            throw new ProductException();
        }
        $result = $recents->hidden(['summary']);
        return json($result);
    }

    /**
     * 根据分类查找商品
     * @param $id
     * @return \think\response\Json
     * @throws ProductException
     * @throws \app\api\exception\ValidateException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function productInCategory($id)
    {
        $this->validate(compact('id'),ProductInCategoryValidate::class);
        $result = ProductModel::getInCategory($id);
        if($result->isEmpty()){
            throw new ProductException();
        }
        $result = $result->hidden(['summary']);
        return json(compact('result'));
    }

    /**
     * 根据商品id获取商品详情
     * @param $id
     * @return \think\response\Json
     * @throws ProductException
     * @throws \app\api\exception\ValidateException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function info($id){
        $this->validate(compact('id'),ProductInCategoryValidate::class);
        $result = ProductModel::getInfoById($id);
        if(!$result){
            throw new ProductException();
        }
        return json(compact('result'));
    }
}
