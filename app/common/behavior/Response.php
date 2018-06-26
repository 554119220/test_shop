<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8 0008
 * Time: 13:43
 */

namespace app\common\behavior;


use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;

/**
 * Class Response
 * @package app\common\behavior
 *
 * 响应数据重组
 */
class Response
{
    public function run(&$params)
    {
        //取得要返回的数据
        $data   = $params->getData();
        if (is_string($data) && strpos($data, '<!DOCTYPE html>') !== false) {

        } else {
            if (!is_array($data)) { //如果不为数组
                $data   = array_merge(Code::CODE_ARRAY[$data],
                    ['code' => $data]);
            } else if (!isset($data['code']) && empty($data['code'])) { //如果是数组并且不含code
                $data   = array_merge(['data' => $data],
                    Code::CODE_ARRAY[Code::CODE_SUCCESS],
                    ['code' => Code::CODE_SUCCESS]);
            }
            //异步记录日志
            //$flag   = F::gearmanLogs('api', array_merge($data, request()->param()), true);
            //重置要返回的数据
            $logs    = [
                'user'  => request()->user,
                'params'=> request()->data,
                'source'=> State::STATE_NORMAL, #   请求
                'data'  => $data,
            ];
//            $logs   = array_merge($data, $tmp);
            F::gearmanLogs('api', $logs, true);
//            F::writeLogByMongoDb('api', $logs, true);
            $params->data($data);
        }
    }
}