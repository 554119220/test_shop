<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23 0023
 * Time: 14:23
 */

namespace app\api\validate\cps\v1;


use think\Validate;

class Withdraw extends Validate
{
    protected $rule = [
        'user_id'   => ['require'],
        'app_id'    => ['number', 'require'],
        'withdraw_amount'   => ['require', 'min:0.1', 'max:999999']
    ];

    protected $message  = [
        'user_id.require'   => '请登录',
        'app_id.require'    => '应用不存在',
        'app_id.number'     => '应用不存在',
        'withdraw_amount.require'   => '提现金额不能为空',
        'withdraw_amount.min'       => '提现金额不能小于0.1元',
        'withdraw_amount.max'       => '提现金额不能大于999999',

    ];

    public $scene   = [
        'index' => ['user', 'app_id', 'cps_withdraw_amount']
    ];
}