<?php
namespace app\api\validate\user\v1;

class Login extends \think\Validate
{
    protected $rule = [
        'user_username' => ['require'],
        'user_password' => ['require'],
        'baseKey' => [ 'require' ],
    ];


    protected $message = [
        'user_username.require' => '用户名必须',

        'user_password.require' => '用户密码必须',

    ];

    public $scene = [
        'index' => [ 'user_username', 'user_password' ],
        'remember'  => [ 'baseKey' ],
    ];
}