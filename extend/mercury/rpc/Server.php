<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 8:57
 */

namespace mercury\rpc;

use app\common\traits\F;
use mercury\constants\Code;
use mercury\required\Required;
use think\Exception;

/**
 * Class Server
 * @package Mercury\rpc
 *
 * RPC服务端
 * 外部使用curl，内部使用rpc
 */
class Server
{
    protected $service;
    public function __construct($obj)
    {
        $this->service = new \Yar_Server($obj);
        //$this->service->handle();
        //throw new \Yar_Server_Exception('签名错误');
        //throw new Exception('sign fail!');
        //签名校验
        //Post请求？
        //request()->bind('params', ['user' => 'mercury', 'password' => '123465']);
        //F::writeLogByFile(input());
        //可以绑定用户数据
        $params = self::parse(request()->getInput());
        //F::writeLog($params['p']);
        //批量绑定数据
        if (isset($params['p'])) {
            self::bindRequest($params['p'][0]);
        }
        //方法
        request()->bind('action', $params['m']);
        //F::writeLog(request()->app);
    }

    public function handle()
    {
        //$verify = Required::getInstance(request())->verify();
        //if (true !== $verify) {
            //$ret    = ['code' => Code::CODE_FALSE, 'msg' => $verify];
            //exit($verify);
            //exit(json_encode($ret, JSON_UNESCAPED_UNICODE));
            //throw new Exception($verify);
        //}
        $this->service->handle();
    }

    /**
     * 解析输出流
     *
     * @param $str
     * @param bool $array
     * @return bool|mixed|string
     */
    public static function parse($str, $array = true)
    {
        $str    = substr($str, strpos($str, 'YAR_')+4);
        if ($array) $str = unserialize($str);
        return $str;
    }

    /**
     * @param array $data
     */
    public static function bindRequest(array $data)
    {
        foreach ($data as $k => $v) {
            request()->bind($k, $v);
        }
    }

    /**
     * 单独取出数据 [没用]
     *
     * @param array $data
     * @param $key
     * @return array
     */
    public function bind(array $data, $key)
    {
        return array_filter($data, function ($val) use ($key) {
            return $val[$key];
        });
    }
}