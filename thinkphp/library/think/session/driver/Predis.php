<?php
/**
 * Created by PhpStorm.
 * User: LiJiangPeng
 * Date: 2018/5/17
 * Time: 下午1:51
 */

namespace think\session\driver;

use Predis\Client;
use SessionHandler;
use think\Exception;

class Predis extends SessionHandler
{
    /** @var \Redis */
    protected $handler = null;
    protected $config  = [
        'host'         => '127.0.0.1', // redis主机
        'port'         => 6379, // redis端口
        'password'     => '', // 密码
        'select'       => 0, // 操作库
        'expire'       => 3600, // 有效期(秒)
        'timeout'      => 0, // 超时时间(秒)
        'persistent'   => true, // 是否长连接
        'session_name' => '', // sessionkey前缀
    ];

    public function __construct($config = [])
    {
//        $this->config = array_merge($this->config, $config);
        $this->config   = array_merge(config('redis'), $config);
    }

    /**
     * 打开Session
     * @access public
     * @param string $savePath
     * @param mixed  $sessName
     * @return bool
     * @throws Exception
     */
    public function open($savePath, $sessName)
    {
        // 检测php环境
        if (!extension_loaded('redis')) {
            throw new Exception('not support:redis');
        }
        $this->handler = new Client(
            $this->parseSentinel(), [
                'replication'   => 'sentinel',
                'service'       => $this->config['service'],
                'parameters'    => [
                    'password'  => $this->config['password']
                ]
            ]
        );

        /*
        // 建立连接
        $func = $this->config['persistent'] ? 'pconnect' : 'connect';
        $this->handler->$func($this->config['host'], $this->config['port'], $this->config['timeout']);

        if ('' != $this->config['password']) {
            $this->handler->auth($this->config['password']);
        }

        if (0 != $this->config['select']) {
            $this->handler->select($this->config['select']);
        }
*/
        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
    public function close()
    {
        $this->gc(ini_get('session.gc_maxlifetime'));
        $this->handler->close();
        $this->handler = null;
        return true;
    }

    /**
     * 读取Session
     * @access public
     * @param string $sessID
     * @return string
     */
    public function read($sessID)
    {
        return (string) $this->handler->get($this->config['session_name'] . $sessID);
    }

    /**
     * 写入Session
     * @access public
     * @param string $sessID
     * @param String $sessData
     * @return bool
     */
    public function write($sessID, $sessData)
    {
        if ($this->config['expire'] > 0) {
            return $this->handler->setex($this->config['session_name'] . $sessID, $this->config['expire'], $sessData);
        } else {
            return $this->handler->set($this->config['session_name'] . $sessID, $sessData);
        }
    }

    /**
     * 删除Session
     * @access public
     * @param string $sessID
     * @return bool
     */
    public function destroy($sessID)
    {
        return $this->handler->del($this->config['session_name'] . $sessID) > 0;
    }

    /**
     * Session 垃圾回收
     * @access public
     * @param string $sessMaxLifeTime
     * @return bool
     */
    public function gc($sessMaxLifeTime)
    {
        return true;
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
}