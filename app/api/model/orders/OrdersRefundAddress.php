<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20 0020
 * Time: 14:27
 */

namespace app\api\model\orders;

use think\Model;

/**
 * 退款地址表
 *
 * Class OrdersRefundAddress
 * @package app\api\Orders\model
 */
class OrdersRefundAddress extends Model
{
    protected $pk = 'orders_refund_id';
    protected $append = ['express_company_name'];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = false;
    protected $createTime = 'orders_service_create_time';
    protected $updateTime = 'orders_service_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [];
    protected $update = [];

    /**
     * 获取发货时间
     *
     * @param $value
     * @return false|string
     */
    protected function getOrdersRefundExpressTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    protected function getOrdersRefundTimeAttr($value)
    {
        if (!$value) return '';
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 获取快递公司名称
     *
     * @param $value
     * @param $data
     * @return mixed|string
     */
    protected function getExpressCompanyNameAttr($value, $data)
    {
        if (!isset($data['express_company_id'])) return '';
        return db('express_company')
            ->where(['company_id' => $data['express_company_id']])
            ->cache(true)
            ->value('company_name');
    }
}