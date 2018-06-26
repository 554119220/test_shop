<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15 0015
 * Time: 18:04
 */

namespace mercury\cps;


class Config
{
    //    const HOST  = 'http://cps.htota.com';
    const HOST  = 'http://182.61.33.216';

    #   订单同步
    const ORDERS_SYNCHRONIZE    = '/v1.0/order/sync.json';
    #   订单更新
    const ORDERS_UPDATE         = '/v1.0/order/update.json';

    #   商品同步
    const GOODS_SYNCHRONIZE     = '/v1.0/goods/sync.json';

    #   用户签到奖励
    const USER_CHECK_IN_REWARD  = '/v1.0/checkin/do.json';

    #   优惠券查询接口
    const COUPON_QUERY_BY_GOODS = '/v1.0/coupon/query.json';
    #   优惠券查询通过用户查询
    const COUPON_QUERY_BY_USER  = '/v1.0/coupon/find.json';

    #   优惠券领取
    const COUPON_RECEIVE        = '/v1.0/coupon/fetch.json';
    #   优惠券使用
    const COUPON_USE            = '/v1.0/coupon/use.json';
    #   获取商品佣金比例
    const GOODS_COMMISSION_RATIO= '/v1.0/goods/commission.json';
}