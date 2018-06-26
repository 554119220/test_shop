<?php
namespace app\api\validate\tools\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-30 14:52:03
 */
use \app\common\traits\F as Fun;

class NoticeTpl extends \think\Validate
{
    protected $rule = [
        'id'        => [ 'require', 'regex' => '/^[1-9][0-9]{0,10}$/' ],
        'code'      => [ 'require', 'regex' => '/^[1-9][0-9]{5}$/' ],
        'mobile'    => [ 'require', 'checkMobile' => '' ],
    ];


    protected $message = [
        'id.require'    => '短信参数不能为空',
        'id.regex'      => '短信参数错误',

        'code.require'  => '验证码不能为空',
        'code.regex'    => '验证码错误',

        'mobile.require'        => '手机号码不能为空',
        'mobile.checkMobile'    => '手机号码错误',
    ];

    public $scene = [
        'code'          => [ 'id' ],
        'check_code'    => [ 'code' ],
        'check_mobile'  => [ 'mobile' ],
    ];

    function checkMobile($value, $rule)
    {
        return Fun::is_mobile($value);
    }
}