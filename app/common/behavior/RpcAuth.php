<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 16:50
 */

namespace app\common\behavior;


use app\common\traits\F;
use think\Exception;

class RpcAuth
{
    //可以访问的IP地址
    const ALLOW_FROM_IPS    = [
        '127.0.0.1',
    ];

    public function run(&$params)
    {
        //如果不在白名单则退出程序
        if (!in_array(request()->ip(), self::ALLOW_FROM_IPS)) {
            exit();
        }
        //设置用户信息
//        if (request()->has('openid')) {
//            request()->bind('user', ['nick' => 'mercury']);
//        }

        //F::writeLog(123);
        //参数验证
        $verify = \mercury\required\Required::getInstance(request())->verify();
        if (true !== $verify) {
            //throw new Exception($verify);
        }
    }
}