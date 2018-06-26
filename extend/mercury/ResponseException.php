<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 19:03
 */

namespace mercury;

use app\common\traits\F;
use mercury\constants\Code;

/**
 * Class ResponseException
 * @package mercury
 *
 * 响应异常错误处理
 */
class ResponseException extends \Exception
{

    /**
     * @var string $info 错误信息[英文版]
     */
    protected $info = 'information is empty';

    public function __construct($code = 20000, $message = '', $info = '')
    {

        if (empty($message)) {
            $code_arr   = Code::CODE_ARRAY;
            $message    = isset($code_arr[$code]['msg']) && !empty($code_arr[$code]['msg']) ? $code_arr[$code]['msg'] : '状态编码不存在！';
            $info       = isset($code_arr[$code]['info']) && !empty($code_arr[$code]['info']) ? $code_arr[$code]['info'] : 'information is empty';
        }
        if (!empty($info)) $this->info = $info;
        parent::__construct($message, $code);
    }

    /**
     * 返回数据
     *
     * @return array
     */
    public function getData()
    {
        return [
            'code'  => $this->getCode(),
            'msg'   => $this->getMessage(),
            'info'  => $this->getInfo(),
        ];
    }

    /**
     * 响应数据
     *
     * @param array|string $data
     * @return array
     */
    public function response($data)
    {
        $ret   = [
            'code'  => $this->getCode(),
            'msg'   => $this->getMessage(),
            'info'  => $this->getInfo(),
            'data'  => $data
        ];
        $this->writeLog($ret);
        return $ret;
    }

    /**
     * 获取info
     *
     * @return 错误信息|string
     */
    public function getInfo()
    {
        return $this->info;
    }

    public function writeLog(array $data)
    {
        //写入日志
        if ($this->getCode() == Code::CODE_SUCCESS) {
            $table  = 'response_success';
        } else {
            $table  = 'response_error';
        }

        $logs   = [
            'data'  => request()->param(),
            'app'   => request()->app,
            'user'  => request()->user,
            'ip'    => request()->ip,
            'response'  => $data,
        ];
        //异步请求
        //F::writeLog($logs);
    }
}