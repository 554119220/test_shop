<?php
namespace app\api\validate\orders\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-16 15:57:18
 */
class Refund extends \think\Validate
{
    protected $rule = [
        'shop_id'               => [ 'require', 'number' ],
        'shop_orders_id'        => [ 'require', 'number' ],
        'shop_no'        => [ 'require', 'alphaNum', 'length' => '22' ],
        'seller_user_id'        => [ 'require', 'number' ],
        'user_id'               => [ 'require', 'number' ],
        //'refund_no'      => ['require', 'alphaNum', 'length:22'],
        'num'                   => ['number', 'egt:0'],
        'amount'                => ['float', 'egt:0'],
        'express_amount'        => ['float', 'egt:0'],
        'remark'                => ['length:0,200'],
        'goods_id'              => ['require', 'number'],
        'type'                  => ['require', 'number', 'in:1,2'],
        'is_ship'               => ['require', 'number', 'in:0,1'],
        'refund_no'             => ['require', 'length:22', 'alphaNum'],
        'company_id'            => ['require', 'number'],
        'express_no'            => ['require', 'alphaNum', 'length:6,32'],
        'pay_password'          => ['require', 'length:6', 'number'],
        'images'                => ['require']
    ];


    protected $message = [
        'shop_id.require'           => '店铺不能为空',
        'shop_id.number'            => '店铺不正确',
        'shop_orders_id.require'    => '订单不能为空',
        'shop_orders_id.number'     => '订单不正确',
        'shop_no.require'    => '订单编号不能为空',
        'shop_no.alphaNum'   => '订单编号不正确',
        'shop_no.length'     => '订单编号不正确',
        'orders_refund_type.number' => '订单类型不正确',
        'orders_refund_type.in'     => '订单类型不正确',
        'seller_user_id.require'    => '商家不能为空',
        'seller_user_id.number'     => '商家不存在',
        'user_id.require'           => '买家不能为空',
        'user_id.number'            => '买家不存在',
        'orders_refund_no.require'  => '退款单号不能为空',
        'orders_refund_no.alphaNum' => '退款单号不正确',
        'orders_refund_no.length'   => '退款单号不正确',
        'refund_no.require'         => '退款单号不能为空',
        'refund_no.length'          => '退款单号不正确',
        'refund_no.alphaNum'        => '退款单号不正确',
        'is_ship.require'           => '是否可退货不能为空',
        'is_ship.in'                => '是否可退货不正确',
        'type.require'              => '退款类型不能为空',
        'type.in'                   => '退款类型不正确',
        'company_id.require'        => '快递公司不能为空',
        'company_id.number'         => '快递公司不正确',
        'express_no.require'        => '快递单号不能为空',
        'express_no.alphaNum'       => '快递单号不正确',
        'express_no.length'         => '快递单号不正确',
        'remark.length'             => '理由或备注文本长度在5-200个文本之间',
        'num.number'                => '退款数量不正确',
        'amount.number'             => '退款金额不正确',
        'num.egt'                   => '退款数量不能小于0',
        'amount.egt'                => '退款金额不能小于0',
        'express_amount.egt'        => '退运费不能小于0',
        'pay_password.require'      => '安全密码不能为空',
        'pay_password.number'       => '安全密码不正确',
        'pay_password.length'       => '安全密码不正确',
        'images.require'            => '请上传凭证'
    ];


    public $scene = [
        'index'     => [ 'seller_user_id', 'user_id' ],
        'create'    => [ 'shop_no', 'orders_refund_type', 'user_id', 'num', 'remark', 'amount', 'orders_goods_id', 'type', 'is_ship', 'express_amount' ],
        'cancel'    => [ 'user_id', 'refund_no' ],
        'agree'     => [ 'seller_user_id', 'refund_no', 'remark' ],
        'express'   => [ 'user_id', 'company_id', 'express_no', 'refund_no', 'remark' ],
        'receive'   => [ 'seller_user_id', 'refund_no', 'remark' ],
        'appeal'    => [ 'seller_user_id', 'user_id', 'refund_no', 'remark', 'images'],
        'modify'    => ['user_id', 'num', 'amount', 'type', 'remark', 'refund_no', 'express_amount', 'orders_refund_type', 'is_ship', 'type'],
        'detail'    => ['user_id', 'refund_no'],
        'refuse'    => ['seller_user_id', 'refund_no', 'remark'],
    ];
}