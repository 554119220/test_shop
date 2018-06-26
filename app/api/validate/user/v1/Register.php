<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 10:25
 */

namespace app\api\validate\user\v1;
use mercury\factory\Factory;
use mercury\constants\Code;

class Register extends \think\Validate
{
    protected $rule = [
        'user_mobile'   => [ 'require', 'regex' => '/^1[0-9]{10}$/', 'unique' => 'user'],
        'code'          => [ 'require', 'checkCode' => '' ],
        'user_password' => [ 'require', 'confirm' => 're_user_password'],
        'user_username' => [ 'require', 'regex' => '/^.*?$/', 'unique' => 'user'],
    ];


    protected $message = [
        'user_mobile.require'   => '手机号码必须',
        'user_mobile.regex'     => '手机号码格式错误',
        'user_mobile.unique'    => '手机号已被注册',

        'user_username.require' => '用户名必须',
        'user_username.regex'   => '用户名格式错误',
        'user_username.unique'  => '用户名已被注册',

        'user_password.require' => '用户密码必须',
        'user_password.confirm' => '两次密码不一致',

        'code.require'          => '手机验证码必须',
        'code.checkCode'        => '手机验证码错误',
    ];

    public $scene = [
        'index'     => [ 'user_mobile', 'code' , 'user_username', 'user_password' ],
    ];

    function checkCode($value, $rule)
    {
        $check = Factory::instance('/tools/v1/NoticeTpl/check_code')->run();
        return Code::CODE_SUCCESS == ($check['code'] ?? '') ? true : $check['msg'];
    }
}