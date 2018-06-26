<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27 0027
 * Time: 10:33
 */

namespace mercury\weChat;


use app\common\traits\F;

class Sign
{
    protected $sign, $time, $nonce, $get;
    public function __construct(array $get)
    {
        $this->sign = $get['signature'];
        $this->time = $get['timestamp'];
        $this->nonce= $get['nonce'];
        $this->get  = $get;
    }

    public static function instance(array $get)
    {
        return new static($get);
    }

    /**
     * @title check
     * @return bool
     */
    public function check()
    {
        $this->get['localSign'] = $this->sign();
        $this->get['type']      = 'weChat';
        F::gearmanLogs('debug', $this->get);
        return $this->sign == $this->sign();
    }

    /**
     * @title sign
     * @return string
     */
    public function sign()
    {
        $tmpArr = [$this->time, $this->nonce, WeChat::TOKEN];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        return sha1($tmpStr);
    }

    public function getEchoStr()
    {
        return $this->get['echostr'];
    }
}