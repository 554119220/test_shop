<?php
namespace app\api\validate\user\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-30 17:23:24
 */
use app\common\traits\F as Fun;
use mercury\factory\Factory;
use mercury\constants\Code;

class Setting extends \think\Validate
{
    protected $rule = [
        'old_user_password' => [ 'require', 'checkOldPassword' => '' ],
        'user_password'     => [ 'require', 'confirm' => 're_user_password' ],
        
        // 're_user_password'  => [ 'require' ],
        'old_user_pay_password' => [ 'require', 'checkOldPayPassword' => '' ],
        'user_pay_password'     => [ 'require', 'regex' => '/^[0-9]{6}$/', 'confirm' => 're_user_pay_password' ],

        'code'                  => [ 'require', 'checkCode' => '' ],
        'user_avatar'           => [ 'require' ],
        'user_nick'             => [ 'require', 'length' => '3,10' ],
    ];


    protected $message = [
        'user_password.require'                     => '用户密码必须',
        'user_password.confirm'                     => '两次新密码不一致',

        'old_user_password.require'                 => '旧密码必须',
        'old_user_password.checkOldPassword'        => '旧密码错误',

        'code.require'                              => '验证码必须',
        'code.checkCode'                            => '验证码错误',

        'user_pay_password.require'                 => '安全密码必须',
        'user_pay_password.regex'                   => '安全必须是6位数字',
        'user_pay_password.confirm'                 => '两次安全密码不一致',

        'old_user_pay_password.require'             => '旧安全密码必须',
        'old_user_pay_password.checkOldPayPassword' => '旧安全密码错误',

        'mobile.require'                            => '手机号码必须',

        'user_avatar.require'                       => '用户头像必须',

        'user_nick.require'                         => '昵称必须',
        'user_nick.length'                          => '昵称长度为3到10个字符',


    ];


    public $scene = [
        'update_password'       => [ 'user_password', 'old_user_password' ],

        'set_pay_password'      => [ 'code', 'user_pay_password' ],

        'update_pay_password'   => [ 'user_pay_password', 'old_user_pay_password' ],

        'forgot_password'       => [ 'code', 'user_password' ],
        'forgot_pay_password'   => [ 'code', 'user_pay_password' ],

        'update_user'           => [ 'user_avatar', 'user_nick' ],
    ];

    function checkCode($value, $rule)
    {
        $check = Factory::instance('/tools/v1/NoticeTpl/check_code')->run();
        return Code::CODE_SUCCESS == ($check['code'] ?? '') ? true : $check['msg'];
    }

    function checkOldPassword($value, $rule)
    {
        $value = Fun::mApi('user', 'User')->passwordDeal($value);
        $user = Fun::dataDetail(Fun::mApi('user', 'User'), intval(request()->user['user_id'] ?? 0));
        if ( $value != ($user['user_password'] ?? '') ) {
            return false;
        }
        return true;
    }

    function checkOldPayPassword($value, $rule)
    {
        $value = Fun::mApi('user', 'User')->payPasswordDeal($value);
        $user = Fun::dataDetail(Fun::mApi('user', 'User'), intval(request()->user['user_id'] ?? 0));
        if ( $value != ($user['user_pay_password'] ?? '') ) {
            return false;
        }
        return true;
    }

}