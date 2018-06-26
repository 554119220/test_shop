<?php
namespace app\api\validate\orders\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-16 15:28:47
 */
class Orders extends \think\Validate
{
    protected $rule = [
        //'orders_shop_id'            => [ 'require', 'number' ],
        'shop_no'            => [ 'require', 'length:22', 'alphaNum' ],
        'orders_no'                 => [ 'require', 'length:22', 'alphaNum' ],
        'shop_id'                   => [ 'require', 'number' ],
        'seller_user_id'            => [ 'require', 'number' ],
        'buyer_user_id'             => [ 'require', 'number' ],
        'orders_shop_pay_type'      => [ 'require', 'number' ],
        'orders_shop_pay_amount'    => [ 'require', 'float' ],
        'express_no'                => [ 'require', 'alphaNum', 'length:6,32' ],
        'company_id'                => [ 'require', 'number' ],
        'address_id'                => ['require', 'number'],
        'orders_goods_id'           => ['require', 'number'],
        'remark'                    => ['length:5,200'],
        'express_remark'            => ['length:5,200'],
        'pay_password'              => ['require', 'number', 'length:6']
    ];


    protected $message = [
        'orders_shop_id.require'            => '订单不能为空',
        'orders_shop_no.require'            => '订单编号不能为空',
        'orders_no.require'                 => '父订单编号不能为空',
        'shop_id.require'                   => '所属店铺不能为空',
        'seller_user_id.require'            => '商家不能为空',
        'buyer_user_id.require'             => '买家不能为空',
        'orders_shop_pay_type.require'      => '付款类型不能为空',
        'orders_shop_pay_amount.require'    => '付款金额不能为空',
        'express_no.require'                => '快递单号不能为空',
        'company_id.require'                => '快递公司不能为空',
        'company_id.number'                 => '快递公司不正确',
        'address_id.require'                => '收货地址不能为空',
        'address_id.number'                 => '收货地址不正确',
        'express_no.alphaNum'               => '快递单号不正确',
        'express_no.length'                 => '快递单号不正确',
        'shop_id.number'                    => '商家不存在',
        'orders_shop_id.number'             => '订单编号不正确',
        'shop_no.length'                    => '订单编号不正确',
        'shop_no.alphaNum'                  => '订单编号不正确',
        'orders_goods_id.require'           => '商品不能为空',
        'orders_goods_id.number'            => '商品不存在',
        'remark.length'                     => '备注信息在5-200个字符之间',
        'express_remark.length'             => '备注信息在5-200个字符之间',
        'pay_password.require'              => '安全密码不能为空',
        'pay_password.number'               => '安全密码不正确',
        'pay_password.length'               => '安全密码不正确',
    ];


    public $scene = [
        'index'             => ['user_id'],
        'create'            => ['user_id', 'data', 'address_id'],
        'close'             => ['user_id', 'remark'],
        'setpay'            => ['orders_no', 'trade_no', 'pay_type', 'pay_amount'],
        'ship'              => ['user_id', 'shop_no', 'company_id', 'express_no'],
        //'editShip'         => ['user_id', 'orders_shop_no', 'goods_amount', 'express_amount'],
        'detail'            => ['user_id', 'shop_no'],
        'receive'           => ['user_id', 'shop_no', 'pay_password'],
        'grouporders'       => ['user_id', 'orders_no'],
        'editShip'          => ['user_id', 'shop_no', 'company_id', 'express_no', 'express_remark'],
        'noticeship'        => ['user_id', 'shop_no'],
        'buyagain'          => ['user_id', 'shop_no'],
        'refundorders'      => ['user_id', 'shop_no'],
        'refundapply'       => ['user_id', 'shop_no'],
        'serviceorders'     => ['user_id', 'shop_no'],
        'serviceapply'      => ['user_id', 'shop_no'],
    ];
}