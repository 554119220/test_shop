<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20 0020
 * Time: 14:57
 */

namespace app\api\validate\orders\v1;

use think\Validate;

/**
 * 通知数据验证模型
 *
 * Class Notify
 * @package app\api\validate\orders\v1
 */
class Notify extends Validate
{
    protected $rule = [
        'order_no'    => ['require', 'length:22'],
        'code'  => ['require'],
//        'pay_type'  => ['require', 'number', 'min:1', 'max:100'],
        'amount'=> ['require', 'number', 'egt:0.01', 'gt:9999999']
    ];

    protected $message  = [
        'shop_no.require'   => '订单号不能为空',
        'trade_no.require'  => '第三方交易号不能为空',
        'pay_type.require'  => '付款方式不能为空',
        'pay_amount.require'=> '支付金额不能为空',
        'shop_no.length'    => '订单号不正确',
        'pay_type.number'   => '付款类型不正确',
        'pay_amount.number' => '付款金额不正确',
    ];

    public $scene   = [
        'pay'   => ['shop_no', 'trade_no', 'pay_type', 'pay_amount'],
    ];
}