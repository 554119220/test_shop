<?php
namespace app\api\validate\promotions\v1;



/**
 * 用户优惠券
 * 
 * @author qinzichao
 * @date 2017-12-4 10:46
 */
class UserCoupon extends \think\Validate
{
    protected $rule = [
        'coupon_id'      => [ 'require', 'number','gt:0' ],
        'coupon_sn'      => [ 'require', ],
    ];


    protected $message = [
        'coupon_id.require'      => '优惠券不能为空',
        'coupon_id.number'       => '优惠券不存在',
        'coupon_id.gt'       => '优惠券ID必须大于0',
        
        'coupon_sn.require'       => '优惠券编 号不能为空',
    ];


    public $scene = [
        'create' => ['coupon_id'],
        'mycoupon' => [],
        'useCoupon'=>['coupon_sn'],
    ];
}