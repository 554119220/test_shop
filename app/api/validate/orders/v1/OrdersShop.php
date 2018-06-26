<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9 0009
 * Time: 17:45
 */

namespace app\api\validate\orders\v1;


use think\Validate;

class OrdersShop extends Validate
{
    protected $rule     = [
        'shop_no'   => 'require'
    ];

    protected $message  = [
        'shop_no.require'   => '订单号不能为空',
//        'shop_no.length'    => '订单号不正确',
    ];

    public $scene   = [
        'query' => ['shop_no']
    ];
}