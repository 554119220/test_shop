<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2018-01-10 20:51:37
 */
namespace app\work\model;
use think\Model;
class OrdersServiceLogs682 extends Model
{
    protected $table = 'zr_orders_service_logs';

    protected function service()
    {
        return $this->hasOne('OrdersService652', 'orders_service_id', 'orders_service_id');
    }
}
