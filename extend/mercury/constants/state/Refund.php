<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 14:49
 */

namespace mercury\constants\state;

/**
 * Class Refund
 * @package mercury\constants\state
 *
 * 只退款
 */
interface Refund
{
    /**
     * 只退款
     */
    const STATE_REFUND_DELETE   = 0;    //已删除

    const STATE_REFUND_NORMAL   = 1;    //正常

    const STATE_REFUND_CANCEL   = 20;   //已取消

    const STATE_REFUND_SUCCESS  = 100;  //已完成

    const STATE_REFUND_ARRAY    = [
        self::STATE_REFUND_DELETE   => '已删除',
        self::STATE_REFUND_NORMAL   => '用户申请退款',
        self::STATE_REFUND_CANCEL   => '退款已取消',
        self::STATE_REFUND_SUCCESS  => '退款已完成',
    ];
}