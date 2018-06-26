<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 15:54
 */

namespace mercury\sdk;


class Oauth extends Sdk
{
    const ZRMALL_OAUTH_HOST = 'http://oauth2.zrmall.com';

    public function __construct($api = '')
    {
        parent::__construct('');
        $this->api  = $api;
    }

    /**
     * @title getCode
     * @param $callback
     */
    public function getCode($callback)
    {
        $this->data['ret_url']  = urlencode($callback);
        $this->data['sign']     = $this->_sign();
        $url    = self::ZRMALL_OAUTH_HOST .'/?'. http_build_query($this->data);
        header("Location: {$url}");
        exit();
    }

    /**
     * @title 获取openid
     * @param $code
     * @return bool
     */
    public function getUserToken($code)
    {
        $this->data['code'] = $code;
        $this->data['sign'] = $this->_sign();
        $url    = self::ZRMALL_OAUTH_HOST . $this->api;
        $this->response = $this->curl($url, $this->data);
        $ret    = $this->toArray();
        if ($ret['code'] != 20000) {
            return false;
        }
        return $ret['data']['openid'];
    }

    /**
     * @title 获取用户信息
     * @param $openid
     * @return bool
     */
    public function getUserInfo($openid)
    {
        $this->data['openid']   = $openid;
        $this->data['sign']     = $this->_sign();
        $url    = self::ZRMALL_OAUTH_HOST . $this->api;
        $this->response = $this->curl($url, $this->data);
        $ret    = $this->toArray();
        if ($ret['code'] != 20000) {
            return false;
        }
        return $ret['data'];
    }
}