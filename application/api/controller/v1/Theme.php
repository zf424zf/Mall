<?php

namespace app\api\controller\v1;

use app\api\exception\ThemeException;
use app\api\Validate\Theme\SimpleListValidate;
use app\api\model\Theme as ThemeModel;
use app\api\Validate\Theme\ThemeValidate;

class Theme extends BaseController
{

    /**
     * @url /theme?ids=id1,id2,id3
     * @return
     */

    public function getSimpleList($ids = '')
    {
        //验证ids格式和值
        $this->validate(compact('ids'), SimpleListValidate::class);
        //分割ids字符串为数组
        $ids = explode(',', $ids);
        //查找theme
        $result = ThemeModel::getThemeByIds($ids);
        if ($result->isEmpty()) {
            //找不到指定ids的theme 抛出异常
            throw new ThemeException();
        }
        return json($result);
    }

    public function getOne($id)
    {
        $this->validate(compact('id'),ThemeValidate::class);
        $result = ThemeModel::getThemeWithProdect($id);
        if($result->isEmpty()){
            throw new ThemeException();
        }
        return json(compact('result'));
    }
}
