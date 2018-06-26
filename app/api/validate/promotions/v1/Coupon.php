<?php
namespace app\api\validate\promotions\v1;



/**
 * 优惠券
 * 
 * @author qinzichao
 * @date 2017-12-4 10:46
 */
class Coupon extends \think\Validate
{
    protected $rule = [
        'goods_id'      => [ 'require', 'number','gt:0'  ],
        'coupon_name'      => [ 'require'],
        'discount_money'      => [ 'require', 'gt:0'  ],
        'coupon_id'      => [ 'require', 'number','gt:0'  ],
    ];


    protected $message = [
        'goods_id.require'      => '商品不能为空',
        'goods_id.number'       => '商品不存在',
        'goods_id.gt'       => '商品ID必须大于0',
        
        'coupon_name.require'      => '优惠券名称不能为空',
        
        'discount_money.require'      => '优惠券价值不能为空',
        'discount_money.gt'       => '优惠券价值必须大于0',
        
        'coupon_id.require'      => '优惠券ID不能为空',
        'coupon_id.number'       => '优惠券ID不存在',
        'coupon_id.gt'       => '优惠券ID必须大于0',
    ];


    public $scene = [
        'list' => [],
        'create' => ['coupon_name','discount_money'],
        'update' => ['coupon_id'],
        'disable' => ['coupon_id'],
        'enable' => ['coupon_id'],
    ];
}