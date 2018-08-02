<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/23 0023
 * Time: 下午 9:37
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $autoWriteTimestamp = true;
    //订单隐藏字段
    protected $hidden = ['user_id', 'delete_time', 'update_time'];

    const TO_BE_PAID = 1;//待支付

    const PAID = 2;//已支付

    const DELIVERED = 3;//已发货

    const PAID_BUT_NO_STOCK = 4;//已支付但是库存不够

    /**
     * 转换snap_items属性为json对象
     * @param $value
     * @return mixed|null
     */
    public function getSnapItemsAttr($value)
    {
        if (empty($value)) {
            return null;
        }
        return json_decode($value);
    }

    /**
     * 转换snap_address属性为json对象
     * @param $value
     * @return mixed|null
     */
    public function getSnapAddressAttr($value)
    {
        if (empty($value)) {
            return null;
        }
        return json_decode($value);
    }

    /**
     * 获取订单列表查询器
     * @param $uid
     * @param int $page
     * @param int $pagesize
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public static function getList($uid, $page = 1, $pagesize = 15)
    {
        $paginate = self::where('user_id', '=', $uid)
            ->order('create_time desc')
            ->paginate(['page' => $page, 'list_rows' => $pagesize]);
        return $paginate;
    }
}