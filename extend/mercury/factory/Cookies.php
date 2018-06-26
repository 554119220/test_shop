<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/23 0023
 * Time: 15:33
 */

namespace mercury\factory;
use app\common\traits\F;
use mercury\constants\Cache;

/**
 * 生成唯一cookie值
 *
 * Class Cookie
 * @package mercury\factory
 */
class Cookies
{
    //protected $prefix   = 'ZR_';
    protected $user_id, $user_key;
    const COOKIE_NAME    = 'ID';
    protected static $instance;
    public function __construct($user_id = '')
    {
        if (!empty($user_id)) {
            $this->user_id = $user_id;
            $this->user_key= Cache::SEARCH_HISTORY_BY_USER . $this->user_id;
        }
    }


    /**
     * 获取实例
     *
     * @return Cookies
     */
    public static function instance($user_id = '')
    {
        if (false == self::$instance instanceof self) self::$instance = new self($user_id);
        return self::$instance;
    }

    /**
     * 设置COOKIE ID
     */
    public function setId()
    {
        if (!cookie(self::COOKIE_NAME)) {
            if ($this->user_key && F::redis()->exists($this->user_key)) {
                $value  = F::redis()->get($this->user_key);
            } else {
                $value  = strtoupper(md5(uniqid(rand() . microtime(true) . request()->ip(), true)));
            }
            cookie(self::COOKIE_NAME, $value);
        }
    }

    /**
     * 获取COOKIE ID
     *
     * @return mixed
     */
    public function getId()
    {
        if (!cookie(self::COOKIE_NAME)) $this->setId();
        return cookie(self::COOKIE_NAME);
    }
}