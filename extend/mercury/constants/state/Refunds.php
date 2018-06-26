<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 14:53
 */

namespace mercury\constants\state;

/**
 * Class Refunds
 * @package mercury\constants\state
 *
 * 退货退款
 */
interface Refunds
{
    const STATE_REFUNDS_DELETE  = 0;    //删除状态

    const STATE_REFUNDS_NORMAL  = 1;    //正常状态

    const STATE_REFUNDS_AGREE   = 2;    //同意退款

    const STATE_REFUNDS_EXPRESS = 3;    //买家邮寄商品

    const STATE_REFUNDS_RECEIVE = 4;    //商家确认收货

    const STATE_REFUNDS_CANCEL  = 20;   //退款退货取消

    const STATE_REFUNDS_SUCCESS = 100;  //退款退货成功

    const STATE_REFUNDS_BUYER_APPEAL    = 31;   //买家提起申诉

    const STATE_REFUNDS_SELLER_APPEAL   = 32;   //商家提起申诉

    const STATE_REFUNDS_REFUSE  = 41;   //商家拒绝退款

    const STATE_REFUNDS_ARRAY   = [
        self::STATE_REFUNDS_DELETE  => '已删除',
        self::STATE_REFUNDS_NORMAL  => '买家申请退款',
        self::STATE_REFUNDS_AGREE   => '商家同意退款',
        self::STATE_REFUNDS_EXPRESS => '买家邮寄商品',
        self::STATE_REFUNDS_RECEIVE => '商家确认收货',
        self::STATE_REFUNDS_CANCEL  => '退款已取消',
        self::STATE_REFUNDS_SUCCESS => '退款成功',
        self::STATE_REFUNDS_REFUSE  => '商家拒绝退款',
        self::STATE_REFUNDS_BUYER_APPEAL    => '买家提起申诉',
        self::STATE_REFUNDS_SELLER_APPEAL   => '商家提起申诉',
    ];
}