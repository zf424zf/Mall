<?php

namespace app\api\model;


class BannerItem extends BaseModel
{
    protected $hidden = ['img_id','banner_id','delete_time','update_time'];
    //关联Image
    public function image(){
        return $this->hasOne('Image','id','img_id');
    }

}
