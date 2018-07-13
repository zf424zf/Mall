<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3 0003
 * Time: 上午 9:53
 */

namespace app\api\model;



class Banner extends BaseModel
{
    protected $hidden = ['delete_time','update_time'];

    //关联banner_item 一对多
    public function items(){
        return $this->hasMany('BannerItem','banner_id','id');
    }

    public static function getBannerById($id)
    {
        return self::with(['items','items.image'])->select($id);
//      $result = Db::query('select * from banner_item where banner_id = ?', [$id]);
//        $result = Db::table('banner_item')->where(function ($query) use($id) {
//            $query->where('banner_id',$id);
//        })->select();
//      return $result;
    }
}