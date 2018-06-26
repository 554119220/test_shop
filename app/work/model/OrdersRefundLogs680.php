<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2018-01-10 20:10:53
 */
namespace app\work\model;
use think\Model;
class OrdersRefundLogs680 extends Model
{
    protected $table = 'zr_orders_refund_logs';

    protected function refund()
    {
        return $this->hasOne('OrdersRefund649', 'orders_refund_id', 'orders_refund_id');
    }
}
