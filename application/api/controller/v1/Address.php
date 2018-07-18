<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16 0016
 * Time: 下午 10:13
 */

namespace app\api\controller\v1;


use app\api\exception\RuleForbiddenException;
use app\api\exception\TokenException;
use app\api\exception\UserException;
use app\api\Validate\Address\AddressPostValidate;
use think\facade\Request;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;

class Address extends BaseController
{
    //设置前置方法
    public $beforeActionList = [
        'checkMinRuleUserScope' => ['only' => 'createOrUpdate']
    ];

    /**
     * 前置方法 用于检查当前用户的权限
     * @return bool
     * @throws RuleForbiddenException
     * @throws TokenException
     * @throws \think\Exception
     */


    public function createOrUpdate()
    {
        //获取post提交数据
        $params = Request::post();
        //实例化address验证器进行验证
        $addressPostValidate = new AddressPostValidate();
        $this->validate($params, get_class($addressPostValidate));

        //获取当前登录用户uid
        $uid = TokenService::getCurrentUid();
        //获取用户信息
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException();
        }
        //拿到通过验证器过滤之后的数据
        $ruleData = $addressPostValidate->getDataByRule($params);

        //获取用户地址
        $userAddress = $user->address;
        if (!$userAddress) {
            //如果没有数据则新增
            $user->address()->save($ruleData);
        } else {
            //否则做更新操作
            $user->address->save($ruleData);
        }
        return json(compact('user'));
    }
}