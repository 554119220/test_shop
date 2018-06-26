<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 8:57
 */

namespace mercury\rpc;

use app\common\traits\F;

/**
 * Class Client
 * @package Mercury\rpc
 *
 * RPC客户端
 */
class Client
{
    protected $client;
    protected $url;
    public static $instance;

    //YAR_OPT_CONNECT_TIMEOUT   1000
    //YAR_OPT_PACKAGER  json
    public function __construct($url)
    {
        if (strpos($url, 'http') === false) {
            if (strpos($url, '/') !== 0) $url = "/{$url}";
            //$domain = F::domain(config('rpc.domain'));
            $url =  F::domain(config('rpc.domain')) . $url;
        }
        $this->url  = $url;
    }

    /**
     * 获取实例
     *
     * @param $url
     * @return static
     */
    public static function getInstance($url)
    {
        return new static($url);
    }
    
    
    public function setOpt($var, $val)
    {
        return $this;
    }

    /**
     * call
     *
     * @param $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, array $args)
    {
        $this->url      = $this->stitchingParams($args, $method);
        $this->client   = new \Yar_Client($this->url);
        //绑定当前用户信息及app信息
        $tmp['data']    = $args[0];
        $args[0]    = array_merge($tmp, ['user' => session('user')], ['app' => ['app_id' => 1, 'app_key' => 123]]);
        $ret        = call_user_func_array([$this->client, $method], $args);
        return $ret;
    }

    /**
     * 拼接参数
     *
     * @param string|array $params
     * @return string
     */
    public function stitchingParams($params, $method)
    {
//        if (is_array($params)) {
//            $tmp    = [];
//            foreach ($params as $key => $param) {
//                if (is_array($param)) {
//                    foreach ($param as $k => $v) {
//                        if (is_array($v)) {
//                            foreach ($v as $kk => $vv) {
//                                $tmp[$kk] = $vv;
//                            }
//                        } else {
//                            $tmp[$k]    = $v;
//                        }
//                    }
//                } else {
//                    $tmp["tmp_{$key}"] = $param;
//                }
//            }
//            $params = $tmp;
//            unset($tmp);
//        }
        if (is_array($params) && !empty($params)) {
            $params = http_build_query(current($params));
        } else {
            $params = '';
        }
        //设置session id
        if (strpos($params, 'session_id') === false) $params .= "&session_id=" . session_id();
        //设置IP地址
        if (strpos($params, 'ip') === false) $params .= "&ip=" . request()->ip();

        //设置open
        if (session('user') && strpos($params, 'openid') === false) $params = "{$params}&openid=" . session('user.openid');

        if (strpos($params, 'method') === false) $params = "{$params}&method={$method}";

        if (strpos($this->url, '?') === false) {
            $this->url .= "?{$params}";
        } else {
            $this->url .= "&{$params}";
        }
        return $this->url;
    }
}