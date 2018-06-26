<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 11:06
 */

namespace app\common\behavior;

use app\common\traits\F;
use mercury\constants\Cache;
use mercury\constants\State;

/**
 * 整站配置信息
 *
 * Class Config
 * @package app\common\behavior
 */
class Config
{
    public function run(&$params)
    {
        $this->setConfig(F::getSiteConfig());
    }

    /**
     * 获取config
     *
     * @return false|mixed|\PDOStatement|string|\think\Collection
     */
    protected function getConfig()
    {
        $key    = F::getCacheName(Cache::CONFIG);
        $config = F::redis()->get($key);
        if (!$config) {
            $config = db('config_category')->where(['upid' => ['gt', State::STATE_DISABLED]])->select();
            if ($config) {
                foreach ($config as &$item) {
                    $item['config'] = unserialize($item['config']);
                }
            }
            F::redis()->set($key, serialize($config));
        }
        if (is_string($config)) $config = unserialize($config);
        return $config;
    }

    /**
     * 设置config
     *
     * @param $config
     */
    protected function setConfig($config)
    {
        $config = array_reduce($config, function (&$cfg, $val) {
            $cfg[$val['group_name']] = $val['config'];
            return $cfg;
        });
        config('site', $config);
    }
}