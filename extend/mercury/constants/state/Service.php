<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 15:25
 */

namespace mercury\constants\state;

/**
 * Class Service
 * @package mercury\constants\state
 *
 * 售后对应状态码
 */
interface Service
{
    const STATE_SERVICE_DELETE  = 0;    //删除状态

    const STATE_SERVICE_NORMAL  = 1;    //正常状态

    const STATE_SERVICE_AGREE   = 2;    //同意售后

    const STATE_SERVICE_BUYER_EXPRESS   = 3;    //买家邮寄商品

    const STATE_SERVICE_SELLER_RECEIVE  = 4;    //商家收到商品

    const STATE_SERVICE_SELLER_EXPRESS  = 5;    //商家邮寄商品

    const STATE_SERVICE_SELLER_REFUSE   = 41;   //商家拒绝售后

    const STATE_SERVICE_BUYER_APPEAL    = 31;   //买家提起申诉

    const STATE_SERVICE_SELLER_APPEAL   = 32;   //商家提起申诉

    const STATE_SERVICE_CANCEL  = 20;   //取消售后

    const STATE_SERVICE_SUCCESS = 100;  //完成售后

    const STATE_SERVICE_ARRAY   = [
        self::STATE_SERVICE_DELETE  => '已删除',
        self::STATE_SERVICE_NORMAL  => '买家申请售后',
        self::STATE_SERVICE_AGREE   => '商家同意售后',
        self::STATE_SERVICE_BUYER_EXPRESS   => '买家邮寄商品',
        self::STATE_SERVICE_SELLER_RECEIVE  => '商家收到商品',
        self::STATE_SERVICE_SELLER_EXPRESS  => '商家邮寄商品',
        self::STATE_SERVICE_SELLER_REFUSE   => '商家拒绝售后',
        self::STATE_SERVICE_BUYER_APPEAL    => '买家提起申诉',
        self::STATE_SERVICE_SELLER_APPEAL   => '商家提起申诉',
        self::STATE_SERVICE_CANCEL  => '售后已取消',
        self::STATE_SERVICE_SUCCESS => '售后已完成',
    ];
}