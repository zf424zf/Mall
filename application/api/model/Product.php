<?php

namespace app\api\model;


class Product extends BaseModel
{

    protected $hidden = [
        'delete_time', 'update_time', 'main_img_id', 'pivot', 'from', 'category_id', 'create_time'
    ];

    public function images()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    //
    public function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImageUrl($value, $data);
    }

    /**
     * @param $count
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getRecent($count)
    {
        $recents = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $recents;
    }

    /**
     * @param $categoryId
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getInCategory($categoryId)
    {
        return self::where('category_id', '=', $categoryId)->select();
    }

    /**
     * 根据id查找商品
     * @param $id
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getInfoById($id)
    {
        $product = self::with(['images'=>function($query){
            //按照images的order 正序排序
            $query->with(['imageUrl'])->order('order','asc');
        }])->with(['properties'])->find($id);
        return $product;
    }

    public static function getListByPidArr(array $pidArr){
        return self::select($pidArr)->visible(['id', 'price', 'stock', 'name', 'main_img_url'])->toArray();
    }
}
