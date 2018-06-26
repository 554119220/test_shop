<?php
namespace app\api\validate\orders\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-16 16:08:57
 */
class Service extends \think\Validate
{
    protected $rule = [
        'shop_no'            => [ 'require', 'length:22', 'alphaNum' ],
        'shop_id'                   => [ 'require', 'number' ],
        'seller_user_id'            => [ 'require', 'number' ],
        'buyer_user_id'             => [ 'require', 'number' ],
        'orders_shop_orders_id'     => [ 'require', 'number' ],
        'service_no'                => [ 'require', 'alphaNum', 'length:22' ],
        'orders_service_num'        => [ 'require', 'number', 'egt:1' ],
        'goods_id'                  => [ 'require', 'number' ],
        'goods_sku_id'              => [ 'require', 'number' ],
        'company_id'                => ['require', 'number'],
        'express_no'                => ['require', 'alphaNum', 'length:6,32'],
        'num'                       => ['require', 'number', 'egt:1'],
        'pay_password'              => ['require', 'number', 'length:6'],
        'remark'                    => ['length:0,255'],
        'address_id'                => ['require', 'number'],
        'images'                    => ['require']
    ];


    protected $message = [
        'shop_no.require'        => '订单编号不能为空',
        'shop_no.length'         => '订单编号不正确',
        'shop_no.alphaNum'       => '订单编号不正确',
        'shop_id.require'               => '店铺不能为空',
        'shop_id.number'                => '店铺不存在',
        'seller_user_id.require'        => '商家不能为空',
        'seller_user_id.number'         => '商家不存在',
        'buyer_user_id.require'         => '买家不能为空',
        'buyer_user_id.number'          => '买家不存在',
        'orders_shop_orders_id.require' => '订单不能为空',
        'orders_shop_orders_id.number'  => '订单不存在',
        'service_no.require'            => '售后订单编号不能为空',
        'service_no.alphaNum'           => '售后订单编号不正确',
        'service_no.length'             => '售后订单编号不存在',
        'orders_service_num.require'    => '申请售后数量不能为空',
        'orders_service_num.number'     => '申请售后数量不正确',
        'orders_service_num.egt'        => '售后申请数量不正确',
        'goods_id.require'              => '申请售后商品不能为空',
        'goods_id.number'               => '申请售后商品不存在',
        'goods_sku_id.require'          => '申请售后库存不能为空',
        'goods_sku_id.number'           => '申请售后库存不正确',
        'pay_password.require'          => '安全密码不能为空',
        'num.require'                   => '申请售后数量不能为空',
        'num.egt'                       => '申请售后数量不能小于0',
        'express_no.require'            => '快递单号不能为空',
        'express_no,alphaNum'           => '快递单号不正确',
        'express_no,length'             => '快递单号不正确',
        'company_id.require'            => '快递公司不能为空',
        'company_id.number'             => '快递公司不正确',
        'remark.length'                 => '备注或理由文本长度在5-200个之间',
        'address_id.require'            => '收货地址不能为空',
        'address_id.number'             => '收货地址不正确',
        'pay_password.length'           => '安全密码不正确',
        'pay_password.number'           => '安全密码不正确',
        'images.require'                => '请上传凭证'
    ];


    public $scene = [
        'index'         => ['user_id'],
        'create'        => ['user_id', 'num', 'goods_id', 'shop_no', 'remark'],
        'refuse'        => ['service_no', 'remark'],
        'agree'         => ['service_no', 'remark', 'address_id'],
        'appeal'        => ['user_id', 'service_no', 'remark', 'images'],
        'detail'        => ['user_id', 'service_no'],
        'receive'       => ['user_id', 'service_no', 'remark'],
        'express'       => ['user_id', 'service_no', 'express_no', 'company_id', 'remark'],
        'modify'        => ['user_id', 'service_no', 'num', 'remark'],
        'cancel'        => ['user_id', 'service_no', 'remark']
    ];
}