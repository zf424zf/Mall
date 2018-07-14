<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/14
 * Time: 16:28
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\Validate\Token\TokenGetValidate;

class Token extends BaseController
{
    public function getToken($code)
    {
        $this->validate(compact('code'),TokenGetValidate::class);
        $userToken = new UserToken();
        $token = $userToken->get($code);
        return $token;
    }
}