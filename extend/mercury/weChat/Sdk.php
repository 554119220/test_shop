<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27 0027
 * Time: 11:54
 */

namespace mercury\weChat;


use app\common\traits\F;
use mercury\constants\Cache;
use think\Exception;

class Sdk
{
    protected $api, $time, $nonceStr, $params = [], $error = false;
    public static $instance = [];
    public function __construct($api, $params = [])
    {
        $this->api  = $api;
        $this->time = time();
        $this->nonceStr = F::createStr();
        $this->params   = $params;
    }

    /**
     * @title instance
     * @return static
     */
    public static function instance($api, $params = [])
    {
        $p      = !empty($params) ? http_build_query($params) : '';
        $key    = md5("{$api}{$p}");
        if (!isset(self::$instance[$key]) || self::$instance[$key] instanceof self == false) {
            self::$instance[$key]  = new static($api, $params);
        }
        return self::$instance[$key];
    }
    
    /**
     * @title getAccessToken
     * @return string
     */
    public function getAccessToken()
    {
        try {
            $key    = F::getCacheName(Cache::WECHAT_ACCESS_TOKEN);
            $redis  = F::redis();
            $access_token   = $redis->get($key);
            if (!$access_token) {
                $appId  = WeChat::APP_ID;
                $secret = WeChat::APP_SECRET;
                $res    = $this->get("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$secret}");
                if (!$res) throw new Exception('执行get失败');
                $res    = json_decode($res, true);
                if (!$res || !isset($res['access_token'])) {
                    throw new Exception($res['errmsg']);
                }
                $access_token   = $res['access_token'];
                $redis->setex($key, $res['expires_in'], $access_token);
            }
            return $access_token;
        } catch (Exception $e) {
            $this->error    = $e->getMessage();
            return false;
        }
    }

    /**
     * @title getTicket
     * @return bool|string
     */
    public function getTicket()
    {
        $key    = F::getCacheName(Cache::WECHAT_TICKET);
        $redis  = F::redis();
        $ticket = $redis->get($key);
        if (!$ticket) {
            $access_token   = $this->getAccessToken();
            $res    = $this->get("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi");
            if (!$res) return false;
            $res    = json_decode($res, true);
            if (!$res || $res['errcode'] != 0) return false;
            $ticket = $res['ticket'];
            $redis->setex($key, $res['expires_in'], $ticket);
        }
        return $ticket;
    }

    /**
     * @title getJsApiSignature
     * @return string
     */
    public function getJsApiSignature()
    {
        $array  = [
            'noncestr'      => $this->nonceStr,
            'jsapi_ticket'  => $this->getTicket(),
            'timestamp'     => $this->time,
            'url'           => $this->params['url'],
        ];
        ksort($array);
        $str    = urldecode(http_build_query($array));
//        $array['str']   = $str;
        $str    = sha1($str);
//        $array['sha1']  = $str;
//        F::gearmanLogs('debug', $array);
        return $str;
    }

    /**
     * @title getJsApiParams
     * @return array
     */
    public function getJsApiParams()
    {
        return [
            'appId'     => WeChat::APP_ID,
            'timestamp' => $this->time,
            'nonceStr'  => $this->nonceStr,
            'signature' => $this->getJsApiSignature(),
//            'signature' => '',
            'jsApiList' => WeChat::_parseApiList(),
        ];
    }
    
    protected function request()
    {
        
    }

    protected function response()
    {
        
    }

    public function toArray()
    {
        
    }

    protected function post($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, []);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res    = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    protected function get($url)
    {
        return file_get_contents($url);
    }

    protected function stitchingParams()
    {
        
    }

    public function getError()
    {
        return $this->error;
    }
}