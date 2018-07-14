<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/7/14
 * Time: 16:32
 */

namespace app\api\service;


use think\Exception;

class UserToken
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
            $err = array_key_exists('errorcode', $result);
            if ($err) {

            } else {

            }
        }
    }
}