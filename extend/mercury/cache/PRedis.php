<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8 0008
 * Time: 9:44
 */

namespace mercury\cache;


use Predis\Client;

/**
 * redis
 *
 * Class PRedis
 * @package mercury\cache
 */
class PRedis
{
    protected $config = [], $client, $sentinel;
    public function __construct()
    {
        $this->config   = config('redis');
        //$this->connect();
    }
    /**
     * 返回连接
     *
     * @return mixed
     */
//    public function client()
//    {
//        return $this->client;
//    }

    /**
     * 连接到redis服务器
     */
    public function client()
    {
        return new Client($this->parseSentinel(), [
            'replication'   => 'sentinel',
            'service'       => $this->config['service'],
            'parameters'    => [
                'password'  => $this->config['password']
            ]
        ]);
    }


    /**
     * 获取哨兵
     *
     * @return mixed
     */
    protected function getSentinels()
    {
        return $this->config['sentinel'];
    }

    /**
     * 获取redis服务器
     *
     * @return mixed
     */
    protected function getHosts()
    {
        return $this->config['host'];
    }

    /**
     * 解析sentinel配置
     *
     * @return array
     */
    protected function parseSentinel()
    {
        $sentinels  = [];
        foreach ($this->getSentinels() as $k => $v) {
            $sentinels[]    = "tcp://{$v['ip']}:{$v['port']}?timeout={$v['timeout']}";
        }
        return $sentinels;
    }

    /**
     * 断开连接
     */
    public function __destruct()
    {
        $this->client()->disconnect();
    }
}