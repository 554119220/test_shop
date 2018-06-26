<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30 0030
 * Time: 14:04
 */

namespace mercury\async;
use app\common\traits\F;
use think\Exception;

/**
 * Class Gearman
 * @package app\common\async
 *
 * 异步处理工具
 */
class Gearman
{
    /**
     * @var \GearmanClient 对象
     * @var string $function 执行方法
     */
    protected $client;

    /**
     * @var object $instance 实例
     */
    protected static $instance;

    /**
     * 邮件发送
     */
    const FUNCTION_SEND_EMAIL   = 'noticeEmail';

    /**
     * 短信发送
     */
    const FUNCTION_SEND_SMS     = 'noticeSms';

    /**
     * 日志写入
     */
    const FUNCTION_WRITE_LOG    = 'writeLog';

    const FUNCTION_TEST         = 'noticeTest';

    public function __construct(array $config = [])
    {
        $host   = isset($config['host']) && !empty($config['host']) ? $config['host'] : config('gearman.host');
        $port   = isset($config['port']) && !empty($config['port']) ? $config['port'] : config('gearman.port');
        $timeout= isset($config['timeout']) && !empty($config['timeout']) ? $config['timeout'] : config('gearman.timeout');
        try {
            $this->client   = new \GearmanClient();
            $this->client->addServer($host, $port);
            $this->client->setTimeout($timeout);
        } catch (\GearmanException $e) {
            exit($e);
        }
    }

    /**
     * 获取当前实例
     *
     * @param string $host
     * @param string $timeout
     * @return object 实例|static
     */
    public static function getInstance(array $config = [])
    {
        if (false == self::$instance instanceof self) self::$instance = new static($config);
        return self::$instance;
    }

    /**
     * 添加任务
     *
     * @param $func
     * @param string|array $data 存储的数据
     */
    public function addTask($function, $data)
    {
        try {
            if (is_array($data)) $data = serialize($data);
            $this->client->addTaskBackground($function, $data);
            if (! $this->client->runTasks()) throw new Exception($this->client->error());
        } catch (\GearmanException $e) {
            //执行失败
            return $this->exceptionRun($function, $data, $e->getMessage());
        } catch (Exception $e) {
            //执行失败
            return $this->exceptionRun($function, $data, $e->getMessage());
        }
        return true;
    }

    /**
     * 异常处理
     *
     * @param $function
     * @param $data
     * @return bool|int|mixed|string
     */
    protected function exceptionRun($function, $data, $error)
    {
        $data   = is_string($data) ? unserialize($data) : $data;
        //通知程序员,限制执行次数，不然影响性能多出1秒左右的执行时间
        $domain = config('url_domain_root');
        F::sendMail([
            'subject' => "{$domain}--gearman执行失败--{$function}",
            'content' => $error . '_params_' . (is_string($data) ? $data : serialize($data))
        ]);
        //执行失败
        switch ($function) {
            case self::FUNCTION_WRITE_LOG:  //记录日志
                return F::writeLogByMongoDb($data['table'], $data, $data['suffix']);
                break;
            case self::FUNCTION_SEND_EMAIL: //发送邮件
                return F::sendMail($data);
                break;
            case self::FUNCTION_SEND_SMS:   //发送短信
                return F::sendSms($data);
                break;
        }
        return false;
    }
}