<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/23 0023
 * Time: 15:47
 */

namespace mercury\factory;
use app\common\traits\F;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\ResponseException;

/**
 * 历史记录
 *
 * Class SearchHistory
 * @package mercury\factory
 */
class SearchHistory
{
    /**
     * @var mixed 唯一标识
     */
    protected $identity, $key, $user_key, $user_id;
    protected static $instance;
    const ROWS  = 9;
    const KEYWORDS_MAX_LENGTH   = 15;
    public function __construct($user_id = '', $identity = '')
    {
        if (!empty($user_id)) {
            $this->user_id  = $user_id;
            $this->user_key = Cache::SEARCH_HISTORY_BY_USER . $this->user_id;
        }
        $this->identity = $this->getIdentity();
        $this->key      = Cache::SEARCH_HISTORY . $this->identity;
    }

    /**
     * 获取实例
     *
     * @param string $identity
     * @return SearchHistory
     */
    public static function instance($user_id = '', $identity = '')
    {
        if (false == self::$instance instanceof self) self::$instance = new self($user_id, $identity);
        return self::$instance;
    }

    /**
     * 记录历史记录
     *
     * @param $q
     */
    public function put($q)
    {
        $q      = trim($q);
        $flag   = F::redis()->zrank($this->key, $q);
        if (false == $flag) {
            $score  = F::redis()->zcard($this->key);
            F::redis()->zadd($this->key, ++$score, $q);
        }
        $this->setHotKeywords($q);
    }

    /**
     * 获取历史记录
     *
     * @return array
     */
    public function get()
    {
        return F::redis()->zRevRange($this->key, 0, self::ROWS);
    }

    /**
     * 删除历史搜索记录
     *
     * @return array|int
     */
    public function flush()
    {
        try {
            $key    = [$this->key];
            if (false == F::redis()->del($key)) throw new ResponseException(Code::CODE_OTHER_FAIL, '已无历史记录');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 绑定用户ID
     *
     * @return $this
     */
    public function bindUserId()
    {
        if (!empty($this->identity) && false == F::redis()->exists($this->user_key)) {
            F::redis()->set($this->user_key, $this->identity);
        }
        return $this;
    }

    /**
     * 获取唯一标识
     *
     * @return mixed
     */
    public function getIdentity()
    {
        F::redis()->exists('zr:table:area:id:5');
        if (!empty($this->user_id) && F::redis()->exists($this->user_key)) {
//            if (F::redis()->exists($this->user_key))
                return F::redis()->get($this->user_key);
        }
        return Cookies::instance()->getId();
    }

    /**
     * +分
     *
     * @param $q
     */
    private function setHotKeywords($q)
    {
//        $key    = md5(trim($q));
        $key    = Cache::SEARCH_KEYWORDS;
        if (mb_strlen($q) > self::KEYWORDS_MAX_LENGTH) return;
        F::redis()->zincrby($key, 1, $q);
    }
}