<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    protected $hidden = ['delete_time','update_time'];
    //
    protected function prefixImageUrl($value,$data)
    {
        //当from为1的时候 返回拼接好的url地址
        return $data['from'] == 1 ? config('app.img_prefix') . $value : $value;
    }
}
