<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:58
 */

namespace mercury\auth\api;
use app\common\traits\F;
use crypt\Crypt;
use think\Request;

/**
 * Class AuthApi
 * @package mercury\auth\api
 *
 * api验证    module,controller,action,session_id
 */
class AuthApi
{

    /**
     * @var string 加密解密的盐值
     */

    /**
     * @var string $session_id
     * @var string $api path info
     */
    protected $session_id, $api, $token;

    /**
     * @var object $instance 对象
     */
    protected static $instance, $session_token;

    /**
     * AuthApi constructor.
     * @param $api
     */
    public function __construct($api, $rand_str = '')
    {
        $this->api  = $api;
        if (!self::$session_token && $rand_str == '') self::$session_token = Request::instance()->token();
        $this->session_id = !empty($rand_str) ? $rand_str : self::$session_token;
    }

    /**
     * 获取实例
     *
     * @param $api
     * @return static
     */
    public static function getInstance($api, $rand_str = '')
    {
        return new static($api, $rand_str);
    }
    
    /**
     * 验证access token
     *
     * @param $accessToken
     * @return bool
     */
    public function verifyAccessToken($accessToken)
    {
        //先解密api
        $this->api  = $this->decryptionApi();
        return $this->createAccessToken() == $accessToken;
    }

    /**
     * 生成access token
     *
     * @return string
     */
    public function createAccessToken()
    {
        //secret，
        $str    = "{$this->session_id}{$this->api}{secret}";
        return F::createStr($str);
    }

    /**
     * 加密API
     *
     * @return string
     */
    public function encryptionApi()
    {
        return Crypt::encrypt($this->api, $this->session_id);
    }

    /**
     * 解密api
     *
     * @return string
     */
    public function decryptionApi()
    {
        if (F::redis()->exists($this->api)) return F::redis()->get($this->api);
        return Crypt::decrypt($this->api, $this->session_id);
    }

    /**
     * 获取api
     *
     * @return mixed
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * 创建headers
     *
     * @return string
     */
    public function createHeaders()
    {
        $tmp    = [
            'Content-AccessToken'   => $this->createAccessToken(),
            'Content-Redirect'      => $this->encryptionApi(),
        ];
        $this->cache();
        return json_encode($tmp);
    }

    /**
     * 缓存
     *
     * @return int
     */
    public function cache()
    {
        return  F::redis()->setex($this->encryptionApi(), 3600, $this->api);
    }
}