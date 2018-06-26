<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 17:40
 */

namespace app\api\logic\user\v1;

use think\Validate;

/**
 * Class Login
 * @package app\api\logic\user\v1
 *
 * 用户逻辑层 处理请求参数验证, 当前方法对应controller中的方法，对象名称对应控制器名称
 */
class Login
{
    public function index()
    {
        return [
            'user_username'  => '用户名',
            'user_password'  => '密码',
        ];
    }

    public function remember($data)
    {
//        return [
//            'baseKey'  => 'key',
//        ];
        $message    = [];
        $rules      = [];
        $validate   =  Validate::make($rules, $message);
        if (!$validate->check($data)) return $validate->getError();
        return true;
    }
}