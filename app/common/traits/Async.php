<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13 0013
 * Time: 15:04
 */

namespace app\common\traits;
use mercury\async\Beanstalkd;

/**
 * Trait Async
 * @package app\common\traits
 *
 * 异步方法
 */
trait Async
{
    /**
     * 异步发送短信
     *
     * @param string $to
     * @param string $content
     * @return mixed
     */
    public static function gearmanSms($to, $content)
    {
        return \mercury\async\Gearman::getInstance()->addTask(\mercury\async\Gearman::FUNCTION_SEND_SMS, ['to' => $to, 'content' => $content]);
    }

    /**
     * 异步发送邮件
     *
     * @param string $subject
     * @param string $content
     * @param string $to
     * @return mixed
     */
    public static function gearmanEmail($subject, $content, $to = '')
    {
        $to = $to ? $to : config('notice.email')['to'];
        return \mercury\async\Gearman::getInstance()->addTask(\mercury\async\Gearman::FUNCTION_SEND_EMAIL, [
            'to'        => $to,
            'content'   => $content,
            'subject'   => $subject,
        ]);
    }

    /**
     * 异步记录日志
     *
     * @param string $table
     * @param array $data
     * @param bool|string $suffix
     * @return mixed
     */
    public static function gearmanLogs($table, array $data, $suffix = false)
    {
        $data['suffix'] = $suffix;
        $data['table']  = $table;
        return \mercury\async\Gearman::getInstance()->addTask(\mercury\async\Gearman::FUNCTION_WRITE_LOG, $data);
    }

    /**
     * 入列
     *
     * @param string $tube
     * @param string|array $data
     * @param int $pri
     * @param int $delay
     * @param int $ttr
     * @return int
     */
    public static function beanstalkPut($tube, $data,  $delay = 0, $pri = 1024,$ttr = 60)
    {
        return Beanstalkd::getInstance($tube)->put($data, $pri, $delay, $ttr);
    }
}