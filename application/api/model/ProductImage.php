<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16 0016
 * Time: 下午 9:18
 */

namespace app\api\model;


class ProductImage extends BaseModel
{

    protected $hidden = ['delete_time','product_id','img_id'];

    public function imageUrl(){
        return $this->belongsTo('Image','img_id','id');
    }
}