<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 14:39
 */

namespace mercury\constants\state;

/**
 * Class Orders
 * @package mercury\constants\state
 *
 * 订单
 */
interface Orders
{
    /**
     * 订单状态
     */
    const STATE_ORDERS_DELETE   = 0;    //已删除

    const STATE_ORDERS_NORMAL   = 1;    //待付款

    const STATE_ORDERS_PAY      = 2;    //已付款

    const STATE_ORDERS_SHIP     = 3;    //已发货

    const STATE_ORDERS_RECEIVE  = 4;    //已收货

    const STATE_ORDERS_COMMIT   = 5;    //已评价

    const STATE_ORDERS_ARCHIVE  = 6;    //已归档

    const STATE_ORDERS_REFUND_CLOSE = 41;   //退款并关闭

    const STATE_ORDERS_NORMAL_CLOSE = 51;   //正常关闭

    const STATE_ORDERS_ARRAY    = [
        self::STATE_ORDERS_DELETE   => '已删除',
        self::STATE_ORDERS_NORMAL   => '未付款',
        self::STATE_ORDERS_PAY      => '已付款',
        self::STATE_ORDERS_SHIP     => '已发货',
        self::STATE_ORDERS_RECEIVE  => '已收货',
        self::STATE_ORDERS_COMMIT   => '已评价',
        self::STATE_ORDERS_ARCHIVE  => '已归档',
        self::STATE_ORDERS_REFUND_CLOSE => '退款并关闭',
        self::STATE_ORDERS_NORMAL_CLOSE => '已关闭',
    ];


    /**
     * 订单付款状态
     */
    const STATE_ORDERS_PAYMENT_SUCCESS  = 1;    //已付款

    const STATE_ORDERS_PAYMENT_WAITING  = 0;    //等待付款

    const STATE_ORDERS_PAYMENT_ARRAY    = [
        self::STATE_ORDERS_PAYMENT_WAITING  => '未付款',
        self::STATE_ORDERS_PAYMENT_SUCCESS  => '已付款',
    ];

}