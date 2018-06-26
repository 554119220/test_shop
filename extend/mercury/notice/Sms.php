<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 16:31
 */

namespace mercury\notice;


use app\common\traits\F;

class Sms extends Notice
{

    public function __construct(array $params, array $config = [])
    {
        parent::__construct('sms', $params, $config);
    }

    /**
     * 发送短信
     *
     * @return bool
     */
    public function send()
    {
        //前缀为144444的手机号码直接返回true
        if (strpos($this->getTo(), '144444') === 0) return true;
        if (false === $this->max()) return false;
        $host   = $this->config['host'];
        unset($this->config['host']);

        $this->append('mobile', $this->getTo());
        $this->append('content', $this->getContent());
        //带上签名
        $this->sign();
        $ret    = F::curl($host, $this->config, false);
        $ret    = simplexml_load_string($ret);
        if ($ret->returnstatus == 'Success') return true;
        /*
        $ret    = F::curl($host, $this->config, false);
        $ret = explode(',',$ret);
        if($ret[0] == 0) return true;
        */
        return false;
    }

    /**
     * 为短信加入签名
     */
    public function sign()
    {
        if (strpos($this->config['content'], $this->sign) === false)
            $this->config['content']    = "{$this->config['content']} {$this->sign}";
    }
}