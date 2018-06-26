<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/1 0001
 * Time: 15:45
 */

namespace app\common\traits;

/**
 * Trait Cache
 * @package app\common\method
 *
 * 缓存相关
 */
trait Cache
{
    /**
     * @param string|array $cache_name string || array
     * @return string
     */
    public static function getCacheName($cache_name)
    {
        if (is_array($cache_name))
            $cache_name = str_replace('=', ':', http_build_query($cache_name, '', ':'));
        return \mercury\constants\Cache::CACHE_NAME_PREFIX . $cache_name;
    }

    /**
     * 获取配置信息
     *
     * @param bool $reset   是否重置缓存
     * @return mixed
     */
    public static function cacheConfig($reset = false)
    {
        $key    = self::getCacheName(['table' => 'config']);
        $data   = self::redis()->get($key);
        if (!$data || true == $reset) {
            $data   = db('config_category')
                ->where(['status' => 1, 'upid' => ['neq', 0]])
                ->order('sort asc')
                ->field('id,group_name,config')
                ->select();
            if ($data) {
                $config = [];
                foreach ($data as $v) {
                    $config[$v['group_name']]   = unserialize($v['config']);
                }
                $data   = serialize($config);
                self::redis()->set($key, $data);
            }
        }
        return unserialize($data);
    }

    /**
     * 用户缓存
     *
     * @param int $uid
     * @param bool $reset 是否重置缓存
     * @return array|bool|false|\PDOStatement|string|\think\Model
     */
    public static function cacheUser($uid, $reset = false)
    {
        $key    = self::getCacheName(['table' => 'user', 'id' => $uid]);
        $user   = F::redis()->hGetAll($key);
        if (!$user || true == $reset) {
            $user   = db('user')->where(['user_ID' => $uid])->find();
            if (!$user) return false;
            F::redis()->hMset($key, $user);
        }
        return $user;
    }

    /**
     * 清除缓存
     *
     * @param string $prefix    前缀
     * @return bool
     */
    public static function cacheClearUser($prefix = '')
    {
        $key    = $prefix ?: self::getCacheName(['table' => 'user']);
        $key   .= '*';
        $keys   = F::redis()->keys($key);
        if (!$keys) {
            foreach ($keys as $v) {
                F::redis()->del($v);
            }
        }
        return true;
    }
}