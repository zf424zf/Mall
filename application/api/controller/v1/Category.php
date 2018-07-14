<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/14
 * Time: 15:03
 */

namespace app\api\controller\v1;

use app\api\exception\CategoryException;
use app\api\model\Category as CategoryModel;
class Category extends BaseController
{

    public function getAllCategory()
    {
        $result = CategoryModel::allCategories();
        if($result->isEmpty()){
            throw new CategoryException();
        }
        return json(compact('result'));
    }
}