<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/14
 * Time: 15:04
 */

namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden = ['delete_time','update_time','create_time'];
    public function img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

    /**
     * @return Category[]
     * @throws \think\exception\DbException
     */
    public static function allCategories(){
        return self::all([],'img');
    }
}