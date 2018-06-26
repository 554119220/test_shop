<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28 0028
 * Time: 15:06
 */

namespace mercury\async\beanstalkd;
use app\common\traits\F;
use mercury\constants\Cache;


/**
 * Class Persistence
 * @package app\common
 * @title 持久化队列
 */
class Persistence
{
    protected $data, $tube, $job_id, $redis;
    public function __construct($tube, $data, $job_id)
    {
        $this->tube = $tube;
        $this->data = $data;
        $this->job_id   = $job_id;
        $this->redis    = F::redis();
    }

    /**
     * @title addPersistenceJob 添加到持久化
     * @return mixed
     */
    public function addPersistenceJob()
    {
        $data['tube']   = $this->tube;
        $data['job_id'] = $this->job_id;
        $data['create_time']    = time();
        return $this->redis->set($this->cacheName(), serialize($data));
    }

    /**
     * @title removePersistenceJob 移除持久化
     * @return bool
     */
    public function removePersistenceJob()
    {
        $key    = $this->cacheName();
        if (!$this->redis->exists($key)) return false;
        return $this->redis->del([$key]);
    }

    /**
     * @title getPersistenceJobData 获取持久化数据
     * @return bool|mixed|string
     */
    public function getPersistenceJobData()
    {
        $key    = $this->cacheName();
        if (!$this->redis->exists($key)) return false;
        $data   = $this->redis->get($key);
        if (!$data) return false;
        $data   = unserialize($data);
        if (!$data) return false;
        return $data;
    }

    /**
     * @title cacheName 获取缓存名称
     * @return string
     */
    public function cacheName()
    {
        //移除两个可变值
        $data   = $this->data;
        unset($data['delay'], $data['method'], $data['exec_num']);
        //查看是否有入列,如果redis里面有这个key则直接删除并且删除之前的job
        $prev   = Cache::QUEUE_PERSISTENCE_ROW;
        $key    = str_replace('=', ':', http_build_query($data, '', ':'));
        return "{$prev}{$this->tube}:{$key}";
    }

    /**
     * @title setJobId
     * @param $job_id
     */
    public function setJobId($job_id)
    {
        $this->job_id   = $job_id;
    }

    public function __set($name, $value)
    {
        return $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name ? : null;
    }
}