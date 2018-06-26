<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 15:16
 */

namespace mercury\cache;


use PSRedis\Client;
use PSRedis\HAClient;
use PSRedis\MasterDiscovery;
use think\Exception;

/**
 * Class PSRedis
 * @package mercury\cache
 *
 * git https://github.com/jamescauwelier/PSRedis#auto-failover
 */
class PSRedis
{
    /**
     * @var string $master_discovery master discovery
     * @var array $sentinels 哨兵数组
     * @var string $master_name 默认master名字
     * @var array $config sentinel配置
     */
    protected $master_discovery, $sentinels = [], $master_name = 'master', $config = [];

    /**
     * 基本类型
     */
    const REDIS_BASIC   = 'basic';

    /**
     * 定制适配器类型
     */
    const REDIS_ADAPTER = 'adapter';

    /**
     * 故障转移类型
     */
    const REDIS_BACK_OFF= 'backOff';

    /**
     * 构造方法
     *
     * PSRedis constructor.
     * @param string $type 类型
     * @throws Exception
     */
    public function __construct($type = '')
    {
        $this->config   = config('redis.sentinel');
        if (empty($type)) $type = self::REDIS_BASIC;
        if (false == is_callable([$this, $type])) throw new Exception("方法{$type}不存在！");
        $this->$type();
    }

    /**
     * 基本类型
     */
    private function basic()
    {
        if (!empty($this->config)) {
            foreach ($this->config as $k => $v) {
                $this->sentinels[$k] = new Client($v['ip'], $v['port']);
            }
            $this->master_discovery = new MasterDiscovery($this->master_name);
            foreach ($this->sentinels as $v) {
                $this->master_discovery->addSentinel($v);
            }
        }
    }

    /**
     * 自动故障转移
     *
     * @return HAClient
     */
    public function autoFailOver()
    {
        $client = new HAClient($this->master_discovery);
        return $client;
    }

    /**
     * 获取master
     *
     * @return Client\ClientAdapter
     */
    public function getMaster()
    {
        return $this->master_discovery->getMaster();
    }

    /**
     * adapter 类型
     */
    private function adapter()
    {
        $factory    = new Client\Adapter\Predis\PredisClientCreator();
        if (!empty($this->config)) {
            foreach ($this->config as $k => $v) {
                $client_adapter = new Client\Adapter\PredisClientAdapter($factory, Client::TYPE_SENTINEL);
                $this->sentinels[$k]    = new Client($v['ip'], $v['port'], $client_adapter);
            }
            $this->master_discovery = new MasterDiscovery($this->master_name);
            foreach ($this->sentinels as $k => $v) {
                $this->master_discovery->addSentinel($v);
            }
        }
    }

    /**
     * 故障转移类型
     */
    private function backOff()
    {
        $config     = current($this->config);
        $sentinel   = new Client($config['ip'], $config['port']);
        $this->master_discovery = new MasterDiscovery($this->master_name);
        //创建一个退避策略（最初半秒，并在每次成功的尝试中增加一半退避）
        $back_off   = new MasterDiscovery\BackoffStrategy\Incremental(500, 1.5);
        //使用此退避策略获取master
        $back_off->setMaxAttempts(10);
    }
}