<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8 0008
 * Time: 9:27
 */

namespace mercury\beanstalkd;

/**
 * Class Tubes
 * @package mercury\beanstalkd
 *
 * 管道常量
 */
class Tubes implements Refund, Service
{
    /**
     * 订单关闭
     */
    const TUBE_ORDERS_CLOSE         = 'orders_close';
    const TUBE_ORDERS_CLOSE_EXPIRE  = 86400;

    /**
     * 收货
     */
    const TUBE_ORDERS_RECEIVE       = 'orders_receive';
    const TUBE_ORDERS_RECEIVE_EXPIRE= 15 * 86400;

    /**
     * 订单评价
     */
    const TUBE_ORDERS_COMMENT       = 'orders_comment';
    const TUBE_ORDERS_COMMENT_EXPIRE= 30 * 86400;
}