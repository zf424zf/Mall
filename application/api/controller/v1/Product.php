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
        return json(compact('result'));
    }

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
}
