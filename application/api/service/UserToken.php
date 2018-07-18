<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/14
 * Time: 16:32
 */

namespace app\api\service;


use app\api\exception\TokenException;
use app\api\exception\WeiXinException;
use think\Exception;
use app\api\model\User as UserModel;
use Scope as ScopeApi;
class UserToken extends Token
{
    protected $code;
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code)
    {
        //初始化微信公众接口参数
        $this->code = $code;
        $this->wxAppId = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppId, $this->wxAppSecret, $this->code);
    }

    /**
     * 获取wx openID以及session_key
     * @throws Exception
     */
    public function get()
    {
        //curl获取微信接口返回信息
        $contents = curl_get($this->wxLoginUrl);
        $result = json_decode($contents, true);
        //如果获取为空，则视为微信服务器错误
        if (empty($result)) {
            throw new Exception("微信内部错误，获取异常！");
        } else {
            //如果包含微信返回的错误码，则表示获取失败
            $err = array_key_exists('errcode', $result);
            if ($err) {
                //抛出自定义异常
                $this->processWXLoginError($result);
            } else {
                return $this->handleWXInfo($result);
            }
        }
    }

    /**
     * 处理获取到的微信获取openid接口信息
     * @param array $result
     */
    private function handleWXInfo(array $result)
    {
        $openId = $result['openid'];
        //根据openID查找用户
        $user = UserModel::getUserByOpenID($openId);
        if ($user) {
            $uid = $user->id;
        } else {
            //若找不到则创建新用户
            $uid = $this->createUser($openId);
        }
        //获取初始化后的用户缓存信息
        $cacheInfo = $this->initCacheInfo($uid, $result);
        $token = $this->setUserCache($cacheInfo);
        return $token;
    }

    /**
     * 抛出微信获取异常
     * @param array $result
     * @throws WeiXinException
     */
    private function processWXLoginError(array $result)
    {
        throw new WeiXinException([
            'msg' => $result['errmsg'],
            'errorCode' => $result['errcode']
        ]);
    }

    /**
     * 根据openid创建新用户
     * @param $openID
     * @return mixed
     */
    private function createUser($openID)
    {
        $user = UserModel::create([
            'openid' => $openID
        ]);
        return $user->id;
    }

    /**
     * 初始化用户缓存数据
     * @param $uid
     * @param array $result
     * @return array $cacheInfo
     */
    private function initCacheInfo($uid, array $result)
    {
        $cacheInfo = $result;
        $cacheInfo['uid'] = $uid;
        //设置当前用户权限 默认16
        $cacheInfo['scope'] = ScopeApi::APP_User;
        return $cacheInfo;
    }

    /**
     * 将初始化好的用户信息放入缓存
     * @param array $cacheInfo
     */
    private function setUserCache(array $cacheInfo)
    {
        $key = self::generateToken();
        $value = json_encode($cacheInfo);
        $expire_in = config('app.app_token_expire');
        //存入缓存 默认文件缓存
        $request = cache($key, $value, $expire_in);
        if (!$request) {
            throw  new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 1001
            ]);
        }
        return $key;
    }
}