<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27 0027
 * Time: 9:25
 */

namespace mercury\weChat;


use app\common\traits\F;

class WeChat
{
    const APP_ID    = 'wxacf900cca5fd3d82';
    const TOKEN     = 'quBxp9mEnj6TyqPyU22xUpUmBzt7VkMf';
    const APP_SECRET= '4eb3c0c8f606eec38954c59d8d4c3e9c';
    protected $timestamp, $nonceStr, $jsApiList;

    public function __construct(array $jsApiList = [])
    {
        $this->timestamp    = time();
        $this->nonceStr     = F::createStr();
        $this->jsApiList    = $jsApiList ? : $this->apiList();
    }

    public static function instance(array $jsApiList = [])
    {
        return new static($jsApiList);
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getNonceStr()
    {
        return $this->nonceStr;
    }

    /**
     * @title getParams
     * @return array
     */
    public function getParams()
    {

        return [
            'appId'     => self::APP_ID,
            'timestamp' => $this->timestamp,
            'nonceStr'  => $this->nonceStr,
            'signature' => $this->getSign(),
            'echostr'   => uniqid(rand(), true),
            'jsApiList' => $this->parseApiList()
        ];
    }
    
    /**
     * @title getSign
     * @return string
     */
    public function getSign()
    {
        $tmpArr = [$this->timestamp, $this->nonceStr, self::TOKEN];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        return $tmpStr;
    }

    /**
     * @title apiList
     * @return array
     */
    protected function apiList()
    {
        return [
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'onMenuShareQZone',
            'startRecord',
            'stopRecord',
            'onVoiceRecordEnd',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'onVoicePlayEnd',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'translateVoice',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ];
    }

    public function parseApiList()
    {
        $apiList    = implode('","', $this->jsApiList);
        return "[\"{$apiList}\"]";
    }

    public function test()
    {

    }

    public function __call($method, $args = [])
    {
        $method = substr($method, 1);
        if (is_callable([$this, $method])) {
            return call_user_func_array([$this, $method], $args);
        }
        return false;
    }

    public static function __callStatic($method, $args = [])
    {
        $self   = new static([]);
        $method = substr($method, 1);
        if (is_callable([$self, $method])) {
            return call_user_func_array([$self, $method], $args);
        }
        return false;
    }
}