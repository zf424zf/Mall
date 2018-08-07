<?php

namespace app\api\model;


class Theme extends BaseModel
{

    protected $hidden = ['topic_img_id', 'head_img_id', 'delete_time', 'update_time'];

    //
    public function topicImage()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public function headImage()
    {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    public static function getThemeByIds($ids)
    {
        return self::with('topicImage,headImage')->select($ids);

    }

    public function products(){
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public static function getThemeWithProdect($id){
        return $themes = self::with('products,topicImage,headImage')->where('id','=',$id)->find();
    }
}
