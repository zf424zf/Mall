<?php

namespace app\api\model;


class Image extends BaseModel
{
    //隐藏字段
    protected $hidden = ['update_time', 'delete_time', 'from'];

    public function getUrlAttr($value,$data)
    {
        return $this->prefixImageUrl($value, $data);
//        return $data['from'] == 1 ? config('app.img_prefix') . $value : $value;
    }
}
