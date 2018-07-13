<?php

namespace app\api\controller\v1;



use app\api\exception\ProductException;
use app\api\Validate\Product\RecentValidate;

class Product extends BaseController
{
    public function recent($count = 15){
        $this->validate(compact('count'), RecentValidate::class);
        $recents = \app\api\model\Product::getRecent($count);
        if($recents->isEmpty()){
            throw new ProductException();
        }
        $result = $recents->hidden(['summary']);
        return json(compact('result'));
    }
}
