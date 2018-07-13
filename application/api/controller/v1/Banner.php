<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/1
 * Time: 17:09
 */

namespace app\api\controller\v1;


use app\api\exception\BannerMissException;
use app\api\Validate\Banner\GetBannerValidate;
use app\api\model\Banner as BannerModel;

class Banner extends BaseController
{
    protected $failException = true;    //验证失败抛出异常
    protected $batchValidate = true;    //开启批量验证
    /**
     * 获取指定id的banner
     * @url /banner/:id
     * @http GET
     * @id banner的id
     */
    public function getBanner($id)
    {

        //进行Banner验证器验证
        $this->validate(compact('id'), GetBannerValidate::class);
        $banner = BannerModel::getBannerById($id);
        if($banner->isEmpty()){
            //若没找到资源,抛出自定义异常
            throw new BannerMissException();
        }
        return json(compact('banner'));
    }
}