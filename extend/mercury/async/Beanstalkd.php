<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30 0030
 * Time: 13:37
 */

namespace mercury\async;


use app\common\traits\F;
use mercury\async\beanstalkd\Persistence;
use mercury\constants\Cache;
use mercury\constants\State;
use Pheanstalk\Exception\ServerException;
use Pheanstalk\Pheanstalk;
use think\Exception;

/**
 * Class Beanstalkd
 * @package app\common\async
 *
 * 消息队列
 */
class Beanstalkd
{
    /**
     * @var object $handler Pheanstalk句柄
     * @var string $tube   管道
     */
    protected $handler, $tube, $redis;
    const ERP_PAY   = 'erp_pay';
    const ERP_REFUND= 'erp_refund';

    /**
     * @var $instance object 当前对象实例
     */
    protected static $instance;

    /**
     * Beanstalkd constructor.
     * @param $tube
     * @param array $config
     * @throws Exception
     */
    public function __construct($tube, array $config = [])
    {
        if (!isset($config['host']) || empty($config['host'])) $config['host'] = config('beanstalkd.host');
        if (!isset($config['port']) || empty($config['port'])) $config['port'] = config('beanstalkd.port');
        if (!isset($config['timeout']) || empty($config['timeout'])) $config['timeout'] = config('beanstalkd.timeout');
        if (!isset($config['persistent']) || empty($config['persistent'])) $config['persistent'] = config('beanstalkd.persistent');
        $this->tube     = $tube;
        $this->handler  = new Pheanstalk($config['host'], $config['port'], $config['timeout'], $config['persistent']);
        //判断是否能够链接到host,连接失败的话则持久化数据？
//        if (false === $this->handler->getConnection()->isServiceListening()) {
//            //邮件通知程序员
//            F::gearmanEmail('connect beanstalkd fail!', 'connect beanstalkd fail!');
//            throw new Exception('connect beanstalkd fail!');
//        }
        $this->getConnection();
        $this->redis    = F::redis();
    }

    /**
     * 是否能够连接
     *
     * @return false|true
     */
    public function getConnection()
    {
        $flag   = $this->handler->getConnection()->isServiceListening();
        if (false == $flag) $this->noticeManager();
        return $flag;
    }

    public function handler()
    {
        return $this->handler;
    }

    /**
     * 邮件通知管理员
     */
    protected function noticeManager()
    {
        F::gearmanEmail('connect beanstalkd fail!', 'connect beanstalkd fail!');
    }

    /**
     * 获取实例
     *
     * @return Beanstalkd
     */
    public static function getInstance($tube, array $config = [])
    {
        if (false == self::$instance instanceof self) self::$instance = new self($tube, $config);
        return self::$instance;
    }

    /**
     * 入列
     *
     * @param string|array $data
     * @param int $pri
     * @param int $delay
     * @param int $ttr
     * @return int
     */
    public function put($data, $pri = 1024, $delay = 0, $ttr = 60)
    {
        try {
//            F::gearmanLogs('debug', array_merge($data, ['tube' => $this->tube, 'type' => 'web_queue', 'delay' => $delay]));
//            $this->redis->multi();
            $persistence    = new Persistence($this->tube, $data, '');
            $persistenceData= $persistence->getPersistenceJobData();
            #   并且移除旧的队列
            if (false !== $persistenceData) {
                $persistence->removePersistenceJob();
                if (isset($persistenceData['job_id'])) {
                    try {
                        $this->del($persistenceData['job_id']);
                    } catch (ServerException $e) {

                    }
                }
            }
            $job_id = $this->handler->useTube($this->tube)->put(is_array($data) ? serialize($data) : $data, $pri, $delay, $ttr);
            if (!$job_id) throw new ServerException('入列失败');
            #   设置job id
            $persistence->setJobId($job_id);
            #   持久化数据
            $persistence->addPersistenceJob();


//            #   持久化数据
//            $flag   = self::queuePersistence($data, $this->tube, $job_id);
//            if (false == $flag) throw new ServerException('持久化数据失败', State::STATE_NORMAL);
//            $key    = $this->getCacheKey($data);
//            try {
//                $old_job_id = $this->getHistoryJobId($key);
//                if ($old_job_id) {
//                    self::removeQueuePersistence($old_job_id);
//                    $this->del($old_job_id);
//                }
//            } catch (ServerException $e) {
//
//            }
//            #   设置缓存
//            $flag   = $this->redis->setex($key, 2592000, $job_id);
//            if (false == $flag) throw new ServerException('缓存JOB ID失败', State::STATE_NORMAL);
//            $this->redis->exec();
        } catch (ServerException $e) {
//            $this->redis->discard();
            $data['tube']   = $this->tube;
            $data['msg']    = $e->getMessage();
            $data['file']   = $e->getFile();
            $data['line']   = $e->getLine();
            $data['source'] = 'web';
            F::gearmanLogs('queue_put_fail', $data, true);
//            if ($e->getCode() == State::STATE_NORMAL) $this->handler->del($job_id);
            return false;
        }
        return $job_id;
    }

    /**
     * 入列
     *
     * @param $id
     * @param $no
     * @param int $delay
     * @return int
     */
    public function ordersPut($id, $no, $delay = 0)
    {
        $data   = [
            'id'    => $id,
            'no'    => $no,
        ];
        return $this->put($data, 1024, $delay);
    }

    /**
     * 通知ERP收货
     *
     * @param array $data
     * @param int $delay
     * @return int
     */
    public function erpReceivePut(array $data, $delay = 0)
    {

//        'order_no' => $this->no,
//                'openid' => $user->openid,
//                'safe_psw' => '',
//                'is_auto' => State::STATE_NORMAL
        return $this->put($data, 1024, $delay);
    }

    /**
     * ERP退款
     *
     * @param array $data
     * @param int $delay
     * @return int
     */
    public function erpRefundPut(array $data, $delay = 0)
    {
        return $this->put($data, 1024, $delay);
    }
    
    /**
     * 评价生效
     *
     * @param $id
     * @param int $delay
     * @return int
     */
    public function commentEffectPut($id, $delay = 0)
    {
        return $this->put(['id' => $id], 1024, $delay);
    }

    /**
     * 删除一个job
     *
     * @param $job
     * @return $this
     */
    public function delete($job)
    {
        return $this->handler->delete($job);
    }

    /**
     * 通过ID删除JOB
     *
     * @param $job_id
     * @return $this
     */
    public function del($job_id)
    {
        return $this->handler->del($job_id);
    }

    /**
     * 获取KEY JOB
     *
     * @param $data
     * @return string
     */
    protected function getCacheKey($name)
    {
        $name = is_array($name) ? md5(http_build_query($name)) : $name;
        return "queue:{$name}";
    }

    /**
     * 获取历史入列的JOB ID
     *
     * @param $data
     * @return bool|string
     */
    protected function getHistoryJobId($key)
    {
        if (F::redis()->exists($key)) {
            return F::redis()->get($key);
        }
        return false;
    }

    /**
     * 队列持久化
     *
     * @param array $data
     * @param $tube
     * @param $job_id
     * @return mixed
     */
    public static function queuePersistence(array $data, $tube, $job_id)
    {
        $key    = F::getCacheName(Cache::QUEUE_PERSISTENCE_ROW . $job_id);
        $flag   = self::removeQueuePersistence($job_id);
        if (false == $flag) return false;
        $data['tube']   = $tube;
        $data['job_id'] = $job_id;
        $data['create_time']    = time();
        return F::redis()->set($key, serialize($data));
    }

    /**
     * 移除持久化
     *
     * @param $job_id
     * @return bool|int
     */
    public static function removeQueuePersistence($job_id)
    {
        $key    = F::getCacheName(Cache::QUEUE_PERSISTENCE_ROW . $job_id);
        if (F::redis()->exists($key)) {
            return F::redis()->del([$key]);
        }
        return true;
    }
}