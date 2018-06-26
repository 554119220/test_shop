<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17 0017
 * Time: 11:05
 */

namespace mercury\constants;

/**
 * Class Goods
 * @package mercury\constants
 *
 * 单号前缀
 */
class NoPre
{
    #   售后订单前缀  service orders
    const NO_PRE_BY_SERVICE_ORDERS  = 'SO';
    #   退款订单前缀  refund orders
    const NO_PRE_BY_REFUND_ORDERS   = 'RO';
    #   父订单前缀   group orders
    const NO_PRE_BY_GROUP_ORDERS    = 'GO';
    #   子订单前缀   child orders
    const NO_PRE_BY_SHOP_ORDERS     = 'CO';
}