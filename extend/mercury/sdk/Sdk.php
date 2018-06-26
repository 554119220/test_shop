<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 9:33
 */

namespace mercury\sdk;


use app\common\traits\F;

class Sdk
{
    /**
     * @var array 请求信息
     * @var string $resource
     */
    public $data = [], $api, $response = '';

    public function __construct($api, array $data = [])
    {
        if (!isset($data['app_id']) || empty($data['app_id'])) $data['app_id']      = Config::APP_ID;
        if (!isset($data['app_key']) || empty($data['app_key'])) $data['app_key']   = Config::APP_KEY;
        $this->data = $data;
        $this->api  = $this->getApi($api);
    }

    /**
     * @title request
     * @return $this
     */
    public function request()
    {
        $this->data['sign'] = $this->_sign();
        $this->response = $this->curl($this->api, ['body' => $this->createRequestBody()]);
        return $this;
    }

    public static function instance($api, array $data = [])
    {
        return new static($api, $data);
    }

    /**
     * @title toArray
     * @return mixed
     */
    public function toArray()
    {
        return json_decode($this->response, true) ?? $this->response;
    }

    /**
     * @title getAccessToken
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken();
    }

    /**
     * @title _sign
     * @return string
     */
    protected function _sign()
    {
        $this->data['access_token'] = $this->accessToken();
        ksort($this->data);
        $str    = http_build_query($this->data) . Config::APP_SECRET;
        return md5($str);
    }

    /**
     * @title curl
     * @throws \Exception
     */
    protected function curl($url, $body)
    {
        if (strpos($url, 'http') !== 0)
            throw new \Exception('API不正确');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }

    /**
     * @title accessToken
     * @return string
     */
    protected function accessToken()
    {
//        $file   = realpath(__DIR__) . '/access_token.json';
//        $tmp    = json_decode(file_get_contents($file), true);
        $key    = F::getCacheName('sdk:access:token:' . config('url_domain_root'));
        $tmp    = F::redis()->get($key);
        $tmp    = $tmp ? json_decode($tmp, true) : '';
        if (!$tmp || $tmp['expire'] <= time()) {
            $url    = $this->getApi('/tools/v1/token/index');
            $data['app_id'] = Config::APP_ID;
            $data['app_key']= Config::APP_KEY;
            $data['sign']   = $this->accessTokenSign($data);
            $ret    = $this->curl($url, ['body' => json_encode($data)]);
            $ret    = json_decode($ret, true);
            if ($ret['code'] == 20000) {
                $tmp['token']   = $ret['data']['token'];
                $tmp['expire']  = $ret['data']['expire'];
//                file_put_contents($file, json_encode($tmp));
                F::redis()->setex($key, 7200, json_encode($tmp));
            } else {
                exit(json_encode($ret));
            }
        }
        return $tmp['token'];
    }

    /**
     * @title accessTokenSign
     * @request 请求参数
     * @param array $data
     * @return string
     */
    protected function accessTokenSign(array $data)
    {
        ksort($data);
        return md5(http_build_query($data) . Config::APP_SECRET);
    }

    /**
     * @title createApi
     * @param $api
     * @return string
     */
    protected function getApi($api)
    {
        if (strpos($api, '/') !== 0) $api = "/{$api}";
        return Config::APP_HOST . $api;
    }

    /**
     * @title createRequestBody
     * @return string
     */
    protected function createRequestBody()
    {
        return json_encode($this->data);
    }

    /**
     * @title __get
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        return $this->data[$name] ? : null;
    }

    /**
     * @title __set
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->data[$name] = $value;
    }

    public function __destruct()
    {
        $this->data = null;
        $this->api = null;
    }
}