<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22 0022
 * Time: 16:53
 */

namespace mercury\constants\state;


class Times
{
    #   关闭订单延期时间
    const TIME_ORDERS_CLOSE     = 'time_orders_close';  //86400

    const TIME_ACTIVE_ORDERS_CLOSE  = 'time_active_orders_close';  //900
    #   买家付款商家5天不发货的执行扣分及退款
    const TIME_ORDERS_SHIP      = 'time_orders_ship';  //432000
    #   确认收货延期时间
    const TIME_ORDERS_RECEIVE   = 'time_orders_receive';  //864000
    #   评价延期时间
    const TIME_ORDERS_COMMENT   = 'time_orders_comment';  //1296000
    #   退款同意
    const TIME_REFUND_AGREE     = 'time_refund_agree';  //259200
    #   确认收货
    const TIME_REFUND_RECEIVE   = 'time_refund_receive';  //864000
    #   取消退款
    const TIME_REFUND_CANCEL    = 'time_refund_cancel';  //259200
    #   同意售后
    const TIME_SERVICE_AGREE    = 'time_service_agree';  //259200
    #   取消售后
    const TIME_SERVICE_CANCEL   = 'time_service_cancel';  //259200
    #   买家确认收货
    const TIME_SERVICE_BUYER_RECEIVE    = 'time_service_buyer_receive';  //864000
    #   商家确认收货
    const TIME_SERVICE_SELLER_RECEIVE   = 'time_service_seller_receive';  //864000
    #   评价生效时间
    const TIME_COMMENT_EFFECT           = 'time_comment_effect';  //2592000

    /**
     * 获取时间
     *
     * @param $key
     * @return int
     */
    public static function times($key, $sec = false)
    {
        $times  = config('site.orders');
        if (isset($times[$key])) {
            return $sec ? $times[$key] :  (time() + $times[$key]);
        }
        return $sec ? 432000 : (time() + 432000);
    }
}