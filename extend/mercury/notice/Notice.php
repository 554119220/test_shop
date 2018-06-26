<?php
/**
 * Created by PhpStorm.
 * User: Mercury
 * Date: 2017/10/27 0027
 * Time: 16:26
 */

namespace mercury\notice;


use app\common\traits\F;
use think\Cache;

abstract class Notice
{
    /**
     * @var 接收对象，通知内容，是否异步，通知周期，接收参数，配置信息
     */
    //protected $to, $content, $is_async, $expire, $params = [], $config = [];
    /**
     * @var array $params 参数
     *              to  接收人
     *              content 发送内容
     * @var array $config 配置参数
     * @var string $type
     *              email
     *              sms
     * @var string $code 验证码
     */
    protected $params = [], $config = [], $type, $code = '', $sign = '【百望商城】';
    const SEND_EMAIL_MAX    = 50;   #   最大发送邮件数量，针对接收方
    const SEND_SMS_MAX      = 20;   #   最大发送短信数量，针对接收方
    /**
     * @var object $instances 对象
     */
    public static $instances = [];
    public function __construct($type, array $params, array $config = [])
    {
        if (!empty($config)) {
            $this->config = $config;
        } else {
            $this->config = config("notice.{$type}");
        }
        $this->type     = $type;
        $this->params   = $params;
    }

    /**
     * 获取对象
     *
     * @param $type
     * @param array $params
     * @return 对象
     */
    public static function getInstance($type, array $params, array $config = [])
    {
        $class  = __NAMESPACE__ . '\\' . ucfirst($type);
        $key    = md5(json_encode($params,true) . json_encode($config, true));
        if (!isset(self::$instances[$key]) || self::$instances[$key] instanceof $class == false) {
            self::$instances[$key] = new $class($params, $config);
        }
        return self::$instances[$key];
    }

    /**
     * 获取接收的对象
     *
     * @return mixed|string
     */
    public function getTo()
    {
        return isset($this->params['to']) ? $this->params['to'] : $this->config['to'];
    }

    /**
     * 获取发送的内容
     *
     * @return mixed
     */
    public function getContent()
    {
        $this->params['content']    = isset($this->params['content']) ? $this->params['content'] : false;
        if ($this->params['content'] && strpos($this->params['content'], '{code}') !== false) {
            if (empty($this->code)) $this->createCode();
            $this->params['content'] = str_replace('{code}', $this->code, $this->params['content']);
        }
        return $this->params['content'];
    }

    /**
     * 获取延时
     *
     * @return mixed
     */
    public function getExpire()
    {
        return $this->params['expire'];
    }


    /**
     * 获取是否异步处理
     *
     * @return bool|int
     */
    public function getIsAsync()
    {
        return isset($this->params['is_async']) ? intval($this->params['is_async']) : false;
    }

    /**
     * 加入字段
     *
     * @param $name
     * @param $value
     */
    public function append($name, $value)
    {
        $this->config[$name] = $value;
    }

    /**
     * 设置code
     *
     * @param int $length
     * @param int $expire
     * @param array $cache_name
     * @return $this
     */
    public function createCode($length = 6, $expire = 180, $cache_name = [])
    {
        if (empty($cache_name)) $cache_name = ['notice' => $this->type, 'to' => $this->getTo()];
        $cache_name = F::getCacheName($cache_name);
        $this->code = Cache::get($cache_name);
        if (!$this->code) {
            for ($i = 0; $i < $length; $i++) {
                $this->code .= rand(0, 9);
            }
            Cache::set($cache_name, $this->code, $expire);
        }
        return $this;
    }

    /**
     * 验证码判断
     *
     * @param $type
     *          email
     *          sms
     * @param $to
     *          email
     *          mobile
     * @return mixed
     */
    public function checkCode($type, $to)
    {
        $cache_name = F::getCacheName(['notice' => $type, 'to' => $to]);
        return Cache::get($cache_name);
    }

    /**
     * 获取验证码
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 最大发送数量
     *
     * @return bool|int
     */
    public function max()
    {
        #   最大发送数量判断
        $to     = md5($this->getTo());
        switch ($this->type) {
            case 'sms':
                $key    = F::getCacheName(\mercury\constants\Cache::NOTICE_MAX_SMS . $to);
                break;
            default:
                $key    = F::getCacheName(\mercury\constants\Cache::NOTICE_MAX_EMAIL . $to);
                break;
        }
        if (self::SEND_EMAIL_MAX < F::redis()->get($key)) return false;
        if (false == F::redis()->exists($key)) {
            return F::redis()->setex($key, 86400, 1);
        } else {
            return F::redis()->incr($key);
        }
    }
    
    /**
     * 发送
     *
     * @return mixed
     */
    abstract public function send();
}