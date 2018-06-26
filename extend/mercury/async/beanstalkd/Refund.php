<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8 0008
 * Time: 9:37
 */

namespace mercury\beanstalkd;


interface Refund
{
    /**
     * 7天未发货订单执行退款，并且对商家进行一定的处罚
     */
    const TUBE_ORDERS_EXPRESS       = 'orders_express';
    const TUBE_ORDERS_EXPRESS_EXPIRE= 7 * 86400;

    /**
     * 商家4天未处理退款则自动退款
     */
    const TUBE_REFUND           = 'refund';
    const TUBE_REFUND_EXPIRE    = 4 * 86400;

    /**
     * 退货并退款4天未处理系统自动同意
     */
    const TUBE_REFUNDS          = 'refunds';
    const TUBE_REFUNDS_EXPIRE   = 4 * 86400;

    /**
     * 买家邮寄商品
     */
    const TUBE_REFUNDS_BUYER_EXPRESS    = 'refunds_buyer_express';
    const TUBE_REFUNDS_BUYER_EXPRESS_EXPIRE = 4 * 86400;

    /**
     * 商家确认收货
     */
    const TUBE_REFUNDS_SELLER_RECEIVE   = 'refunds_seller_receive';
    const TUBE_REFUNDS_SELLER_RECEIVE_EXPIRE    = 15 * 86400;

    /**
     * 取消
     */
    const TUBE_REFUNDS_CANCEL   = 'refunds_cancel';
}